<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil Financiero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <h3 class="text-lg font-semibold mb-4">Información Personal</h3>
                        
                        <!-- Edad -->
                        <div class="mb-4">
                            <label for="age" class="block text-sm font-medium text-gray-700">
                                Edad
                            </label>
                            <input type="number" 
                                   name="age" 
                                   id="age"
                                   value="{{ old('age', $profile->age) }}"
                                   min="18"
                                   max="75"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('age')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ingresos netos mensuales -->
                        <div class="mb-4">
                            <label for="net_income" class="block text-sm font-medium text-gray-700">
                                Ingresos netos mensuales (€)
                            </label>
                            <input type="number" 
                                   name="net_income" 
                                   id="net_income"
                                   value="{{ old('net_income', $profile->net_income) }}"
                                   min="500"
                                   max="50000"
                                   step="50"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('net_income')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tipo de contrato -->
                        <div class="mb-4">
                            <label for="contract_type" class="block text-sm font-medium text-gray-700">
                                Tipo de contrato
                            </label>
                            <select name="contract_type" 
                                    id="contract_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                @foreach($contractTypes as $type)
                                    <option value="{{ $type->value }}" 
                                            {{ old('contract_type', $profile->contract_type?->value) === $type->value ? 'selected' : '' }}>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_type')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ahorro disponible -->
                        <div class="mb-4">
                            <label for="available_savings" class="block text-sm font-medium text-gray-700">
                                Ahorro disponible (€)
                            </label>
                            <input type="number" 
                                   name="available_savings" 
                                   id="available_savings"
                                   value="{{ old('available_savings', $profile->available_savings) }}"
                                   min="0"
                                   step="100"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('available_savings')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-4 mt-8">Información Adicional</h3>
                        
                        <!-- Gastos mensuales -->
                        <div class="mb-4">
                            <label for="monthly_expenses" class="block text-sm font-medium text-gray-700">
                                Gastos mensuales actuales (€) <span class="text-gray-500">(opcional)</span>
                            </label>
                            <input type="number" 
                                   name="monthly_expenses" 
                                   id="monthly_expenses"
                                   value="{{ old('monthly_expenses', $profile->monthly_expenses) }}"
                                   min="0"
                                   step="50"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-sm text-gray-600 mt-1">
                                Incluye otros préstamos, alquiler actual, etc.
                            </p>
                            @error('monthly_expenses')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Años en el trabajo actual -->
                        <div class="mb-4">
                            <label for="years_in_job" class="block text-sm font-medium text-gray-700">
                                Años en el trabajo actual <span class="text-gray-500">(opcional)</span>
                            </label>
                            <input type="number" 
                                   name="years_in_job" 
                                   id="years_in_job"
                                   value="{{ old('years_in_job', $profile->years_in_job) }}"
                                   min="0"
                                   max="50"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('years_in_job')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Otros préstamos -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="has_other_loans" 
                                       value="1"
                                       {{ old('has_other_loans', $profile->has_other_loans) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">
                                    Tengo otros préstamos o deudas activas
                                </span>
                            </label>
                        </div>
                        
                        <!-- Información sobre ratio de endeudamiento -->
                        @if($profile->exists && $profile->net_income > 0)
                            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                                <h4 class="font-semibold text-blue-900 mb-2">Tu ratio de endeudamiento actual</h4>
                                <p class="text-blue-800">
                                    Ratio actual: <strong>{{ number_format($profile->getDebtRatio(), 1) }}%</strong>
                                </p>
                                <p class="text-sm text-blue-600 mt-1">
                                    Los bancos suelen aprobar hipotecas cuando el ratio total no supera el 35-40%
                                </p>
                            </div>
                        @endif
                        
                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                Guardar Perfil
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
</x-app-layout>