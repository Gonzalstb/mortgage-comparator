<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\MortgageProduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MortgageDataAggregatorService
{
    public function __construct(
        private BankDataService $bankDataService,
        private BankScrapingService $scrapingService
    ) {}
    
    /**
     * Obtener datos de hipotecas de todas las fuentes disponibles
     */
    public function getMortgageData(Bank $bank, array $criteria): Collection
    {
        $data = collect();
        
        // 1. Intentar obtener datos de API si está configurada
        if ($bank->api_endpoint && $bank->api_credentials) {
            $apiData = $this->bankDataService->fetchMortgageRates($bank, $criteria);
            if ($apiData) {
                $data = $data->merge($this->normalizeApiData($apiData, $bank));
            }
        }
        
        // 2. Si no hay datos de API o queremos complementar, usar scraping
        if ($data->isEmpty() || config('services.mortgage.use_scraping', true)) {
            $scrapedData = $this->scrapingService->scrapeBankRates($bank->slug, $criteria);
            if ($scrapedData) {
                $data = $data->merge($this->normalizeScrapedData($scrapedData, $bank));
            }
        }
        
        // 3. Si no hay datos en tiempo real, usar base de datos como fallback
        if ($data->isEmpty()) {
            $dbData = $this->getDataFromDatabase($bank, $criteria);
            $data = $data->merge($dbData);
        }
        
        // 4. Actualizar base de datos con datos frescos si los tenemos
        if ($data->isNotEmpty() && !$this->isFromDatabase($data)) {
            $this->updateDatabaseWithFreshData($bank, $data);
        }
        
        return $data;
    }
    
    /**
     * Normalizar datos de API
     */
    private function normalizeApiData(array $apiData, Bank $bank): Collection
    {
        return collect($apiData)->map(function ($item) use ($bank) {
            return [
                'bank_id' => $bank->id,
                'bank_name' => $bank->name,
                'product_name' => $item['name'] ?? 'Producto hipotecario',
                'interest_type' => $this->determineInterestType($item),
                'fixed_interest_rate' => $item['fixed_rate'] ?? null,
                'variable_interest_rate' => $item['variable_rate'] ?? null,
                'variable_index' => $item['index'] ?? 'Euribor',
                'differential' => $item['differential'] ?? null,
                'tae' => $item['tae'] ?? $item['annualRate'] ?? null,
                'monthly_payment' => $item['monthly_payment'] ?? null,
                'opening_commission' => $item['opening_fee'] ?? 0,
                'study_commission' => $item['study_fee'] ?? 0,
                'linked_products' => $item['linked_products'] ?? $item['requirements'] ?? [],
                'max_ltv' => $item['max_ltv'] ?? 80,
                'min_term_years' => $item['min_term'] ?? 5,
                'max_term_years' => $item['max_term'] ?? 30,
                'source' => 'api',
                'updated_at' => now(),
            ];
        });
    }
    
    /**
     * Normalizar datos de scraping
     */
    private function normalizeScrapedData(array $scrapedData, Bank $bank): Collection
    {
        return collect($scrapedData)->map(function ($item) use ($bank) {
            $isFixed = stripos($item['type'] ?? '', 'fija') !== false;
            
            return [
                'bank_id' => $bank->id,
                'bank_name' => $bank->name,
                'product_name' => $item['name'] ?? $item['type'] ?? 'Hipoteca',
                'interest_type' => $isFixed ? 'Fijo' : 'Variable',
                'fixed_interest_rate' => $isFixed ? $item['interest_rate'] : null,
                'variable_interest_rate' => !$isFixed ? $item['interest_rate'] : null,
                'variable_index' => $item['index'] ?? 'Euribor',
                'differential' => $item['differential'] ?? null,
                'tae' => $item['tae_desde'] ?? null,
                'opening_commission' => $item['commission'] ?? 0,
                'linked_products' => $item['linked_products'] ?? [],
                'max_ltv' => $item['max_ltv'] ?? 80,
                'min_term_years' => 5,
                'max_term_years' => $item['max_term'] ?? 30,
                'source' => 'scraping',
                'updated_at' => now(),
            ];
        });
    }
    
    /**
     * Obtener datos de la base de datos
     */
    private function getDataFromDatabase(Bank $bank, array $criteria): Collection
    {
        $products = MortgageProduct::where('bank_id', $bank->id)
            ->where('is_active', true)
            ->where('min_amount', '<=', $criteria['loan_amount'])
            ->where('max_amount', '>=', $criteria['loan_amount'])
            ->where('max_ltv', '>=', $criteria['ltv'] ?? 80)
            ->get();
        
        return $products->map(function ($product) use ($bank) {
            return [
                'bank_id' => $bank->id,
                'bank_name' => $bank->name,
                'product_name' => $product->name,
                'interest_type' => $product->fixed_interest_rate ? 'Fijo' : 'Variable',
                'fixed_interest_rate' => $product->fixed_interest_rate,
                'variable_interest_rate' => $product->variable_interest_rate,
                'variable_index' => $product->variable_index,
                'differential' => $product->differential,
                'tae' => $product->tae,
                'opening_commission' => $product->opening_commission,
                'study_commission' => $product->study_commission,
                'early_cancellation_fee' => $product->early_cancellation_fee,
                'linked_products' => $product->linked_products ?? [],
                'max_ltv' => $product->max_ltv,
                'min_term_years' => $product->min_term_years,
                'max_term_years' => $product->max_term_years,
                'source' => 'database',
                'updated_at' => $product->updated_at,
            ];
        });
    }
    
    /**
     * Determinar tipo de interés
     */
    private function determineInterestType(array $data): string
    {
        if (isset($data['fixed_rate']) && $data['fixed_rate'] !== null) {
            return 'Fijo';
        }
        
        if (isset($data['type'])) {
            return stripos($data['type'], 'fij') !== false ? 'Fijo' : 'Variable';
        }
        
        return 'Variable';
    }
    
    /**
     * Verificar si los datos vienen de la base de datos
     */
    private function isFromDatabase(Collection $data): bool
    {
        return $data->every(fn($item) => ($item['source'] ?? '') === 'database');
    }
    
    /**
     * Actualizar base de datos con datos frescos
     */
    private function updateDatabaseWithFreshData(Bank $bank, Collection $data): void
    {
        try {
            $data->each(function ($item) use ($bank) {
                // Solo actualizar si tenemos datos más recientes
                if (($item['source'] ?? '') !== 'database') {
                    MortgageProduct::updateOrCreate(
                        [
                            'bank_id' => $bank->id,
                            'name' => $item['product_name'],
                        ],
                        [
                            'fixed_interest_rate' => $item['fixed_interest_rate'],
                            'variable_interest_rate' => $item['variable_interest_rate'],
                            'variable_index' => $item['variable_index'],
                            'differential' => $item['differential'],
                            'opening_commission' => $item['opening_commission'] ?? 0,
                            'study_commission' => $item['study_commission'] ?? 0,
                            'linked_products' => $item['linked_products'] ?? [],
                            'max_ltv' => $item['max_ltv'] ?? 80,
                            'min_amount' => 30000,
                            'max_amount' => 2000000,
                            'min_term_years' => $item['min_term_years'] ?? 5,
                            'max_term_years' => $item['max_term_years'] ?? 30,
                            'is_active' => true,
                            'last_synced_at' => now(),
                        ]
                    );
                }
            });
            
            Log::info("Updated mortgage products for {$bank->name}");
            
        } catch (\Exception $e) {
            Log::error("Error updating database for {$bank->name}: " . $e->getMessage());
        }
    }
}