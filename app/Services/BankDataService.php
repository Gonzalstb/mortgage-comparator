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
}