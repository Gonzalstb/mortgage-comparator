<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Simulación de Hipoteca
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('simulations.calculate') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Datos de la vivienda y financiación</h3>
                            
                            <!-- Precio de la vivienda -->
                            <div class="mb-4">
                                <label for="property_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Precio de la vivienda (€)
                                </label>
                                <input type="number" 
                                       name="property_price" 
                                       id="property_price"
                                       value="{{ old('property_price', 250000) }}"
                                       min="50000"
                                       max="2000000"
                                       step="1000"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('property_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Importe del préstamo -->
                            <div class="mb-4">
                                <label for="loan_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Importe del préstamo (€)
                                </label>
                                <input type="number" 
                                       name="loan_amount" 
                                       id="loan_amount"
                                       value="{{ old('loan_amount', 200000) }}"
                                       min="30000"
                                       max="2000000"
                                       step="1000"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                <p class="text-sm text-gray-600 mt-1">
                                    Máximo 80% del valor de la vivienda
                                </p>
                                @error('loan_amount')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Plazo en años -->
                            <div class="mb-4">
                                <label for="term_years" class="block text-sm font-medium text-gray-700 mb-2">
                                    Plazo (años)
                                </label>
                                <select name="term_years" 
                                        id="term_years"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @for($i = 5; $i <= 30; $i += 5)
                                        <option value="{{ $i }}" {{ old('term_years', 25) == $i ? 'selected' : '' }}>
                                            {{ $i }} años
                                        </option>
                                    @endfor
                                </select>
                                @error('term_years')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Resumen del perfil -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold mb-2">Tu perfil</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Edad: {{ auth()->user()->profile->age }} años</p>
                                <p>Ingresos netos: {{ number_format(auth()->user()->profile->net_income, 0, ',', '.') }}€/mes</p>
                                <p>Tipo de contrato: {{ auth()->user()->profile->contract_type->label() }}</p>
                                <p>Ahorro disponible: {{ number_format(auth()->user()->profile->available_savings, 0, ',', '.') }}€</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                                Actualizar perfil
                            </a>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                <strong>Nota:</strong> Compararemos las mejores hipotecas de La Caixa, Santander, ING, BBVA y Sabadell 
                                basándonos en tu perfil financiero. Los resultados incluirán TAE, cuota mensual y requisitos.
                            </p>
                        </div>
                        
                        <!-- Botones -->
                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                                Comparar Hipotecas
                            </button>
                            <a href="{{ route('dashboard') }}" 
                               class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-calcular el LTV
        document.getElementById('property_price').addEventListener('input', calculateLTV);
        document.getElementById('loan_amount').addEventListener('input', calculateLTV);
        
        function calculateLTV() {
            const price = parseFloat(document.getElementById('property_price').value) || 0;
            const loan = parseFloat(document.getElementById('loan_amount').value) || 0;
            
            if (price > 0) {
                const ltv = (loan / price) * 100;
                const maxLoan = price * 0.8;
                
                if (loan > maxLoan) {
                    document.getElementById('loan_amount').classList.add('border-red-500');
                } else {
                    document.getElementById('loan_amount').classList.remove('border-red-500');
                }
            }
        }
    </script>
</x-app-layout>