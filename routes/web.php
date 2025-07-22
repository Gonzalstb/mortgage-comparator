<?php

use App\Http\Controllers\Admin\BankController as AdminBankController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MortgageSimulationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Simulaciones
    Route::prefix('simulations')->name('simulations.')->group(function () {
        Route::get('/', [MortgageSimulationController::class, 'index'])->name('index');
        Route::get('/create', [MortgageSimulationController::class, 'create'])->name('create');
        Route::post('/calculate', [MortgageSimulationController::class, 'calculate'])->name('calculate');
        Route::get('/{reference}', [MortgageSimulationController::class, 'show'])->name('show');
        Route::post('/{reference}/favorite', [MortgageSimulationController::class, 'toggleFavorite'])->name('favorite');
        Route::delete('/{reference}', [MortgageSimulationController::class, 'destroy'])->name('destroy');
    });
});

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión de usuarios
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    
    // Gestión de bancos
    Route::resource('banks', AdminBankController::class);
    Route::post('/banks/{bank}/toggle', [AdminBankController::class, 'toggleStatus'])->name('banks.toggle');
    Route::get('/banks/{bank}/products/create', [AdminBankController::class, 'createProduct'])->name('banks.products.create');
    Route::post('/banks/{bank}/products', [AdminBankController::class, 'storeProduct'])->name('banks.products.store');
});

require __DIR__.'/auth.php';