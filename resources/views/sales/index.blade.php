<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des ventes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Résumé des ventes -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Total des ventes</h3>
                            <p class="text-2xl font-bold">{{ $totalSales }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Chiffre d'affaires</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalRevenue, 2) }} F</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-yellow-800">Poids total vendu</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalWeight, 2) }} kg</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-800">Prix moyen/kg</h3>
                            <p class="text-2xl font-bold">{{ number_format($averagePricePerKg, 2) }} F</p>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Filtres</h3>
                        <form action="{{ route('sales.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <div>
                                <label for="sale_type" class="block text-sm font-medium text-gray-700 mb-1">Type de vente</label>
                                <select id="sale_type" name="sale_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Tous les types</option>
                                    <option value="individual" {{ $saleType === 'individual' ? 'selected' : '' }}>Individuelle</option>
                                    <option value="group" {{ $saleType === 'group' ? 'selected' : '' }}>Groupe</option>
                                    <option value="breeding" {{ $saleType === 'breeding' ? 'selected' : '' }}>Portée</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filtrer
                                </button>
                                <a href="{{ route('sales.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mb-6">
                        <div>
                            <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nouvelle vente
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('sales.report') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Rapport détaillé
                            </a>
                        </div>
                    </div>

                    <!-- Liste des ventes -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids (kg)</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/kg</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $sale->id }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $sale->sale_date->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            @if($sale->sale_type == 'individual')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Individuelle
                                                </span>
                                            @elseif($sale->sale_type == 'group')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Groupe
                                                </span>
                                            @elseif($sale->sale_type == 'breeding')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Portée
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            @if($sale->rabbit_id)
                                                Lapin: {{ $sale->rabbit->name ?? 'N/A' }}
                                            @elseif($sale->breeding_id)
                                                Portée: {{ $sale->breeding->mother->name ?? 'N/A' }} ({{ $sale->quantity }} lapereaux)
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ number_format($sale->weight_kg, 2) }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ number_format($sale->price_per_kg, 2) }} F</td>
                                        <td class="py-2 px-4 border-b border-gray-200 font-semibold">{{ number_format($sale->total_price, 2) }} F</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $sale->customer_name ?: 'Non spécifié' }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('sales.show', $sale->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('sales.edit', $sale->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 px-4 border-b border-gray-200 text-center text-gray-500" colspan="9">
                                            Aucune vente trouvée pour la période sélectionnée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>