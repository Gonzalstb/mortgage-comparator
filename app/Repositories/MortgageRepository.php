<?php

namespace App\Repositories;

use App\Models\MortgageProduct;
use Illuminate\Database\Eloquent\Collection;

class MortgageRepository
{
    public function getEligibleProducts(float $loanAmount, float $ltv): Collection
    {
        return MortgageProduct::where('is_active', true)
            ->where('min_amount', '<=', $loanAmount)
            ->where('max_amount', '>=', $loanAmount)
            ->where('max_ltv', '>=', $ltv)
            ->with('bank')
            ->get();
    }
    
    public function findById(int $id): ?MortgageProduct
    {
        return MortgageProduct::with('bank')->find($id);
    }
}