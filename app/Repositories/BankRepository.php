<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;

class BankRepository
{
    public function getAllActive(): Collection
    {
        return Bank::where('is_active', true)
            ->orderBy('priority')
            ->with('mortgageProducts')
            ->get();
    }
    
    public function findBySlug(string $slug): ?Bank
    {
        return Bank::where('slug', $slug)->first();
    }
    
    public function getWithActiveProducts(): Collection
    {
        return Bank::where('is_active', true)
            ->whereHas('mortgageProducts', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['mortgageProducts' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('priority')
            ->get();
    }
}