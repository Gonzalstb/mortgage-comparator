<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')
            ->withCount('simulations');
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        $user->load(['profile', 'simulations' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        $stats = [
            'total_simulations' => $user->simulations()->count(),
            'favorite_simulations' => $user->simulations()->where('is_favorite', true)->count(),
            'last_simulation' => $user->simulations()->latest()->first()?->created_at,
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }
}