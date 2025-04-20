<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
                {{ __('Détails de la cage') }} : {{ $cage->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('cages.edit', $cage) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('cages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Informations de la cage -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mr-2">Informations de la cage</h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $cage->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $cage->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nom</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $cage->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Emplacement</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $cage->location ?: 'Non spécifié' }}</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500">Description</p>
                                <p class="mt-1 text-gray-900">{{ $cage->description ?: 'Aucune description' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date de création</p>
                                <p class="mt-1 text-gray-900">{{ $cage->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                                <p class="mt-1 text-gray-900">{{ $cage->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Liste des lapins dans cette cage -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Lapins dans cette cage</h3>
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $cage->rabbits_count ?? count($cage->rabbits ?? []) }} lapins
                            </span>
                        </div>
                        
                        @if(isset($cage->rabbits) && count($cage->rabbits) > 0)
                            <div class="overflow-x-auto bg-white rounded-lg shadow">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($cage->rabbits as $rabbit)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $rabbit->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $rabbit->identification_number }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rabbit->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                        {{ $rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($rabbit->status == 'alive') bg-green-100 text-green-800 
                                                        @elseif($rabbit->status == 'dead') bg-red-100 text-red-800 
                                                        @elseif($rabbit->status == 'sold') bg-yellow-100 text-yellow-800 
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        @if($rabbit->status == 'alive') Vivant
                                                        @elseif($rabbit->status == 'dead') Mort
                                                        @elseif($rabbit->status == 'sold') Vendu
                                                        @elseif($rabbit->status == 'given') Donné
                                                        @else {{ $rabbit->status }} @endif
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('rabbits.show', $rabbit) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun lapin dans cette cage</h3>
                                <p class="mt-1 text-sm text-gray-500">Vous pouvez ajouter des lapins à cette cage depuis la page de gestion des lapins.</p>
                                <div class="mt-6">
                                    <a href="{{ route('rabbits.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Ajouter un lapin
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>