<?php

namespace Database\Factories;

use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'age' => $this->faker->numberBetween(25, 65),
            'net_income' => $this->faker->numberBetween(1500, 5000),
            'contract_type' => $this->faker->randomElement(ContractType::cases()),
            'available_savings' => $this->faker->numberBetween(10000, 100000),
            'monthly_expenses' => $this->faker->numberBetween(200, 1000),
            'years_in_job' => $this->faker->numberBetween(1, 20),
            'has_other_loans' => $this->faker->boolean(30),
        ];
    }
}