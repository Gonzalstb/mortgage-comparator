<?php

namespace App\Services;

use App\Models\Bank;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankDataService
{
    /**
     * Obtiene datos de hipotecas desde APIs externas o datos simulados
     */
    public function fetchMortgageRates(Bank $bank, array $criteria): ?array
    {
        // Si el banco tiene API configurada
        if ($bank->api_endpoint) {
            return $this->fetchFromApi($bank, $criteria);
        }
        
        // Por ahora devolvemos los datos de la BD
        return $this->fetchFromDatabase($bank, $criteria);
    }
    
    private function fetchFromApi(Bank $bank, array $criteria): ?array
    {
        try {
            // Estructura preparada para futuras integraciones
            $response = Http::timeout(10)
                ->withHeaders($this->getApiHeaders($bank))
                ->post($bank->api_endpoint, [
                    'loan_amount' => $criteria['loan_amount'],
                    'property_value' => $criteria['property_value'],
                    'term_years' => $criteria['term_years'],
                    'applicant' => [
                        'age' => $criteria['age'],
                        'income' => $criteria['income'],
                        'contract_type' => $criteria['contract_type'],
                    ],
                ]);
            
            if ($response->successful()) {
                return $this->parseApiResponse($bank->slug, $response->json());
            }
            
            Log::warning("API call failed for {$bank->name}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error("API exception for {$bank->name}: " . $e->getMessage());
            return null;
        }
    }
    
    private function fetchFromDatabase(Bank $bank, array $criteria): array
    {
        $products = $bank->activeProducts()
            ->where('min_amount', '<=', $criteria['loan_amount'])
            ->where('max_amount', '>=', $criteria['loan_amount'])
            ->where('max_ltv', '>=', $criteria['ltv'])
            ->get();
        
        return $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'fixed_rate' => $product->fixed_interest_rate,
                'variable_rate' => $product->variable_interest_rate,
                'max_term' => $product->max_term_years,
                'requirements' => $product->requirements,
            ];
        })->toArray();
    }
    
    private function getApiHeaders(Bank $bank): array
    {
        $credentials = $bank->api_credentials ?? [];
        
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($credentials['api_key'] ?? ''),
            'X-Client-Id' => $credentials['client_id'] ?? '',
        ];
    }
    
    private function parseApiResponse(string $bankSlug, array $response): array
    {
        // Cada banco tendrá su propio formato de respuesta
        // Este es un parser genérico que se adaptará según el banco
        
        return match($bankSlug) {
            'lacaixa' => $this->parseLaCaixaResponse($response),
            'santander' => $this->parseSantanderResponse($response),
            'ing' => $this->parseIngResponse($response),
            default => $response,
        };
    }
    
    private function parseLaCaixaResponse(array $response): array
    {
        // Simulación de parsing específico para La Caixa
        return $response;
    }
    
    private function parseSantanderResponse(array $response): array
    {
        // Simulación de parsing específico para Santander
        return $response;
    }
    
    private function parseIngResponse(array $response): array
    {
        // Simulación de parsing específico para ING
        return $response;
    }

    private function fetchFromApi(Bank $bank, array $criteria): ?array
    {
        try {
            // Llamar a la API específica de cada banco
            return match($bank->slug) {
                'ing' => $this->fetchFromINGApi($bank, $criteria),
                'bbva' => $this->fetchFromBBVAApi($bank, $criteria),
                'santander' => $this->fetchFromSantanderApi($bank, $criteria),
                'lacaixa' => $this->fetchFromLaCaixaApi($bank, $criteria),
                default => $this->fetchGenericApi($bank, $criteria),
            };
            
        } catch (\Exception $e) {
            Log::error("API exception for {$bank->name}: " . $e->getMessage());
            return null;
        }
    }
    
    private function fetchFromINGApi(Bank $bank, array $criteria): ?array
    {
        // Ejemplo de integración con API de ING (si existiera)
        $response = Http::timeout(15)
            ->withHeaders([
                'X-ING-ApplicationID' => config('services.banks.ing.app_id'),
                'X-ING-APIKey' => config('services.banks.ing.api_key'),
                'Accept' => 'application/json',
            ])
            ->post('https://api.ing.es/mortgage/v1/simulate', [
                'amount' => $criteria['loan_amount'],
                'propertyValue' => $criteria['property_value'],
                'years' => $criteria['term_years'],
                'customer' => [
                    'age' => $criteria['age'],
                    'monthlyIncome' => $criteria['income'],
                    'employmentType' => $this->mapContractType($criteria['contract_type']),
                ],
            ]);
            
        if ($response->successful()) {
            $data = $response->json();
            return [
                [
                    'product_id' => $data['productId'] ?? null,
                    'name' => $data['productName'] ?? 'Hipoteca NARANJA',
                    'fixed_rate' => $data['fixedRate'] ?? null,
                    'variable_rate' => $data['variableRate'] ?? null,
                    'monthly_payment' => $data['monthlyPayment'] ?? null,
                    'tae' => $data['annualRate'] ?? null,
                    'requirements' => $data['requirements'] ?? [],
                ],
            ];
        }
        
        return null;
    }
    
    private function fetchFromBBVAApi(Bank $bank, array $criteria): ?array
    {
        // BBVA Open Platform API
        $response = Http::timeout(15)
            ->withToken(config('services.banks.bbva.access_token'))
            ->post('https://apis.bbva.com/products-services/mortgages/v1/simulations', [
                'loan' => [
                    'amount' => $criteria['loan_amount'],
                    'term' => $criteria['term_years'] * 12, // BBVA usa meses
                ],
                'property' => [
                    'value' => $criteria['property_value'],
                    'type' => 'RESIDENTIAL',
                ],
                'applicant' => [
                    'age' => $criteria['age'],
                    'income' => [
                        'amount' => $criteria['income'],
                        'frequency' => 'MONTHLY',
                    ],
                ],
            ]);
            
        if ($response->successful()) {
            return $this->parseBBVAResponse($response->json());
        }
        
        return null;
    }
    
    private function fetchGenericApi(Bank $bank, array $criteria): ?array
    {
        // Implementación genérica para bancos con API estándar
        $response = Http::timeout(10)
            ->withHeaders($this->getApiHeaders($bank))
            ->post($bank->api_endpoint, [
                'loan_amount' => $criteria['loan_amount'],
                'property_value' => $criteria['property_value'],
                'term_years' => $criteria['term_years'],
                'applicant' => [
                    'age' => $criteria['age'],
                    'income' => $criteria['income'],
                    'contract_type' => $criteria['contract_type'],
                ],
            ]);
        
        if ($response->successful()) {
            return $this->parseApiResponse($bank->slug, $response->json());
        }
        
        return null;
    }
    
    private function mapContractType(string $type): string
    {
        return match($type) {
            'permanent' => 'PERMANENT',
            'temporary' => 'TEMPORARY',
            'freelance' => 'SELF_EMPLOYED',
            default => 'OTHER',
        };
    }
}