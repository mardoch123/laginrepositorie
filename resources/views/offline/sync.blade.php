<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Synchronisation hors ligne') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Statut de connexion</h3>
                    <p class="mt-2">
                        Vous êtes actuellement <span id="online-status" class="font-semibold"></span>
                    </p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Télécharger les données</h3>
                    <p class="mt-2 text-gray-600">
                        Téléchargez les données les plus récentes pour une utilisation hors ligne.
                    </p>
                    <button id="download-data" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Télécharger les données
                    </button>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Synchroniser les modifications</h3>
                    <p class="mt-2 text-gray-600">
                        Envoyez vos modifications locales au serveur.
                    </p>
                    <button id="sync-data" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                        Synchroniser maintenant
                    </button>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Application hors ligne</h3>
                    <p class="mt-2 text-gray-600">
                        Accédez à la version hors ligne de l'application.
                    </p>
                    <a href="{{ route('offline.app') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                        Ouvrir l'application hors ligne
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mettre à jour le statut de connexion
            function updateOnlineStatus() {
                const status = navigator.onLine ? 'en ligne' : 'hors ligne';
                const statusElement = document.getElementById('online-status');
                statusElement.textContent = status;
                statusElement.className = navigator.onLine ? 'text-green-500' : 'text-red-500';
                
                // Activer/désactiver les boutons selon l'état de la connexion
                document.getElementById('sync-data').disabled = !navigator.onLine;
                if (!navigator.onLine) {
                    document.getElementById('sync-data').classList.add('opacity-50');
                } else {
                    document.getElementById('sync-data').classList.remove('opacity-50');
                }
            }
            
            // Mettre à jour le statut initial
            updateOnlineStatus();
            
            // Écouter les changements de statut de connexion
            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            
            // Télécharger les données
            document.getElementById('download-data').addEventListener('click', async function() {
                try {
                    // Afficher un indicateur de chargement
                    this.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Téléchargement...';
                    this.disabled = true;
                    
                    // Ouvrir la base de données
                    const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                    
                    dbPromise.onupgradeneeded = function(event) {
                        const db = event.target.result;
                        
                        // Créer les object stores s'ils n'existent pas
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
                        if (!db.objectStoreNames.contains('foodSchedules')) {
                            db.createObjectStore('foodSchedules', { keyPath: 'id' });
                        }
                        if (!db.objectStoreNames.contains('reminders')) {
                            db.createObjectStore('reminders', { keyPath: 'id' });
                        }
                        if (!db.objectStoreNames.contains('pendingChanges')) {
                            db.createObjectStore('pendingChanges', { keyPath: 'id', autoIncrement: true });
                        }
                    };
                    
                    dbPromise.onsuccess = async function(event) {
                        const db = event.target.result;
                        
                        // Télécharger les données du serveur
                        const response = await fetch('{{ route("offline.download") }}');
                        const data = await response.json();
                        
                        // Stocker les données dans IndexedDB
                        const transaction = db.transaction([
                            'rabbits', 'cages', 'treatments', 'breedings', 
                            'foodSchedules', 'reminders'
                        ], 'readwrite');
                        
                        // Stocker les lapins
                        const rabbitsStore = transaction.objectStore('rabbits');
                        rabbitsStore.clear();
                        data.rabbits.forEach(rabbit => {
                            rabbitsStore.add(rabbit);
                        });
                        
                        // Stocker les cages
                        const cagesStore = transaction.objectStore('cages');
                        cagesStore.clear();
                        data.cages.forEach(cage => {
                            cagesStore.add(cage);
                        });
                        
                        // Stocker les traitements
                        const treatmentsStore = transaction.objectStore('treatments');
                        treatmentsStore.clear();
                        data.treatments.forEach(treatment => {
                            treatmentsStore.add(treatment);
                        });
                        
                        // Stocker les reproductions
                        const breedingsStore = transaction.objectStore('breedings');
                        breedingsStore.clear();
                        data.breedings.forEach(breeding => {
                            breedingsStore.add(breeding);
                        });
                        
                        // Stocker les planifications alimentaires
                        const foodSchedulesStore = transaction.objectStore('foodSchedules');
                        foodSchedulesStore.clear();
                        data.foodSchedules.forEach(schedule => {
                            foodSchedulesStore.add(schedule);
                        });
                        
                        // Stocker les rappels
                        const remindersStore = transaction.objectStore('reminders');
                        remindersStore.clear();
                        data.reminders.forEach(reminder => {
                            remindersStore.add(reminder);
                        });
                        
                        transaction.oncomplete = function() {
                            // Mettre à jour l'interface utilisateur
                            document.getElementById('download-data').innerHTML = 'Télécharger les données';
                            document.getElementById('download-data').disabled = false;
                            
                            // Afficher un message de succès
                            alert('Données téléchargées avec succès pour une utilisation hors ligne.');
                            
                            // Stocker la date de dernière synchronisation
                            localStorage.setItem('lastSyncTimestamp', data.timestamp);
                        };
                        
                        transaction.onerror = function(event) {
                            console.error('Erreur lors du stockage des données:', event.target.error);
                            document.getElementById('download-data').innerHTML = 'Télécharger les données';
                            document.getElementById('download-data').disabled = false;
                            alert('Erreur lors du téléchargement des données. Veuillez réessayer.');
                        };
                    };
                    
                    dbPromise.onerror = function(event) {
                        console.error('Erreur lors de l\'ouverture de la base de données:', event.target.error);
                        document.getElementById('download-data').innerHTML = 'Télécharger les données';
                        document.getElementById('download-data').disabled = false;
                        alert('Erreur lors de l\'accès à la base de données locale. Veuillez réessayer.');
                    };
                } catch (error) {
                    console.error('Erreur lors du téléchargement des données:', error);
                    document.getElementById('download-data').innerHTML = 'Télécharger les données';
                    document.getElementById('download-data').disabled = false;
                    alert('Erreur lors du téléchargement des données. Veuillez réessayer.');
                }
            });
            
            // Synchroniser les données
            document.getElementById('sync-data').addEventListener('click', async function() {
                if (!navigator.onLine) {
                    alert('Vous êtes actuellement hors ligne. Veuillez vous connecter à Internet pour synchroniser vos données.');
                    return;
                }
                
                try {
                    // Afficher un indicateur de chargement
                    this.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Synchronisation...';
                    this.disabled = true;
                    
                    // Ouvrir la base de données
                    const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                    
                    dbPromise.onsuccess = async function(event) {
                        const db = event.target.result;
                        
                        // Récupérer les changements en attente
                        const transaction = db.transaction(['pendingChanges'], 'readonly');
                        const store = transaction.objectStore('pendingChanges');
                        const request = store.getAll();
                        
                        request.onsuccess = async function() {
                            const pendingChanges = request.result;
                            
                            if (pendingChanges.length === 0) {
                                document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                                document.getElementById('sync-data').disabled = false;
                                alert('Aucune modification à synchroniser.');
                                return;
                            }
                            
                            // Envoyer les changements au serveur
                            const response = await fetch('{{ route("offline.upload") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ changes: pendingChanges })
                            });
                            
                            if (response.ok) {
                                // Effacer les changements synchronisés
                                const clearTransaction = db.transaction(['pendingChanges'], 'readwrite');
                                const clearStore = clearTransaction.objectStore('pendingChanges');
                                clearStore.clear();
                                
                                clearTransaction.oncomplete = function() {
                                    document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                                    document.getElementById('sync-data').disabled = false;
                                    alert('Données synchronisées avec succès.');
                                    
                                    // Mettre à jour les données locales
                                    document.getElementById('download-data').click();
                                };
                            } else {
                                document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                                document.getElementById('sync-data').disabled = false;
                                alert('Erreur lors de la synchronisation des données. Veuillez réessayer.');
                            }
                        };
                        
                        request.onerror = function(event) {
                            console.error('Erreur lors de la récupération des changements en attente:', event.target.error);
                            document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                            document.getElementById('sync-data').disabled = false;
                            alert('Erreur lors de la récupération des changements en attente. Veuillez réessayer.');
                        };
                    };
                    
                    dbPromise.onerror = function(event) {
                        console.error('Erreur lors de l\'ouverture de la base de données:', event.target.error);
                        document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                        document.getElementById('sync-data').disabled = false;
                        alert('Erreur lors de l\'accès à la base de données locale. Veuillez réessayer.');
                    };
                } catch (error) {
                    console.error('Erreur lors de la synchronisation des données:', error);
                    document.getElementById('sync-data').innerHTML = 'Synchroniser maintenant';
                    document.getElementById('sync-data').disabled = false;
                    alert('Erreur lors de la synchronisation des données. Veuillez réessayer.');
                }
            });
        });
    </script>
</x-app-layout>