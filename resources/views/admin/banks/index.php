<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-accent leading-tight">{{ __('Gestión de Bancos') }}</h2>
            <a href="{{ route('admin.banks.create') }}" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-gold transition font-semibold shadow-elegant">Añadir Banco</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-elegant rounded-2xl">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Banco</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Productos</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Prioridad</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-accent uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($banks as $bank)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-accent">{{ $bank->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $bank->slug }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $bank->mortgage_products_count }} productos</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $bank->priority }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.banks.toggle', $bank) }}">
                                                @csrf
                                                <button type="submit" class="text-sm">
                                                    @if($bank->is_active)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.banks.edit', $bank) }}" class="text-accent hover:underline mr-3">Editar</a>
                                            <a href="{{ route('admin.banks.products.create', $bank) }}" class="text-gold hover:underline">+ Producto</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $banks->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>