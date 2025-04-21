<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reproductions (Mode Hors Ligne)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des reproductions (hors ligne)</h3>
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
                    
                    <div class="mb-6">
                        <div class="flex space-x-2">
                            <button id="all-breedings" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Toutes
                            </button>
                            <button id="active-breedings" class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                En cours
                            </button>
                            <button id="completed-breedings" class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Terminées
                            </button>
                        </div>
                    </div>
                    
                    <div id="breedings-container" class="mt-4">
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
                document.getElementById('breedings-container').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Votre navigateur ne supporte pas IndexedDB, nécessaire pour le mode hors ligne.
                        </p>
                    </div>
                `;
                return;
            }
            
            // Variables pour le filtrage
            let currentFilter = 'all';
            let allBreedings = [];
            
            // Ouvrir la base de données
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onerror = function(event) {
                console.error("Erreur d'accès à la base de données:", event.target.error);
                document.getElementById('breedings-container').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Impossible d'accéder à la base de données locale. Le mode hors ligne peut ne pas fonctionner correctement.
                        </p>
                    </div>
                `;
            };
            
            dbPromise.onsuccess = function(event) {
                const db = event.target.result;
                
                if (!db.objectStoreNames.contains('breedings')) {
                    document.getElementById('breedings-container').innerHTML = `
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm text-yellow-700">
                                Aucune donnée de reproductions disponible hors ligne. Veuillez d'abord synchroniser vos données.
                            </p>
                        </div>
                    `;
                    return;
                }
                
                loadBreedings(db);
                
                // Gestionnaires d'événements pour les boutons de filtrage
                document.getElementById('all-breedings').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'all';
                    displayBreedings(allBreedings);
                });
                
                document.getElementById('active-breedings').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'active';
                    const activeBreedings = allBreedings.filter(b => !b.birth_date);
                    displayBreedings(activeBreedings);
                });
                
                document.getElementById('completed-breedings').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'completed';
                    const completedBreedings = allBreedings.filter(b => b.birth_date);
                    displayBreedings(completedBreedings);
                });
            };
            
            function setActiveFilter(button) {
                // Réinitialiser tous les boutons
                document.querySelectorAll('#all-breedings, #active-breedings, #completed-breedings').forEach(btn => {
                    btn.className = "px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition";
                });
                
                // Activer le bouton sélectionné
                button.className = "px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition";
            }
            
            function loadBreedings(db) {
                const transaction = db.transaction(['breedings', 'rabbits'], 'readonly');
                const breedingsStore = transaction.objectStore('breedings');
                const rabbitsStore = transaction.objectStore('rabbits');
                
                const request = breedingsStore.getAll();
                
                request.onsuccess = function() {
                    allBreedings = request.result;
                    
                    // Récupérer les informations des lapins pour chaque reproduction
                    let pendingRabbitLookups = allBreedings.length * 2; // Mère et père
                    
                    if (pendingRabbitLookups === 0) {
                        document.getElementById('breedings-container').innerHTML = `
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                <p class="text-sm text-yellow-700">
                                    Aucune reproduction trouvée dans la base de données locale.
                                </p>
                            </div>
                        `;
                        return;
                    }
                    
                    allBreedings.forEach((breeding, index) => {
                        // Récupérer les informations de la mère
                        const motherRequest = rabbitsStore.get(breeding.mother_id);
                        
                        motherRequest.onsuccess = function() {
                            const mother = motherRequest.result;
                            allBreedings[index].motherName = mother ? mother.name : 'Inconnue';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayBreedings(allBreedings);
                            }
                        };
                        
                        motherRequest.onerror = function() {
                            allBreedings[index].motherName = 'Inconnue';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayBreedings(allBreedings);
                            }
                        };
                        
                        // Récupérer les informations du père
                        const fatherRequest = rabbitsStore.get(breeding.father_id);
                        
                        fatherRequest.onsuccess = function() {
                            const father = fatherRequest.result;
                            allBreedings[index].fatherName = father ? father.name : 'Inconnu';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayBreedings(allBreedings);
                            }
                        };
                        
                        fatherRequest.onerror = function() {
                            allBreedings[index].fatherName = 'Inconnu';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayBreedings(allBreedings);
                            }
                        };
                    });
                };
                
                request.onerror = function(event) {
                    console.error("Erreur lors de la récupération des reproductions:", event.target.error);
                    document.getElementById('breedings-container').innerHTML = `
                        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <p class="text-sm text-red-700">
                                Erreur lors de la récupération des données. Veuillez réessayer.
                            </p>
                        </div>
                    `;
                };
            }
            
            function displayBreedings(breedings) {
                if (breedings.length === 0) {
                    document.getElementById('breedings-container').innerHTML = `
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm text-yellow-700">
                                Aucune reproduction ${currentFilter === 'active' ? 'en cours' : (currentFilter === 'completed' ? 'terminée' : '')} trouvée.
                            </p>
                        </div>
                    `;
                    return;
                }
                
                // Trier les reproductions par date d'accouplement (les plus récentes d'abord)
                breedings.sort((a, b) => new Date(b.mating_date) - new Date(a.mating_date));
                
                let html = `
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                `;
                
                breedings.forEach(breeding => {
                    const matingDate = new Date(breeding.mating_date);
                    const expectedBirthDate = new Date(breeding.expected_birth_date);
                    const today = new Date();
                    
                    // Déterminer le statut
                    let status, statusClass;
                    if (breeding.birth_date) {
                        status = 'Terminée';
                        statusClass = 'bg-green-100 border-green-500';
                    } else if (expectedBirthDate < today) {
                        status = 'En retard';
                        statusClass = 'bg-red-100 border-red-500';
                    } else {
                        status = 'En cours';
                        statusClass = 'bg-blue-100 border-blue-500';
                    }
                    
                    html += `
                        <div class="rounded-lg shadow-md overflow-hidden ${statusClass} border">
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold">Reproduction #${breeding.id}</h3>
                                        <p class="text-sm text-gray-600">Mère: ${breeding.motherName}</p>
                                        <p class="text-sm text-gray-600">Père: ${breeding.fatherName}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full ${
                                        breeding.birth_date ? 'bg-green-200 text-green-800' : 
                                        (expectedBirthDate < today ? 'bg-red-200 text-red-800' : 'bg-blue-200 text-blue-800')
                                    }">
                                        ${status}
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-sm"><span class="font-medium">Date d'accouplement:</span> ${formatDate(breeding.mating_date)}</p>
                                    <p class="text-sm mt-1"><span class="font-medium">Naissance prévue:</span> ${formatDate(breeding.expected_birth_date)}</p>
                                    ${breeding.birth_date ? 
                                        `<p class="text-sm mt-1"><span class="font-medium">Date de naissance:</span> ${formatDate(breeding.birth_date)}</p>` : 
                                        ''}
                                    ${breeding.litter_size ? 
                                        `<p class="text-sm mt-1"><span class="font-medium">Taille de la portée:</span> ${breeding.litter_size} lapereaux</p>` : 
                                        ''}
                                </div>
                                
                                <div class="mt-4 flex justify-end">
                                    <button onclick="viewBreedingDetails(${breeding.id})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Voir détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
                
                document.getElementById('breedings-container').innerHTML = html;
            }
            
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('fr-FR', options);
            }
            
            // Exposer la fonction pour l'utiliser dans les boutons
            window.viewBreedingDetails = function(breedingId) {
                const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                
                dbPromise.onsuccess = function(event) {
                    const db = event.target.result;
                    const transaction = db.transaction(['breedings', 'rabbits'], 'readonly');
                    const breedingsStore = transaction.objectStore('breedings');
                    const rabbitsStore = transaction.objectStore('rabbits');
                    
                    const request = breedingsStore.get(breedingId);
                    
                    request.onsuccess = function() {
                        const breeding = request.result;
                        
                        if (!breeding) {
                            alert('Reproduction non trouvée dans la base de données locale.');
                            return;
                        }
                        
                        // Récupérer les informations des lapins
                        const motherRequest = rabbitsStore.get(breeding.mother_id);
                        
                        motherRequest.onsuccess = function() {
                            const mother = motherRequest.result;
                            const motherName = mother ? mother.name : 'Inconnue';
                            
                            const fatherRequest = rabbitsStore.get(breeding.father_id);
                            
                            fatherRequest.onsuccess = function() {
                                const father = fatherRequest.result;
                                const fatherName = father ? father.name : 'Inconnu';
                                
                                // Créer une modal pour afficher les détails
                                const modal = document.createElement('div');
                                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50';
                                
                                const matingDate = new Date(breeding.mating_date);
                                const expectedBirthDate = new Date(breeding.expected_birth_date);
                                const today = new Date();
                                
                                // Déterminer le statut
                                let status, statusClass;
                                if (breeding.birth_date) {
                                    status = 'Terminée';
                                    statusClass = 'bg-green-100 text-green-800';
                                } else if (expectedBirthDate < today) {
                                    status = 'En retard';
                                    statusClass = 'bg-red-100 text-red-800';
                                } else {
                                    status = 'En cours';
                                    statusClass = 'bg-blue-100 text-blue-800';
                                }
                                
                                modal.innerHTML = `
                                    <div class="relative bg-white rounded-lg shadow-xl mx-auto p-5 w-full max-w-2xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-xl font-medium text-gray-900">Détails de la reproduction</h3>
                                            <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <span class="px-3 py-1 text-sm rounded-full ${statusClass}">
                                                ${status}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-500">Mère</h4>
                                                    <p class="text-base text-gray-900">${motherName} (ID: ${breeding.mother_id})</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-500">Père</h4>
                                                    <p class="text-base text-gray-900">${fatherName} (ID: ${breeding.father_id})</p>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">Date d'accouplement</h4>
                                                <p class="text-base text-gray-900">${formatDate(breeding.mating_date)}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">Date de naissance prévue</h4>
                                                <p class="text-base text-gray-900">${formatDate(breeding.expected_birth_date)}</p>
                                            </div>
                                            ${breeding.birth_date ? `
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">Date de naissance effective</h4>
                                                <p class="text-base text-gray-900">${formatDate(breeding.birth_date)}</p>
                                            </div>
                                            ` : ''}
                                            ${breeding.litter_size ? `
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">Taille de la portée</h4>
                                                <p class="text-base text-gray-900">${breeding.litter_size} lapereaux</p>
                                            </div>
                                            ` : ''}
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                                                <p class="text-base text-gray-900">${breeding.notes || 'Aucune note'}</p>
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
                        };
                    };
                };
            };
        });
    </script>
</x-app-layout>