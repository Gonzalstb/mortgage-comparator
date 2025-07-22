<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\MortgageProduct;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'name' => 'La Caixa',
                'slug' => 'lacaixa',
                'website' => 'https://www.caixabank.es',
                'priority' => 1,
                'products' => [
                    [
                        'name' => 'Hipoteca Variable',
                        'min_amount' => 50000,
                        'max_amount' => 1000000,
                        'max_ltv' => 80,
                        'variable_interest_rate' => 2.25,
                        'variable_index' => 'Euribor 12M',
                        'differential' => 0.89,
                        'min_term_years' => 5,
                        'max_term_years' => 30,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 0.25,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'seguro_hogar' => true,
                            'seguro_vida' => false,
                        ],
                        'linked_products' => ['Cuenta Nómina', 'Seguro Hogar'],
                    ],
                    [
                        'name' => 'Hipoteca Fija',
                        'min_amount' => 50000,
                        'max_amount' => 1000000,
                        'max_ltv' => 80,
                        'fixed_interest_rate' => 2.95,
                        'min_term_years' => 10,
                        'max_term_years' => 25,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 2.00,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'seguro_hogar' => true,
                            'seguro_vida' => true,
                        ],
                        'linked_products' => ['Cuenta Nómina', 'Seguro Hogar', 'Seguro Vida'],
                    ],
                ],
            ],
            [
                'name' => 'Santander',
                'slug' => 'santander',
                'website' => 'https://www.bancosantander.es',
                'priority' => 2,
                'products' => [
                    [
                        'name' => 'Hipoteca Variable Santander',
                        'min_amount' => 60000,
                        'max_amount' => 1500000,
                        'max_ltv' => 80,
                        'variable_interest_rate' => 2.15,
                        'variable_index' => 'Euribor 12M',
                        'differential' => 0.79,
                        'min_term_years' => 5,
                        'max_term_years' => 30,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 0.15,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'tarjeta_credito' => true,
                            'seguro_hogar' => true,
                        ],
                        'linked_products' => ['Cuenta 1|2|3', 'Tarjeta de Crédito', 'Seguro Hogar'],
                    ],
                    [
                        'name' => 'Hipoteca Fija Plus',
                        'min_amount' => 60000,
                        'max_amount' => 1500000,
                        'max_ltv' => 75,
                        'fixed_interest_rate' => 2.85,
                        'min_term_years' => 10,
                        'max_term_years' => 30,
                        'opening_commission' => 0.50,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 2.00,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'seguro_vida' => true,
                        ],
                        'linked_products' => ['Cuenta 1|2|3', 'Seguro Vida'],
                    ],
                ],
            ],
            [
                'name' => 'ING',
                'slug' => 'ing',
                'website' => 'https://www.ing.es',
                'priority' => 3,
                'products' => [
                    [
                        'name' => 'Hipoteca NARANJA Variable',
                        'min_amount' => 50000,
                        'max_amount' => 1000000,
                        'max_ltv' => 80,
                        'variable_interest_rate' => 1.99,
                        'variable_index' => 'Euribor 12M',
                        'differential' => 0.69,
                        'min_term_years' => 5,
                        'max_term_years' => 30,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 0,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'contratar_cuenta' => true,
                        ],
                        'linked_products' => ['Cuenta NÓMINA', 'Tarjeta de Débito'],
                    ],
                    [
                        'name' => 'Hipoteca NARANJA Fija',
                        'min_amount' => 50000,
                        'max_amount' => 1000000,
                        'max_ltv' => 80,
                        'fixed_interest_rate' => 2.69,
                        'min_term_years' => 10,
                        'max_term_years' => 20,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 0,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'contratar_cuenta' => true,
                        ],
                        'linked_products' => ['Cuenta NÓMINA'],
                    ],
                ],
            ],
            [
                'name' => 'BBVA',
                'slug' => 'bbva',
                'website' => 'https://www.bbva.es',
                'priority' => 4,
                'products' => [
                    [
                        'name' => 'Hipoteca Variable BBVA',
                        'min_amount' => 75000,
                        'max_amount' => 2000000,
                        'max_ltv' => 80,
                        'variable_interest_rate' => 2.30,
                        'variable_index' => 'Euribor 12M',
                        'differential' => 0.94,
                        'min_term_years' => 5,
                        'max_term_years' => 30,
                        'opening_commission' => 0.50,
                        'study_commission' => 500,
                        'early_cancellation_fee' => 0.25,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'seguro_hogar' => true,
                            'plan_pensiones' => false,
                        ],
                        'linked_products' => ['Cuenta Online', 'Seguro Hogar'],
                    ],
                ],
            ],
            [
                'name' => 'Sabadell',
                'slug' => 'sabadell',
                'website' => 'https://www.bancsabadell.com',
                'priority' => 5,
                'products' => [
                    [
                        'name' => 'Hipoteca Variable Sabadell',
                        'min_amount' => 60000,
                        'max_amount' => 1000000,
                        'max_ltv' => 80,
                        'variable_interest_rate' => 2.20,
                        'variable_index' => 'Euribor 12M',
                        'differential' => 0.84,
                        'min_term_years' => 5,
                        'max_term_years' => 30,
                        'opening_commission' => 0,
                        'study_commission' => 0,
                        'early_cancellation_fee' => 0.50,
                        'requirements' => [
                            'domiciliar_nomina' => true,
                            'seguro_hogar' => true,
                            'seguro_vida' => true,
                        ],
                        'linked_products' => ['Cuenta Expansión', 'Seguro Hogar', 'Seguro Vida'],
                    ],
                ],
            ],
        ];

        foreach ($banks as $bankData) {
            $products = $bankData['products'];
            unset($bankData['products']);
            
            $bank = Bank::create($bankData);
            
            foreach ($products as $product) {
                $bank->mortgageProducts()->create($product);
            }
        }
    }
}