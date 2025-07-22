<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-accent leading-tight">Simulación {{ $simulation->reference_code }}</h2>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('simulations.favorite', $simulation->reference_code) }}">
                    @csrf
                    <button type="submit" class="text-gold hover:text-accent font-semibold">
                        {{ $simulation->is_favorite ? '★ Quitar de favoritos' : '☆ Añadir a favoritos' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Resumen de la simulación -->
            <div class="bg-white shadow-elegant rounded-2xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-accent">Resumen de la simulación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-sm text-gray-600">Precio vivienda</div>
                            <div class="text-xl font-semibold text-accent">{{ number_format($simulation->property_price, 0, ',', '.') }}€</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Préstamo solicitado</div>
                            <div class="text-xl font-semibold text-accent">{{ number_format($simulation->loan_amount, 0, ',', '.') }}€</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Plazo</div>
                            <div class="text-xl font-semibold text-accent">{{ $simulation->term_years }} años</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">LTV</div>
                            <div class="text-xl font-semibold text-accent">{{ number_format($simulation->ltv, 1) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recomendación destacada -->
            @if($simulation->results['recommendation']['recommendation'] ?? null)
                @php $recommended = $simulation->results['recommendation']['recommendation']; @endphp
                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="text-green-600">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">Mejor opción: {{ $recommended['bank_name'] }} - {{ $recommended['product_name'] }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                <div><span class="text-sm text-gray-600">Cuota mensual:</span> <span class="font-semibold text-green-800 ml-1">{{ number_format($recommended['monthly_payment'], 2, ',', '.') }}€</span></div>
                                <div><span class="text-sm text-gray-600">TAE:</span> <span class="font-semibold text-green-800 ml-1">{{ number_format($recommended['tae'], 2, ',', '.') }}%</span></div>
                                <div><span class="text-sm text-gray-600">Tipo:</span> <span class="font-semibold text-green-800 ml-1">{{ $recommended['interest_type'] }}</span></div>
                            </div>
                            <p class="text-sm text-gray-700">{{ $simulation->results['recommendation']['reason'] }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Tabla comparativa -->
            <div class="bg-white shadow-elegant rounded-2xl">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-accent">Comparativa de todas las ofertas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Banco</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Interés</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">TAE</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Cuota Mensual</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Total a Pagar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Vinculaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($simulation->results['results'] as $result)
                                    <tr class="{{ !$result['meets_requirements'] ? 'opacity-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-accent">{{ $result['bank_name'] }}</div></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ $result['product_name'] }}</div></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $result['interest_type'] === 'Fijo' ? 'bg-accent/10 text-accent' : 'bg-gold/10 text-gold' }}">{{ $result['interest_type'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($result['interest_rate'], 2, ',', '.') }}% @if($result['variable_index'])<div class="text-xs text-gray-500">{{ $result['variable_index'] }} + {{ number_format($result['differential'], 2, ',', '.') }}%</div>@endif</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-semibold text-gray-900">{{ number_format($result['tae'], 2, ',', '.') }}%</div></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-semibold text-gray-900">{{ number_format($result['monthly_payment'], 2, ',', '.') }}€</div><div class="text-xs text-gray-500">{{ number_format(($result['monthly_payment'] / auth()->user()->profile->net_income) * 100, 1) }}% ingresos</div></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ number_format($result['total_payment'], 0, ',', '.') }}€</div><div class="text-xs text-gray-500">+{{ number_format($result['total_interest'], 0, ',', '.') }}€ intereses</div></td>
                                        <td class="px-6 py-4"><div class="text-xs text-gray-600">@foreach($result['linked_products'] ?? [] as $product)<span class="inline-block bg-light rounded px-2 py-1 mb-1">{{ $product }}</span>@endforeach</div></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Costes adicionales -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        <h4 class="font-semibold mb-3 text-accent">Costes iniciales por banco</h4>
                        <div class="space-y-2">@foreach($simulation->results['results'] as $result)<div class="flex justify-between text-sm"><span>{{ $result['bank_name'] }}</span><span class="font-semibold">{{ number_format($result['total_initial_costs'], 0, ',', '.') }}€</span></div>@endforeach</div>
                    </div>
                </div>
                <div class="bg-white shadow-elegant rounded-2xl">
                    <div class="p-6">
                        <h4 class="font-semibold mb-3 text-accent">Tu perfil financiero</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Edad:</span><span>{{ $simulation->results['user_profile']['age'] }} años</span></div>
                            <div class="flex justify-between"><span>Ingresos netos:</span><span>{{ number_format($simulation->results['user_profile']['net_income'], 0, ',', '.') }}€/mes</span></div>
                            <div class="flex justify-between"><span>Tipo contrato:</span><span>{{ $simulation->results['user_profile']['contract_type'] }}</span></div>
                            <div class="flex justify-between"><span>Ratio deuda actual:</span><span>{{ number_format($simulation->results['user_profile']['debt_ratio'], 1) }}%</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Acciones -->
            <div class="mt-6 flex gap-4">
                <a href="{{ route('simulations.create') }}" class="bg-accent text-white px-6 py-3 rounded-lg hover:bg-gold transition font-semibold shadow-elegant">Nueva Simulación</a>
                <a href="{{ route('simulations.index') }}" class="bg-gray-200 text-accent px-6 py-3 rounded-lg hover:bg-accent hover:text-white transition font-semibold shadow-elegant">Ver Historial</a>
            </div>
        </div>
    </div>
</x-app-layout>