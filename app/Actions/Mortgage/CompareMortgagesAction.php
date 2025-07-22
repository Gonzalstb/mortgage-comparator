<?php

namespace App\Actions\Mortgage;

use App\Models\User;
use App\Repositories\MortgageRepository;
use App\Services\MortgageCalculatorService;
use App\Services\RecommendationService;
use Illuminate\Support\Collection;

class CompareMortgagesAction
{
    public function __construct(
        private MortgageRepository $mortgageRepo,
        private MortgageCalculatorService $calculator,
        private RecommendationService $recommendationService
    ) {}
    
    public function execute(
        User $user,
        float $propertyPrice,
        float $loanAmount,
        int $termYears
    ): array {
        $profile = $user->profile;
        
        if (!$profile) {
            throw new \Exception('Usuario sin perfil completo');
        }
        
        $ltv = ($loanAmount / $propertyPrice) * 100;
        
        // Obtener productos elegibles
        $products = $this->mortgageRepo->getEligibleProducts($loanAmount, $ltv);
        
        // Calcular cada hipoteca
        $results = new Collection();
        
        foreach ($products as $product) {
            $calculation = $this->calculator->calculateMortgage(
                $product,
                $loanAmount,
                $termYears,
                $profile
            );
            
            $results->push($calculation);
        }
        
        // Obtener recomendación
        $recommendation = $this->recommendationService->recommendBestMortgage(
            $profile,
            $results
        );
        
        return [
            'property_price' => $propertyPrice,
            'loan_amount' => $loanAmount,
            'ltv' => round($ltv, 2),
            'term_years' => $termYears,
            'results' => $results->sortBy('monthly_payment')->values(),
            'recommendation' => $recommendation,
            'user_profile' => [
                'age' => $profile->age,
                'net_income' => $profile->net_income,
                'contract_type' => $profile->contract_type->label(),
                'debt_ratio' => $profile->getDebtRatio(),
            ],
        ];
    }
}