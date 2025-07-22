<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-accent leading-tight">{{ __('Historial de Simulaciones') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-elegant rounded-2xl">
                <div class="p-6">
                    @if($simulations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Referencia</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Precio Vivienda</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Préstamo</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Plazo</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($simulations as $simulation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-accent">{{ $simulation->reference_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $simulation->created_at->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($simulation->property_price, 0, ',', '.') }}€</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($simulation->loan_amount, 0, ',', '.') }}€</div>
                                                <div class="text-xs text-gray-500">LTV: {{ number_format($simulation->ltv, 1) }}%</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $simulation->term_years }} años</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($simulation->is_favorite)
                                                    <span class="text-gold font-semibold">★ Favorito</span>
                                                @endif
                                                @if($simulation->viewed_at)
                                                    <div class="text-xs text-gray-500">Visto: {{ $simulation->viewed_at->format('d/m/Y') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('simulations.show', $simulation->reference_code) }}" class="text-accent hover:underline mr-3">Ver</a>
                                                <form method="POST" action="{{ route('simulations.destroy', $simulation->reference_code) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Estás seguro de eliminar esta simulación?')">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $simulations->links() }}</div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No tienes simulaciones guardadas.</p>
                            <a href="{{ route('simulations.create') }}" class="bg-accent text-white px-6 py-3 rounded-lg hover:bg-gold transition font-semibold shadow-elegant">Crear Primera Simulación</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>