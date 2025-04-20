<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
                {{ __('Gestion des cages') }}
            </h2>
            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                {{ $cages->total() }} {{ Str::plural('cage', $cages->total()) }}
            </span>
        </div>
    </x-slot>

    <!-- Notification container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de notification -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md flex justify-between items-center" role="alert">
                    <div>{{ session('success') }}</div>
                    <button class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md flex justify-between items-center" role="alert">
                    <div>{{ session('error') }}</div>
                    <button class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- En-tête avec bouton d'ajout -->
            <div class="mb-6 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-medium text-gray-900">Liste des cages</h3>
                    <div class="flex space-x-1">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            {{ $cages->where('is_active', true)->count() }} actives
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                            {{ $cages->where('is_active', false)->count() }} inactives
                        </span>
                    </div>
                </div>
                <a href="{{ route('cages.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une cage
                </a>
            </div>

            <!-- Grille des cages -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cages as $cage)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg transition-all duration-200 hover:shadow-md border-l-4 {{ $cage->is_active ? 'border-green-500' : 'border-red-500' }}">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        {{ $cage->name }}
                                        @if(!$cage->is_active)
                                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-600">{{ $cage->location ?: 'Emplacement non spécifié' }}</p>
                                </div>
                                <span class="flex items-center justify-center h-8 w-8 rounded-full {{ $cage->rabbits_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $cage->rabbits_count }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                                {{ $cage->description ?: 'Aucune description' }}
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    Créée le {{ $cage->created_at->format('d/m/Y') }}
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('cages.show', $cage) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('cages.edit', $cage) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" class="text-red-600 hover:text-red-800 delete-cage" data-cage-id="{{ $cage->id }}" data-cage-name="{{ $cage->name }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 flex flex-col items-center justify-center text-center">
                            <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune cage trouvée</h3>
                            <p class="text-gray-500 mb-4">Commencez par ajouter une nouvelle cage pour vos lapins.</p>
                            <a href="{{ route('cages.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ajouter une cage
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $cages->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
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
                            <p class="text-sm text-gray-500" id="modal-description">
                                Êtes-vous sûr de vouloir supprimer cette cage ? Cette action est irréversible.
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const cancelDelete = document.getElementById('cancelDelete');
            const modalTitle = document.getElementById('modal-title');
            const modalDescription = document.getElementById('modal-description');
            const deleteButtons = document.querySelectorAll('.delete-cage');
            
            // Fonction pour afficher les notifications
            function showNotification(message, type = 'success') {
                const container = document.getElementById('notification-container');
                const notification = document.createElement('div');
                
                // Classes Tailwind pour les différents types de notifications
                const classes = {
                    success: 'bg-green-100 border-l-4 border-green-500 text-green-700',
                    error: 'bg-red-100 border-l-4 border-red-500 text-red-700',
                    warning: 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
                    info: 'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
                };
                
                notification.className = `${classes[type]} p-4 rounded shadow-md mb-2 flex justify-between items-center`;
                notification.innerHTML = `
                    <div>${message}</div>
                    <button class="text-gray-500 hover:text-gray-800 focus:outline-none">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(notification);
                
                // Ajouter un gestionnaire d'événements pour fermer la notification
                notification.querySelector('button').addEventListener('click', function() {
                    notification.remove();
                });
                
                // Supprimer automatiquement la notification après 5 secondes
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
            
            // Ouvrir le modal de confirmation
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cageId = this.getAttribute('data-cage-id');
                    const cageName = this.getAttribute('data-cage-name');
                    
                    // Mettre à jour le titre et la description du modal
                    modalTitle.textContent = `Confirmer la suppression de "${cageName}"`;
                    modalDescription.textContent = `Êtes-vous sûr de vouloir supprimer la cage "${cageName}" ? Cette action est irréversible.`;
                    
                    // Mettre à jour l'action du formulaire
                    deleteForm.action = `{{ url('cages') }}/${cageId}`;
                    
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