<?php

namespace App\Repositories;

use App\Models\Simulation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SimulationRepository
{
    public function getUserSimulations(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->simulations()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    public function getFavoriteSimulations(User $user): Collection
    {
        return $user->simulations()
            ->where('is_favorite', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function getRecentSimulations(User $user, int $limit = 5): Collection
    {
        return $user->simulations()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    public function findByReference(string $reference): ?Simulation
    {
        return Simulation::where('reference_code', $reference)->first();
    }
}