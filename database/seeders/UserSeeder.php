<?php

namespace Database\Seeders;

use App\Enums\ContractType;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin Rankia',
            'email' => 'admin@rankia.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);

        // Usuario de prueba
        $user = User::create([
            'name' => 'Juan García',
            'email' => 'juan@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::USER,
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'age' => 32,
            'net_income' => 2500,
            'contract_type' => ContractType::PERMANENT,
            'available_savings' => 45000,
            'monthly_expenses' => 400,
            'years_in_job' => 5,
            'has_other_loans' => false,
        ]);

        // Más usuarios de prueba
        $user2 = User::create([
            'name' => 'María López',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::USER,
        ]);

        UserProfile::create([
            'user_id' => $user2->id,
            'age' => 28,
            'net_income' => 1800,
            'contract_type' => ContractType::TEMPORARY,
            'available_savings' => 25000,
            'monthly_expenses' => 200,
            'years_in_job' => 2,
            'has_other_loans' => true,
        ]);
    }
}