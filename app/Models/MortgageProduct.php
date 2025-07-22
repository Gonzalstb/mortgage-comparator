<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MortgageProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'name',
        'min_amount',
        'max_amount',
        'max_ltv',
        'fixed_interest_rate',
        'variable_interest_rate',
        'variable_index',
        'differential',
        'min_term_years',
        'max_term_years',
        'opening_commission',
        'study_commission',
        'early_cancellation_fee',
        'requirements',
        'linked_products',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'max_ltv' => 'decimal:2',
            'fixed_interest_rate' => 'decimal:3',
            'variable_interest_rate' => 'decimal:3',
            'differential' => 'decimal:3',
            'opening_commission' => 'decimal:2',
            'study_commission' => 'decimal:2',
            'early_cancellation_fee' => 'decimal:2',
            'requirements' => 'array',
            'linked_products' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function getTaeAttribute(): float
    {
        // Cálculo simplificado de TAE
        $nominalRate = $this->fixed_interest_rate ?? $this->variable_interest_rate ?? 0;
        $periods = 12; // mensual
        
        return ((1 + ($nominalRate / 100) / $periods) ** $periods - 1) * 100;
    }
}