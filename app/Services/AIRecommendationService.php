<?php

namespace App\Services;

use App\Models\UserProfile;

class AIRecommendationService
{
    public function analyzeUserProfile(UserProfile $profile): array
    {
        // Preparar datos para el modelo
        $features = [
            'age' => $profile->age,
            'income' => $profile->net_income,
            'contract_stability' => $this->getContractStability($profile->contract_type),
            'debt_ratio' => $profile->getDebtRatio(),
            'savings_ratio' => $profile->available_savings / ($profile->net_income * 12),
        ];
        
        // Llamar a API de ML (OpenAI, TensorFlow, etc.)
        // return $this->mlClient->predict($features);
        
        return $features;
    }
    
    private function getContractStability($contractType): float
    {
        return match($contractType->value) {
            'permanent' => 1.0,
            'temporary' => 0.6,
            'freelance' => 0.4,
            'unemployed' => 0.0,
        };
    }
}