<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du lapin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $rabbit->name }}</h3>
                        <div>
                            <a href="{{ route('rabbits.edit', $rabbit) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-800 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Modifier
                            </a>
                            <form action="{{ route('rabbits.destroy', $rabbit) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce lapin?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-2">Informations générales</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Numéro d'identification:</span>
                                    <p class="mt-1">{{ $rabbit->identification_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Sexe:</span>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rabbit->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Date de naissance:</span>
                                    <p class="mt-1">{{ $rabbit->birth_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Âge:</span>
                                    <p class="mt-1">{{ $rabbit->age }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Statut:</span>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($rabbit->status == 'alive') bg-green-100 text-green-800 
                                            @elseif($rabbit->status == 'dead') bg-red-100 text-red-800 
                                            @elseif($rabbit->status == 'sold') bg-yellow-100 text-yellow-800 
                                            @else bg-blue-100 text-blue-800 @endif">
                                            @if($rabbit->status == 'alive') Vivant 
                                            @elseif($rabbit->status == 'dead') Mort 
                                            @elseif($rabbit->status == 'sold') Vendu 
                                            @else Donné @endif
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Actif:</span>
                                    <p class="mt-1">
                                        @if($rabbit->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Oui</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Non</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-2">Caractéristiques</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Race:</span>
                                    <p class="mt-1">{{ $rabbit->breed }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-sm font-medium text-gray-500">Couleur:</span>
                                    <p class="mt-1">{{ $rabbit->color ?: 'Non spécifiée' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Cage:</span>
                                    <p class="mt-1">{{ $rabbit->cage ? $rabbit->cage->name : 'Non assignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($rabbit->notes)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-700 mb-2">Notes</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-line">{{ $rabbit->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('rabbits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>