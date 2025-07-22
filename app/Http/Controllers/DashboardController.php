<?php

namespace App\Http\Controllers;

use App\Repositories\SimulationRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private SimulationRepository $simulationRepo
    ) {}
    
    public function index(Request $request)
    {
        $user = $request->user();
        
        $recentSimulations = $this->simulationRepo->getRecentSimulations($user, 5);
        $favoriteSimulations = $this->simulationRepo->getFavoriteSimulations($user);
        
        $stats = [
            'total_simulations' => $user->simulations()->count(),
            'favorite_count' => $favoriteSimulations->count(),
            'profile_complete' => $user->profile !== null,
        ];
        
        return view('dashboard', compact('recentSimulations', 'favoriteSimulations', 'stats'));
    }
}