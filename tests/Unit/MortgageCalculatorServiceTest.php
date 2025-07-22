<?php

namespace Tests\Unit;

use App\Services\MortgageCalculatorService;
use App\Models\MortgageProduct;
use App\Models\UserProfile;
use App\Models\Bank;
use Tests\TestCase;

class MortgageCalculatorServiceTest extends TestCase
{
    public function test_calculates_monthly_payment_correctly()
    {
        $service = new MortgageCalculatorService();
        
        $bank = Bank::factory()->create();
        $product = MortgageProduct::factory()->create([
            'bank_id' => $bank->id,
            'fixed_interest_rate' => 3.0,
        ]);
        
        $profile = UserProfile::factory()->make([
            'net_income' => 3000,
            'monthly_expenses' => 500,
        ]);
        
        $result = $service->calculateMortgage(
            $product,
            200000, // loan amount
            25,     // years
            $profile
        );
        
        // Verificar que la cuota mensual sea aproximadamente correcta
        // Para 200k€ a 25 años al 3% ≈ 948€/mes
        $this->assertEqualsWithDelta(948, $result['monthly_payment'], 10);
    }
}