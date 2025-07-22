<?php

namespace App\Services;

use App\Models\UserProfile;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Analiza los resultados y recomienda la mejor hipoteca
     * Preparado para integrar IA en el futuro
     */
    public function recommendBestMortgage(
        UserProfile $profile,
        Collection $mortgageResults
    ): array {
        // Filtrar solo las hipotecas que cumplen requisitos
        $eligibleMortgages = $mortgageResults->filter(fn($m) => $m['meets_requirements']);
        
        if ($eligibleMortgages->isEmpty()) {
            return [
                'recommendation' => null,
                'reason' => 'No hay hipotecas disponibles que cumplan con tu perfil.',
                'suggestions' => $this->getSuggestions($profile),
            ];
        }
        
        // Scoring básico (preparado para ML)
        $scoredMortgages = $eligibleMortgages->map(function ($mortgage) use ($profile) {
            $score = 0;
            
            // Menor TAE = mejor puntuación
            $score += (10 - $mortgage['tae']) * 10;
            
            // Menor cuota mensual respecto a ingresos
            $affordabilityRatio = $mortgage['monthly_payment'] / $profile->net_income;
            $score += (1 - $affordabilityRatio) * 20;
            
            // Menos productos vinculados = mejor
            $linkedProductsCount = count($mortgage['linked_products'] ?? []);
            $score += (5 - $linkedProductsCount) * 5;
            
            // Bonus por tipo fijo si el usuario es conservador
            if ($profile->contract_type->value !== 'permanent' && $mortgage['interest_type'] === 'Fijo') {
                $score += 10;
            }
            
            return array_merge($mortgage, ['score' => $score]);
        });
        
        // Ordenar por puntuación
        $bestMortgage = $scoredMortgages->sortByDesc('score')->first();
        
        return [
            'recommendation' => $bestMortgage,
            'reason' => $this->getRecommendationReason($bestMortgage, $profile),
            'alternatives' => $scoredMortgages->sortByDesc('score')->skip(1)->take(2)->values(),
            'factors_considered' => [
                'tae' => 'Tasa Anual Equivalente',
                'monthly_payment' => 'Cuota mensual vs ingresos',
                'linked_products' => 'Productos vinculados requeridos',
                'interest_type' => 'Tipo de interés según tu perfil',
            ],
        ];
    }
    
    private function getRecommendationReason(array $mortgage, UserProfile $profile): string
    {
        $reasons = [];
        
        if ($mortgage['tae'] < 3.0) {
            $reasons[] = 'TAE muy competitiva';
        }
        
        $affordabilityRatio = $mortgage['monthly_payment'] / $profile->net_income;
        if ($affordabilityRatio < 0.30) {
            $reasons[] = 'cuota mensual muy asequible para tus ingresos';
        }
        
        if (empty($mortgage['opening_cost']) && empty($mortgage['study_cost'])) {
            $reasons[] = 'sin comisiones de apertura ni estudio';
        }
        
        return 'Recomendada por: ' . implode(', ', $reasons);
    }
    
    private function getSuggestions(UserProfile $profile): array
    {
        $suggestions = [];
        
        if ($profile->getDebtRatio() > 35) {
            $suggestions[] = 'Reducir gastos mensuales actuales';
        }
        
        if ($profile->available_savings < 50000) {
            $suggestions[] = 'Aumentar el ahorro disponible para la entrada';
        }
        
        if ($profile->contract_type->value === 'temporary') {
            $suggestions[] = 'Conseguir un contrato indefinido mejorará las condiciones';
        }
        
        return $suggestions;
    }
}