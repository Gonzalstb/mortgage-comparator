<?php

namespace Tests\Unit;

use App\Services\RecommendationService;
use App\Models\UserProfile;
use App\Enums\ContractType;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    public function test_recommends_best_mortgage_based_on_score()
    {
        $service = new RecommendationService();
        
        $profile = UserProfile::factory()->make([
            'net_income' => 3000,
            'contract_type' => ContractType::PERMANENT,
        ]);
        
        $mortgages = new Collection([
            [
                'bank_name' => 'ING',
                'tae' => 2.5,
                'monthly_payment' => 800,
                'linked_products' => ['Cuenta'],
                'meets_requirements' => true,
                'interest_type' => 'Variable',
            ],
            [
                'bank_name' => 'Santander',
                'tae' => 2.8,
                'monthly_payment' => 850,
                'linked_products' => ['Cuenta', 'Seguro'],
                'meets_requirements' => true,
                'interest_type' => 'Fijo',
            ],
        ]);
        
        $result = $service->recommendBestMortgage($profile, $mortgages);
        
        $this->assertNotNull($result['recommendation']);
        $this->assertEquals('ING', $result['recommendation']['bank_name']);
    }
}