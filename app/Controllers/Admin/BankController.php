<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\MortgageProduct;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::withCount('mortgageProducts')
            ->orderBy('priority')
            ->paginate(10);
            
        return view('admin.banks.index', compact('banks'));
    }
    
    public function create()
    {
        return view('admin.banks.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:banks,slug',
            'website' => 'nullable|url',
            'logo_url' => 'nullable|url',
            'api_endpoint' => 'nullable|url',
            'priority' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        Bank::create($validated);
        
        return redirect()->route('admin.banks.index')
            ->with('success', 'Banco creado correctamente');
    }
    
    public function edit(Bank $bank)
    {
        $products = $bank->mortgageProducts()->paginate(10);
        
        return view('admin.banks.edit', compact('bank', 'products'));
    }
    
    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:banks,slug,' . $bank->id,
            'website' => 'nullable|url',
            'logo_url' => 'nullable|url',
            'api_endpoint' => 'nullable|url',
            'priority' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $bank->update($validated);
        
        return redirect()->route('admin.banks.edit', $bank)
            ->with('success', 'Banco actualizado correctamente');
    }
    
    public function toggleStatus(Bank $bank)
    {
        $bank->update(['is_active' => !$bank->is_active]);
        
        return redirect()->back()
            ->with('success', 'Estado del banco actualizado');
    }
    
    public function createProduct(Bank $bank)
    {
        return view('admin.banks.create-product', compact('bank'));
    }
    
    public function storeProduct(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'max_ltv' => 'required|numeric|min:0|max:100',
            'fixed_interest_rate' => 'nullable|numeric|min:0|max:20',
            'variable_interest_rate' => 'nullable|numeric|min:0|max:20',
            'variable_index' => 'nullable|string|max:50',
            'differential' => 'nullable|numeric|min:0|max:10',
            'min_term_years' => 'required|integer|min:1',
            'max_term_years' => 'required|integer|gte:min_term_years',
            'opening_commission' => 'required|numeric|min:0|max:5',
            'study_commission' => 'required|numeric|min:0',
            'early_cancellation_fee' => 'required|numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);
        
        $validated['bank_id'] = $bank->id;
        $validated['is_active'] = $request->has('is_active');
        
        // Procesar productos vinculados
        $linkedProducts = $request->input('linked_products', []);
        $validated['linked_products'] = array_filter($linkedProducts);
        
        MortgageProduct::create($validated);
        
        return redirect()->route('admin.banks.edit', $bank)
            ->with('success', 'Producto hipotecario creado correctamente');
    }
}