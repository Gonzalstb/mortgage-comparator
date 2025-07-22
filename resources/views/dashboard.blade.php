<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-accent leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Simulaciones</div>
                        <div class="text-3xl font-bold text-accent">{{ $stats['total_simulations'] }}</div>
                    </div>
                </div>
                
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Favoritos</div>
                        <div class="text-3xl font-bold text-gold">{{ $stats['favorite_count'] }}</div>
                    </div>
                </div>
                
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        @if($stats['profile_complete'])
                            <div class="text-green-600 text-sm">Perfil Completo</div>
                            <div class="text-lg font-semibold text-green-800">✓ Listo para simular</div>
                        @else
                            <div class="text-yellow-600 text-sm">Perfil Incompleto</div>
                            <a href="{{ route('profile.edit') }}" class="text-accent hover:underline">Completar perfil →</a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            <div class="bg-white shadow-elegant rounded-2xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-accent">Acciones Rápidas</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('simulations.create') }}" class="bg-accent text-white px-6 py-3 rounded-lg hover:bg-gold transition font-semibold shadow-elegant">Nueva Simulación</a>
                        <a href="{{ route('simulations.index') }}" class="bg-gray-200 text-accent px-6 py-3 rounded-lg hover:bg-accent hover:text-white transition font-semibold shadow-elegant">Ver Historial</a>
                        <a href="{{ route('profile.edit') }}" class="bg-white border border-accent text-accent px-6 py-3 rounded-lg hover:bg-accent hover:text-white transition font-semibold shadow-elegant">Actualizar Perfil</a>
                    </div>
                </div>
            </div>
            
            <!-- Simulaciones recientes -->
            @if($recentSimulations->count() > 0)
                <div class="bg-white shadow-elegant rounded-2xl mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-accent">Simulaciones Recientes</h3>
                        <div class="space-y-3">
                            @foreach($recentSimulations as $simulation)
                                <div class="border rounded-lg p-4 hover:bg-light transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-semibold text-accent">{{ $simulation->reference_code }}</div>
                                            <div class="text-sm text-gray-600">
                                                Vivienda: {{ number_format($simulation->property_price, 0, ',', '.') }}€
                                                | Préstamo: {{ number_format($simulation->loan_amount, 0, ',', '.') }}€
                                                | {{ $simulation->term_years }} años
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $simulation->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('simulations.show', $simulation->reference_code) }}" class="text-accent hover:underline text-sm">Ver detalles</a>
                                            @if($simulation->is_favorite)
                                                <span class="text-gold">★</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Favoritos -->
            @if($favoriteSimulations->count() > 0)
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gold">Simulaciones Favoritas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($favoriteSimulations as $simulation)
                                <div class="border rounded-lg p-4 bg-yellow-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-semibold flex items-center gap-2 text-gold">
                                                <span>★</span>
                                                {{ $simulation->reference_code }}
                                            </div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                Préstamo: {{ number_format($simulation->loan_amount, 0, ',', '.') }}€
                                                a {{ $simulation->term_years }} años
                                            </div>
                                            @if($simulation->results['recommendation']['recommendation'] ?? null)
                                                <div class="text-xs text-green-600 mt-2">
                                                    Recomendado: {{ $simulation->results['recommendation']['recommendation']['bank_name'] }}
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('simulations.show', $simulation->reference_code) }}" class="text-accent hover:underline text-sm">Ver →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>