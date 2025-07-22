<?php

namespace App\Http\Controllers;

use App\Enums\ContractType;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? new UserProfile();
        $contractTypes = ContractType::cases();
        
        return view('profile.edit', compact('user', 'profile', 'contractTypes'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'age' => 'required|integer|min:18|max:75',
            'net_income' => 'required|numeric|min:500|max:50000',
            'contract_type' => ['required', new Enum(ContractType::class)],
            'available_savings' => 'required|numeric|min:0',
            'monthly_expenses' => 'nullable|numeric|min:0',
            'years_in_job' => 'nullable|integer|min:0|max:50',
            'has_other_loans' => 'boolean',
        ]);
        
        $user = $request->user();
        
        if ($user->profile) {
            $user->profile->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            UserProfile::create($validated);
        }
        
        return redirect()->route('profile.edit')
            ->with('success', 'Perfil actualizado correctamente');
    }
}