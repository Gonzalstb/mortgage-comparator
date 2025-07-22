<?php

namespace App\Http\Controllers;

use App\Actions\Mortgage\CompareMortgagesAction;
use App\Models\Simulation;
use App\Repositories\SimulationRepository;
use Illuminate\Http\Request;

class MortgageSimulationController extends Controller
{
    public function __construct(
        private CompareMortgagesAction $compareMortgagesAction,
        private SimulationRepository $simulationRepo
    ) {}
    
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->profile) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Completa tu perfil antes de simular una hipoteca');
        }
        
        return view('simulations.create');
    }
    
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'property_price' => 'required|numeric|min:50000|max:2000000',
            'loan_amount' => 'required|numeric|min:30000|max:2000000',
            'term_years' => 'required|integer|min:5|max:30',
        ]);
        
        // Validar que el préstamo no supere el 80% del valor
        $maxLoan = $validated['property_price'] * 0.8;
        if ($validated['loan_amount'] > $maxLoan) {
            return back()->withErrors([
                'loan_amount' => 'El préstamo no puede superar el 80% del valor de la vivienda',
            ])->withInput();
        }
        
        try {
            $results = $this->compareMortgagesAction->execute(
                $request->user(),
                $validated['property_price'],
                $validated['loan_amount'],
                $validated['term_years']
            );
            
            // Guardar simulación
            $simulation = Simulation::create([
                'user_id' => $request->user()->id,
                'property_price' => $validated['property_price'],
                'loan_amount' => $validated['loan_amount'],
                'term_years' => $validated['term_years'],
                'user_data' => $request->user()->profile->toArray(),
                'results' => $results,
            ]);
            
            return redirect()->route('simulations.show', $simulation->reference_code);
            
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al calcular las hipotecas: ' . $e->getMessage(),
            ])->withInput();
        }
    }
    
    public function show(string $reference)
    {
        $simulation = $this->simulationRepo->findByReference($reference);
        
        if (!$simulation || $simulation->user_id !== auth()->id()) {
            abort(404);
        }
        
        $simulation->markAsViewed();
        
        return view('simulations.show', compact('simulation'));
    }
    
    public function index(Request $request)
    {
        $simulations = $this->simulationRepo->getUserSimulations($request->user());
        
        return view('simulations.index', compact('simulations'));
    }
    
    public function toggleFavorite(string $reference)
    {
        $simulation = $this->simulationRepo->findByReference($reference);
        
        if (!$simulation || $simulation->user_id !== auth()->id()) {
            abort(404);
        }
        
        $simulation->toggleFavorite();
        
        return redirect()->back()->with('success', 
            $simulation->is_favorite ? 'Añadido a favoritos' : 'Eliminado de favoritos'
        );
    }
    
    public function destroy(string $reference)
    {
        $simulation = $this->simulationRepo->findByReference($reference);
        
        if (!$simulation || $simulation->user_id !== auth()->id()) {
            abort(404);
        }
        
        $simulation->delete();
        
        return redirect()->route('simulations.index')
            ->with('success', 'Simulación eliminada correctamente');
    }
}