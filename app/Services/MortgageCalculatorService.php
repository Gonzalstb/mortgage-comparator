<?php

namespace App\Services;

use App\Models\MortgageProduct;
use App\Models\UserProfile;

class MortgageCalculatorService
{
    public function calculateMortgage(
        MortgageProduct $product,
        float $loanAmount,
        int $termYears,
        UserProfile $profile
    ): array {
        $monthlyRate = $this->getMonthlyRate($product);
        $totalMonths = $termYears * 12;
        
        // Fórmula de cuota mensual
        $monthlyPayment = $loanAmount * 
            ($monthlyRate * pow(1 + $monthlyRate, $totalMonths)) / 
            (pow(1 + $monthlyRate, $totalMonths) - 1);
        
        // Calcular costes adicionales
        $openingCost = $loanAmount * ($product->opening_commission / 100);
        $studyCost = $product->study_commission;
        $totalInitialCosts = $openingCost + $studyCost;
        
        // Calcular total a pagar
        $totalPayment = $monthlyPayment * $totalMonths;
        $totalInterest = $totalPayment - $loanAmount;
        
        // Calcular TAE aproximada
        $tae = $this->calculateTAE(
            $loanAmount - $totalInitialCosts,
            $monthlyPayment,
            $totalMonths
        );
        
        // Evaluar si el usuario cumple requisitos
        $meetsRequirements = $this->checkRequirements($product, $profile, $loanAmount);
        
        return [
            'bank_name' => $product->bank->name,
            'product_name' => $product->name,
            'loan_amount' => $loanAmount,
            'term_years' => $termYears,
            'interest_rate' => $product->fixed_interest_rate ?? $product->variable_interest_rate,
            'interest_type' => $product->fixed_interest_rate ? 'Fijo' : 'Variable',
            'variable_index' => $product->variable_index,
            'differential' => $product->differential,
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'tae' => round($tae, 2),
            'opening_cost' => round($openingCost, 2),
            'study_cost' => round($studyCost, 2),
            'total_initial_costs' => round($totalInitialCosts, 2),
            'linked_products' => $product->linked_products,
            'meets_requirements' => $meetsRequirements,
            'max_debt_ratio' => $this->calculateDebtRatio($monthlyPayment, $profile),
        ];
    }
    
    private function getMonthlyRate(MortgageProduct $product): float
    {
        $annualRate = $product->fixed_interest_rate ?? $product->variable_interest_rate ?? 0;
        return ($annualRate / 100) / 12;
    }
    
    private function calculateTAE(float $principal, float $monthlyPayment, int $months): float
    {
        // Cálculo aproximado de TAE usando método iterativo
        $guess = 0.05;
        $increment = 0.00001;
        $maxIterations = 1000;
        
        for ($i = 0; $i < $maxIterations; $i++) {
            $pv = 0;
            for ($n = 1; $n <= $months; $n++) {
                $pv += $monthlyPayment / pow(1 + $guess / 12, $n);
            }
            
            if (abs($pv - $principal) < 0.01) {
                return $guess * 100;
            }
            
            if ($pv > $principal) {
                $guess += $increment;
            } else {
                $guess -= $increment;
            }
        }
        
        return $guess * 100;
    }
    
    private function checkRequirements(
        MortgageProduct $product,
        UserProfile $profile,
        float $loanAmount
    ): bool {
        // Verificar ratio de endeudamiento (max 35-40%)
        $monthlyRate = $this->getMonthlyRate($product);
        $monthlyPayment = $loanAmount * 
            ($monthlyRate * pow(1 + $monthlyRate, 360)) / 
            (pow(1 + $monthlyRate, 360) - 1);
            
        $debtRatio = $this->calculateDebtRatio($monthlyPayment, $profile);
        
        if ($debtRatio > 40) {
            return false;
        }
        
        // Verificar requisitos específicos del producto
        $requirements = $product->requirements ?? [];
        
        // Simplificación: asumimos que cumple requisitos vinculados
        return true;
    }
    
    private function calculateDebtRatio(float $monthlyPayment, UserProfile $profile): float
    {
        $totalMonthlyDebt = $monthlyPayment + ($profile->monthly_expenses ?? 0);
        return ($totalMonthlyDebt / $profile->net_income) * 100;
    }
}