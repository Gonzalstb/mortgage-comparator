<?php

namespace App\Models;

use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'net_income',
        'contract_type',
        'available_savings',
        'monthly_expenses',
        'years_in_job',
        'has_other_loans',
    ];

    protected function casts(): array
    {
        return [
            'net_income' => 'decimal:2',
            'available_savings' => 'decimal:2',
            'monthly_expenses' => 'decimal:2',
            'has_other_loans' => 'boolean',
            'contract_type' => ContractType::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDebtRatio(): float
    {
        if ($this->net_income <= 0) {
            return 0;
        }
        
        return ($this->monthly_expenses ?? 0) / $this->net_income * 100;
    }
}