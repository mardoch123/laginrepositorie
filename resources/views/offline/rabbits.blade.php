<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lapins (Mode Hors Ligne)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des lapins (hors ligne)</h3>
                        <a href="{{ route('offline.app') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Retour
                        </a>
                    </div>
                    
                    <div id="offline-status" class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Vous consultez les données en mode hors ligne. Ces données peuvent ne pas être à jour.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="rabbits-container" class="mt-4">
                        <div class="flex justify-center items-center h-32">
                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-500">Chargement des données...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si IndexedDB est disponible
            if (!window.indexedDB) {
                document.getElementById('rabbits-container').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Votre navigateur ne supporte pas IndexedDB, nécessaire pour le mode hors ligne.
                        </p>
                    </div>
                `;
                return;
            }
            
            // Ouvrir la base de données
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onerror = function(event) {
                console.error("Erreur d'accès à la base de données:", event.target.error);
                document.getElementById('rabbits-container').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Impossible d'accéder à la base de données locale. Le mode hors ligne peut ne pas fonctionner correctement.
                        </p>
                    </div>
                `;
            };
            
            dbPromise.onsuccess = function(event) {
                const db = event.target.result;
                
                if (!db.objectStoreNames.contains('rabbits')) {
                    document.getElementById('rabbits-container').innerHTML = `
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm text-yellow-700">
                                Aucune donnée de lapins disponible hors ligne. Veuillez d'abord synchroniser vos données.
                            </p>
                        </div>
                    `;
                    return;
                }
                
                const transaction = db.transaction(['rabbits'], 'readonly');
                const store = transaction.objectStore('rabbits');
                const request = store.getAll();
                
                request.onsuccess = function() {
                    const rabbits = request.result;
                    
                    if (rabbits.length === 0) {
                        document.getElementById('rabbits-container').innerHTML = `
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                <p class="text-sm text-yellow-700">
                                    Aucun lapin trouvé dans la base de données locale.
                                </p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Afficher les lapins dans un tableau
                    let tableHTML = `
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                    `;
                    
                    rabbits.forEach(rabbit => {
                        tableHTML += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rabbit.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${rabbit.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rabbit.gender === 'male' ? 'Mâle' : 'Femelle'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rabbit.birth_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rabbit.cage_id || 'Non assigné'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewRabbitDetails(${rabbit.id})" class="text-indigo-600 hover:text-indigo-900 mr-2">Détails</button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    tableHTML += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    
                    document.getElementById('rabbits-container').innerHTML = tableHTML;
                };
                
                request.onerror = function(event) {
                    console.error("Erreur lors de la récupération des lapins:", event.target.error);
                    document.getElementById('rabbits-container').innerHTML = `
                        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <p class="text-sm text-red-700">
                                Erreur lors de la récupération des données. Veuillez réessayer.
                            </p>
                        </div>
                    `;
                };
            };
        });
        
        // Fonction pour afficher les détails d'un lapin
        function viewRabbitDetails(rabbitId) {
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onsuccess = function(event) {
                const db = event.target.result;
                const transaction = db.transaction(['rabbits'], 'readonly');
                const store = transaction.objectStore('rabbits');
                const request = store.get(rabbitId);
                
                request.onsuccess = function() {
                    const rabbit = request.result;
                    
                    if (!rabbit) {
                        alert('Lapin non trouvé dans la base de données locale.');
                        return;
                    }
                    
                    // Créer une modal pour afficher les détails
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
                    
                    modal.innerHTML = `
                        <div class="relative bg-white rounded-lg shadow-xl mx-auto p-5 w-full max-w-2xl">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-medium text-gray-900">Détails du lapin</h3>
                                <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">ID</h4>
                                    <p class="text-base text-gray-900">${rabbit.id}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Nom</h4>
                                    <p class="text-base text-gray-900">${rabbit.name}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Sexe</h4>
                                    <p class="text-base text-gray-900">${rabbit.gender === 'male' ? 'Mâle' : 'Femelle'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Date de naissance</h4>
                                    <p class="text-base text-gray-900">${rabbit.birth_date}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Cage</h4>
                                    <p class="text-base text-gray-900">${rabbit.cage_id || 'Non assigné'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Race</h4>
                                    <p class="text-base text-gray-900">${rabbit.breed || 'Non spécifiée'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Poids</h4>
                                    <p class="text-base text-gray-900">${rabbit.weight ? rabbit.weight + ' kg' : 'Non spécifié'}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                                    <p class="text-base text-gray-900">${rabbit.notes || 'Aucune note'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                    
                    // Fermer la modal
                    document.getElementById('close-modal').addEventListener('click', function() {
                        document.body.removeChild(modal);
                    });
                };
                
                request.onerror = function(event) {
                    console.error("Erreur lors de la récupération des détails du lapin:", event.target.error);
                    alert('Erreur lors de la récupération des détails du lapin. Veuillez réessayer.');
                };
            };
        }
    </script>
</x-app-layout>