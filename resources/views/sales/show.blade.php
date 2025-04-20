<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la vente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Vente #{{ $sale->id }}
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('sales.edit', $sale->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Retour
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Type de vente</h4>
                                <p class="mt-1">
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
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Date de vente</h4>
                                <p class="mt-1">{{ $sale->sale_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Poids</h4>
                                <p class="mt-1">{{ number_format($sale->weight_kg, 2) }} kg</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Prix par kg</h4>
                                <p class="mt-1">{{ number_format($sale->price_per_kg, 2) }} F</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Prix total</h4>
                                <p class="mt-1 font-bold text-lg">{{ number_format($sale->total_price, 2) }} F</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Quantité</h4>
                                <p class="mt-1">{{ $sale->quantity }} lapin(s)</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Détails du lapin ou de la portée -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Détails de l'animal / portée</h4>
                            
                            @if($sale->rabbit_id)
                                <div class="space-y-2">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Lapin</h5>
                                        <p class="mt-1">{{ $sale->rabbit->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Sexe</h5>
                                        <p class="mt-1">
                                            @if($sale->rabbit && $sale->rabbit->gender == 'male')
                                                ♂ Mâle
                                            @elseif($sale->rabbit && $sale->rabbit->gender == 'female')
                                                ♀ Femelle
                                            @else
                                                Inconnu
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Race</h5>
                                        <p class="mt-1">{{ $sale->rabbit->breed ?? 'Non spécifiée' }}</p>
                                        </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Date de naissance</h5>
                                        <p class="mt-1">{{ $sale->rabbit && $sale->rabbit->date_of_birth ? $sale->rabbit->date_of_birth->format('d/m/Y') : 'Non spécifiée' }}</p>
                                    </div>
                                </div>
                            @elseif($sale->breeding_id)
                                <div class="space-y-2">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Portée</h5>
                                        <p class="mt-1">Portée #{{ $sale->breeding_id }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Mère</h5>
                                        <p class="mt-1">{{ $sale->breeding->mother->name ?? 'Non spécifiée' }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Père</h5>
                                        <p class="mt-1">{{ $sale->breeding->father->name ?? 'Non spécifié' }}</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Nombre de lapereaux</h5>
                                        <p class="mt-1">{{ $sale->breeding->number_of_kits ?? '0' }} ({{ $sale->breeding->number_of_males ?? '0' }} mâles, {{ $sale->breeding->number_of_females ?? '0' }} femelles)</p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500">Date de naissance</h5>
                                        <p class="mt-1">{{ $sale->breeding && $sale->breeding->birth_date ? $sale->breeding->birth_date->format('d/m/Y') : 'Non spécifiée' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">Aucune information disponible</p>
                            @endif
                        </div>

                        <!-- Informations client -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informations client</h4>
                            
                            <div class="space-y-2">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Nom du client</h5>
                                    <p class="mt-1">{{ $sale->customer_name ?: 'Non spécifié' }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Contact</h5>
                                    <p class="mt-1">{{ $sale->customer_contact ?: 'Non spécifié' }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">Notes</h5>
                                    <p class="mt-1">{{ $sale->notes ?: 'Aucune note' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>