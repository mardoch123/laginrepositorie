<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des lapins') }}
        </h2>
    </x-slot>

    <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer ce lapin ? Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                </form>
                <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
    <div class="py-12">

    <!-- Ajouter ceci juste après l'ouverture de la div py-12 -->
    @if (session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    @if (session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des lapins</h3>
                        <a href="{{ route('rabbits.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ajouter un lapin
                        </a>
                    </div>

                    <!-- Filtres -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('rabbits.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Tous</option>
                                    <option value="alive" {{ request('status') == 'alive' ? 'selected' : '' }}>Vivant</option>
                                    <option value="dead" {{ request('status') == 'dead' ? 'selected' : '' }}>Mort</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendu</option>
                                    <option value="given" {{ request('status') == 'given' ? 'selected' : '' }}>Donné</option>
                                </select>
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Sexe</label>
                                <select id="gender" name="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Tous</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Mâle</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Femelle</option>
                                </select>
                            </div>
                            <div>
                                <label for="cage_id" class="block text-sm font-medium text-gray-700">Cage</label>
                                <select id="cage_id" name="cage_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Toutes</option>
                                    @foreach($cages as $cage)
                                        <option value="{{ $cage->id }}" {{ request('cage_id') == $cage->id ? 'selected' : '' }}>{{ $cage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filtrer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Messages de succès/erreur -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Tableau des lapins -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Âge</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Race</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cage</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rabbits as $rabbit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rabbit->identification_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rabbit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rabbit->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ $rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rabbit->birth_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rabbit->age }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rabbit->breed }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rabbit->cage ? $rabbit->cage->name : 'Non assigné' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('rabbits.show', $rabbit) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Voir</a>
                                            <a href="{{ route('rabbits.edit', $rabbit) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Modifier</a>
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-900 delete-rabbit" 
                                                    data-rabbit-id="{{ $rabbit->id }}"
                                                    data-rabbit-name="{{ $rabbit->name }}">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun lapin trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $rabbits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const cancelDelete = document.getElementById('cancelDelete');
            const modalTitle = document.getElementById('modal-title');
            const deleteButtons = document.querySelectorAll('.delete-rabbit');
            
            // Ouvrir le modal de confirmation
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const rabbitId = this.getAttribute('data-rabbit-id');
                    const rabbitName = this.getAttribute('data-rabbit-name');
                    
                    // Mettre à jour le titre du modal avec le nom du lapin
                    modalTitle.textContent = `Confirmer la suppression de "${rabbitName}"`;
                    
                    // Mettre à jour l'action du formulaire
                    deleteForm.action = `{{ route('rabbits.destroy', '') }}/${rabbitId}`;
                    
                    // Afficher le modal
                    deleteModal.classList.remove('hidden');
                });
            });
            
            // Fermer le modal
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
            
            // Fermer le modal en cliquant en dehors
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>