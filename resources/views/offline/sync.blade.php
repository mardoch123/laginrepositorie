<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Synchronisation des données') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">État de la connexion</h3>
                        <div id="connection-status" class="p-4 rounded-md bg-green-50 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        Vous êtes actuellement en ligne
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Dernière synchronisation</h3>
                        <div id="last-sync" class="text-sm text-gray-600">
                            Jamais synchronisé
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Synchroniser les données</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            La synchronisation téléchargera les dernières données du serveur et enverra les modifications locales.
                        </p>
                        <div id="sync-status" class="mb-4 text-sm text-gray-600">
                            Prêt à synchroniser
                        </div>
                        <button id="sync-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Synchroniser maintenant
                        </button>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Mode hors ligne</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Accédez à vos données même sans connexion Internet.
                        </p>
                        <a href="{{ route('offline.app') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Accéder au mode hors ligne
                        </a>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Statistiques de synchronisation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500">Lapins</h4>
                                <p id="rabbits-count" class="mt-1 text-2xl font-semibold text-gray-900">-</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500">Cages</h4>
                                <p id="cages-count" class="mt-1 text-2xl font-semibold text-gray-900">-</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500">Traitements</h4>
                                <p id="treatments-count" class="mt-1 text-2xl font-semibold text-gray-900">-</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500">Reproductions</h4>
                                <p id="breedings-count" class="mt-1 text-2xl font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if IndexedDB is available
            if (!window.indexedDB) {
                document.getElementById('sync-status').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Votre navigateur ne supporte pas IndexedDB, nécessaire pour le mode hors ligne.
                        </p>
                    </div>
                `;
                document.getElementById('sync-button').disabled = true;
                return;
            }

            // Check connection status
            updateConnectionStatus();
            window.addEventListener('online', updateConnectionStatus);
            window.addEventListener('offline', updateConnectionStatus);

            // Load last sync time
            const lastSyncTimestamp = localStorage.getItem('lastSyncTimestamp');
            if (lastSyncTimestamp) {
                const date = new Date(parseInt(lastSyncTimestamp) * 1000);
                document.getElementById('last-sync').textContent = 'Dernière synchronisation: ' + date.toLocaleString();
            }

            // Load database statistics
            loadDatabaseStats();

            // Sync button event listener
            document.getElementById('sync-button').addEventListener('click', synchronizeData);
        });

        function updateConnectionStatus() {
            const statusElement = document.getElementById('connection-status');
            
            if (navigator.onLine) {
                statusElement.className = 'p-4 rounded-md bg-green-50 border border-green-200';
                statusElement.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Vous êtes actuellement en ligne
                            </p>
                        </div>
                    </div>
                `;
                document.getElementById('sync-button').disabled = false;
            } else {
                statusElement.className = 'p-4 rounded-md bg-red-50 border border-red-200';
                statusElement.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                Vous êtes actuellement hors ligne
                            </p>
                        </div>
                    </div>
                `;
                document.getElementById('sync-button').disabled = true;
            }
        }

        function loadDatabaseStats() {
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onupgradeneeded = function(event) {
                const db = event.target.result;
                
                // Create object stores if they don't exist
                if (!db.objectStoreNames.contains('rabbits')) {
                    db.createObjectStore('rabbits', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('cages')) {
                    db.createObjectStore('cages', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('treatments')) {
                    db.createObjectStore('treatments', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('breedings')) {
                    db.createObjectStore('breedings', { keyPath: 'id' });
                }
            };
            
            dbPromise.onsuccess = function(event) {
                const db = event.target.result;
                
                // Count rabbits
                const rabbitsTransaction = db.transaction(['rabbits'], 'readonly');
                const rabbitsStore = rabbitsTransaction.objectStore('rabbits');
                const rabbitsCountRequest = rabbitsStore.count();
                
                rabbitsCountRequest.onsuccess = function() {
                    document.getElementById('rabbits-count').textContent = rabbitsCountRequest.result;
                };
                
                // Count cages
                const cagesTransaction = db.transaction(['cages'], 'readonly');
                const cagesStore = cagesTransaction.objectStore('cages');
                const cagesCountRequest = cagesStore.count();
                
                cagesCountRequest.onsuccess = function() {
                    document.getElementById('cages-count').textContent = cagesCountRequest.result;
                };
                
                // Count treatments
                const treatmentsTransaction = db.transaction(['treatments'], 'readonly');
                const treatmentsStore = treatmentsTransaction.objectStore('treatments');
                const treatmentsCountRequest = treatmentsStore.count();
                
                treatmentsCountRequest.onsuccess = function() {
                    document.getElementById('treatments-count').textContent = treatmentsCountRequest.result;
                };
                
                // Count breedings
                const breedingsTransaction = db.transaction(['breedings'], 'readonly');
                const breedingsStore = breedingsTransaction.objectStore('breedings');
                const breedingsCountRequest = breedingsStore.count();
                
                breedingsCountRequest.onsuccess = function() {
                    document.getElementById('breedings-count').textContent = breedingsCountRequest.result;
                };
            };
            
            dbPromise.onerror = function(event) {
                console.error("Error opening database:", event.target.error);
            };
        }

        function synchronizeData() {
            if (!navigator.onLine) {
                document.getElementById('sync-status').textContent = 'Impossible de synchroniser: vous êtes hors ligne';
                return;
            }
            
            document.getElementById('sync-status').textContent = 'Synchronisation en cours...';
            document.getElementById('sync-button').disabled = true;
            
            // First, collect any local changes to upload
            collectLocalChanges()
                .then(localChanges => {
                    // Upload local changes if there are any
                    if (localChanges.hasChanges) {
                        return fetch('{{ route("offline.upload") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(localChanges.data)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        });
                    }
                    return { success: true };
                })
                .then(uploadResult => {
                    if (!uploadResult.success) {
                        throw new Error('Failed to upload local changes');
                    }
                    
                    // Download latest data from server
                    return fetch('{{ route("offline.download") }}')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        });
                })
                .then(serverData => {
                    // Store the downloaded data in IndexedDB
                    return storeServerData(serverData);
                })
                .then(() => {
                    // Update UI
                    document.getElementById('sync-status').textContent = 'Synchronisation réussie!';
                    document.getElementById('sync-button').disabled = false;
                    
                    const now = new Date();
                    document.getElementById('last-sync').textContent = 'Dernière synchronisation: ' + now.toLocaleString();
                    
                    // Reload database statistics
                    loadDatabaseStats();
                })
                .catch(error => {
                    console.error('Synchronization error:', error);
                    document.getElementById('sync-status').textContent = 'Erreur de synchronisation: ' + error.message;
                    document.getElementById('sync-button').disabled = false;
                });
        }

        function collectLocalChanges() {
            return new Promise((resolve, reject) => {
                const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                
                dbPromise.onsuccess = function(event) {
                    const db = event.target.result;
                    const changes = {
                        hasChanges: false,
                        data: {
                            rabbits: [],
                            cages: [],
                            treatments: [],
                            breedings: []
                        }
                    };
                    
                    // Collect modified rabbits
                    const rabbitsTransaction = db.transaction(['rabbits'], 'readonly');
                    const rabbitsStore = rabbitsTransaction.objectStore('rabbits');
                    const rabbitsRequest = rabbitsStore.getAll();
                    
                    rabbitsRequest.onsuccess = function() {
                        const rabbits = rabbitsRequest.result.filter(rabbit => rabbit.modified);
                        if (rabbits.length > 0) {
                            changes.hasChanges = true;
                            changes.data.rabbits = rabbits;
                        }
                        
                        // Collect modified cages
                        const cagesTransaction = db.transaction(['cages'], 'readonly');
                        const cagesStore = cagesTransaction.objectStore('cages');
                        const cagesRequest = cagesStore.getAll();
                        
                        cagesRequest.onsuccess = function() {
                            const cages = cagesRequest.result.filter(cage => cage.modified);
                            if (cages.length > 0) {
                                changes.hasChanges = true;
                                changes.data.cages = cages;
                            }
                            
                            // Collect modified treatments
                            const treatmentsTransaction = db.transaction(['treatments'], 'readonly');
                            const treatmentsStore = treatmentsTransaction.objectStore('treatments');
                            const treatmentsRequest = treatmentsStore.getAll();
                            
                            treatmentsRequest.onsuccess = function() {
                                const treatments = treatmentsRequest.result.filter(treatment => treatment.modified);
                                if (treatments.length > 0) {
                                    changes.hasChanges = true;
                                    changes.data.treatments = treatments;
                                }
                                
                                // Collect modified breedings
                                const breedingsTransaction = db.transaction(['breedings'], 'readonly');
                                const breedingsStore = breedingsTransaction.objectStore('breedings');
                                const breedingsRequest = breedingsStore.getAll();
                                
                                breedingsRequest.onsuccess = function() {
                                    const breedings = breedingsRequest.result.filter(breeding => breeding.modified);
                                    if (breedings.length > 0) {
                                        changes.hasChanges = true;
                                        changes.data.breedings = breedings;
                                    }
                                    
                                    resolve(changes);
                                };
                            };
                        };
                    };
                    
                    rabbitsRequest.onerror = function(event) {
                        reject(event.target.error);
                    };
                };
                
                dbPromise.onerror = function(event) {
                    reject(event.target.error);
                };
            });
        }

        function storeServerData(data) {
            return new Promise((resolve, reject) => {
                const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                
                dbPromise.onsuccess = function(event) {
                    const db = event.target.result;
                    const transaction = db.transaction(['rabbits', 'cages', 'treatments', 'breedings'], 'readwrite');
                    
                    // Clear and repopulate rabbits
                    const rabbitsStore = transaction.objectStore('rabbits');
                    rabbitsStore.clear();
                    data.rabbits.forEach(rabbit => {
                        rabbitsStore.add(rabbit);
                    });
                    
                    // Clear and repopulate cages
                    const cagesStore = transaction.objectStore('cages');
                    cagesStore.clear();
                    data.cages.forEach(cage => {
                        cagesStore.add(cage);
                    });
                    
                    // Clear and repopulate treatments
                    const treatmentsStore = transaction.objectStore('treatments');
                    treatmentsStore.clear();
                    data.treatments.forEach(treatment => {
                        treatmentsStore.add(treatment);
                    });
                    
                    // Clear and repopulate breedings
                    const breedingsStore = transaction.objectStore('breedings');
                    breedingsStore.clear();
                    data.breedings.forEach(breeding => {
                        breedingsStore.add(breeding);
                    });
                    
                    transaction.oncomplete = function() {
                        // Store the timestamp
                        localStorage.setItem('lastSyncTimestamp', data.timestamp);
                        resolve();
                    };
                    
                    transaction.onerror = function(event) {
                        reject(event.target.error);
                    };
                };
                
                dbPromise.onerror = function(event) {
                    reject(event.target.error);
                };
            });
        }
    </script>
</x-app-layout>