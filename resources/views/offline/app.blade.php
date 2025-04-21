<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Hors Ligne') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mode Hors Ligne</h3>
                    
                    <div id="offline-status" class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Vous utilisez actuellement l'application en mode hors ligne. Certaines fonctionnalités peuvent être limitées.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Remplacer les boutons existants par des liens -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lapins -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-lg text-gray-900 mb-2">Lapins</h4>
                            <p class="text-gray-600 mb-4">Accédez à la liste des lapins disponibles hors ligne.</p>
                            <a href="{{ route('offline.rabbits') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Voir les lapins
                            </a>
                        </div>
                        
                        <!-- Cages -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-lg text-gray-900 mb-2">Cages</h4>
                            <p class="text-gray-600 mb-4">Consultez les informations sur les cages disponibles hors ligne.</p>
                            <a href="{{ route('offline.cages') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Voir les cages
                            </a>
                        </div>
                        
                        <!-- Traitements -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-lg text-gray-900 mb-2">Traitements</h4>
                            <p class="text-gray-600 mb-4">Gérez les traitements médicaux en mode hors ligne.</p>
                            <a href="{{ route('offline.treatments') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Voir les traitements
                            </a>
                        </div>
                        
                        <!-- Reproductions -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-lg text-gray-900 mb-2">Reproductions</h4>
                            <p class="text-gray-600 mb-4">Consultez les informations sur les reproductions en cours.</p>
                            <a href="{{ route('offline.breedings') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Voir les reproductions
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ route('offline.sync') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Retour à la page de synchronisation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si IndexedDB est disponible
            if (!window.indexedDB) {
                alert("Votre navigateur ne supporte pas IndexedDB, nécessaire pour le mode hors ligne.");
                return;
            }
            
            // Ouvrir la base de données
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onerror = function(event) {
                console.error("Erreur d'accès à la base de données:", event.target.error);
                alert("Impossible d'accéder à la base de données locale. Le mode hors ligne peut ne pas fonctionner correctement.");
            };
            
            // Gestionnaires d'événements pour les boutons
            document.getElementById('view-rabbits').addEventListener('click', function() {
                showOfflineData('rabbits', 'Lapins');
            });
            
            document.getElementById('view-cages').addEventListener('click', function() {
                showOfflineData('cages', 'Cages');
            });
            
            document.getElementById('view-treatments').addEventListener('click', function() {
                showOfflineData('treatments', 'Traitements');
            });
            
            document.getElementById('view-breedings').addEventListener('click', function() {
                showOfflineData('breedings', 'Reproductions');
            });
            
            // Fonction pour afficher les données hors ligne
            function showOfflineData(storeName, title) {
                dbPromise.onsuccess = function(event) {
                    const db = event.target.result;
                    
                    if (!db.objectStoreNames.contains(storeName)) {
                        alert(`Aucune donnée ${title.toLowerCase()} disponible hors ligne. Veuillez d'abord synchroniser vos données.`);
                        return;
                    }
                    
                    const transaction = db.transaction([storeName], 'readonly');
                    const store = transaction.objectStore(storeName);
                    const request = store.getAll();
                    
                    request.onsuccess = function() {
                        const data = request.result;
                        
                        if (data.length === 0) {
                            alert(`Aucune donnée ${title.toLowerCase()} disponible hors ligne.`);
                            return;
                        }
                        
                        // Créer une modal pour afficher les données
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
                        
                        let content = `
                            <div class="relative bg-white rounded-lg shadow-xl mx-auto p-5 w-full max-w-4xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-medium text-gray-900">${title} (${data.length})</h3>
                                    <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>`;
                        
                        // Créer les en-têtes de colonnes en fonction du type de données
                        if (storeName === 'rabbits') {
                            content += `
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cage</th>`;
                        } else if (storeName === 'cages') {
                            content += `
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimensions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>`;
                        } else if (storeName === 'treatments') {
                            content += `
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médicament</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date prévue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>`;
                        } else if (storeName === 'breedings') {
                            content += `
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mère</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Père</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'accouplement</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance prévue</th>`;
                        }
                        
                        content += `
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">`;
                        
                        // Ajouter les lignes de données
                        data.forEach(item => {
                            content += '<tr>';
                            
                            if (storeName === 'rabbits') {
                                content += `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.gender === 'male' ? 'Mâle' : 'Femelle'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.birth_date}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.cage_id || 'Non assigné'}</td>`;
                            } else if (storeName === 'cages') {
                                content += `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.dimensions || 'Non spécifié'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.status || 'Actif'}</td>`;
                            } else if (storeName === 'treatments') {
                                content += `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.rabbit_id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.medication_id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.scheduled_at}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.status || 'En attente'}</td>`;
                            } else if (storeName === 'breedings') {
                                content += `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.mother_id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.father_id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.mating_date}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.expected_birth_date}</td>`;
                            }
                            
                            content += '</tr>';
                        });
                        
                        content += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>`;
                        
                        modal.innerHTML = content;
                        document.body.appendChild(modal);
                        
                        // Fermer la modal
                        document.getElementById('close-modal').addEventListener('click', function() {
                            document.body.removeChild(modal);
                        });
                    };
                    
                    request.onerror = function(event) {
                        console.error("Erreur lors de la récupération des données:", event.target.error);
                        alert("Erreur lors de la récupération des données. Veuillez réessayer.");
                    };
                };
            }
        });
    </script>
</x-app-layout>