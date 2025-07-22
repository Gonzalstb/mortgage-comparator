<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use App\Enums\ContractType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MortgageSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_simulation()
    {
        $user = User::factory()->create();
        UserProfile::factory()->create([
            'user_id' => $user->id,
            'age' => 30,
            'net_income' => 2500,
            'contract_type' => ContractType::PERMANENT,
            'available_savings' => 50000,
        ]);
        
        $this->seed(\Database\Seeders\BankSeeder::class);
        
        $response = $this->actingAs($user)
            ->post(route('simulations.calculate'), [
                'property_price' => 250000,
                'loan_amount' => 200000,
                'term_years' => 25,
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('simulations', [
            'user_id' => $user->id,
            'property_price' => 250000,
        ]);
    }
    
    public function test_user_cannot_simulate_without_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('simulations.create'));
        
        $response->assertRedirect(route('profile.edit'));
    }
    
    public function test_loan_cannot_exceed_80_percent_ltv()
    {
        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)
            ->post(route('simulations.calculate'), [
                'property_price' => 100000,
                'loan_amount' => 90000, // 90% LTV
                'term_years' => 25,
            ]);
        
        $response->assertSessionHasErrors('loan_amount');
    }
}