<?php

namespace App\Actions\Mortgage;

use App\Models\MortgageProduct;
use App\Models\UserProfile;
use App\Services\MortgageCalculatorService;

class CalculateMortgageAction
{
    public function __construct(
        private MortgageCalculatorService $calculator
    ) {}
    
    public function execute(
        MortgageProduct $product,
        float $loanAmount,
        int $termYears,
        UserProfile $profile
    ): array {
        return $this->calculator->calculateMortgage(
            $product,
            $loanAmount,
            $termYears,
            $profile
        );
    }
}