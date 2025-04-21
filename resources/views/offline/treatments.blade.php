<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Traitements (Mode Hors Ligne)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des traitements (hors ligne)</h3>
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
                            <button id="all-treatments" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Tous
                            </button>
                            <button id="pending-treatments" class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                En attente
                            </button>
                            <button id="completed-treatments" class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Terminés
                            </button>
                        </div>
                    </div>
                    
                    <div id="treatments-container" class="mt-4">
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
                document.getElementById('treatments-container').innerHTML = `
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
            let allTreatments = [];
            
            // Ouvrir la base de données
            const dbPromise = indexedDB.open('gestionElevageOffline', 1);
            
            dbPromise.onerror = function(event) {
                console.error("Erreur d'accès à la base de données:", event.target.error);
                document.getElementById('treatments-container').innerHTML = `
                    <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-700">
                            Impossible d'accéder à la base de données locale. Le mode hors ligne peut ne pas fonctionner correctement.
                        </p>
                    </div>
                `;
            };
            
            dbPromise.onsuccess = function(event) {
                const db = event.target.result;
                
                if (!db.objectStoreNames.contains('treatments')) {
                    document.getElementById('treatments-container').innerHTML = `
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm text-yellow-700">
                                Aucune donnée de traitements disponible hors ligne. Veuillez d'abord synchroniser vos données.
                            </p>
                        </div>
                    `;
                    return;
                }
                
                loadTreatments(db);
                
                // Gestionnaires d'événements pour les boutons de filtrage
                document.getElementById('all-treatments').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'all';
                    displayTreatments(allTreatments);
                });
                
                document.getElementById('pending-treatments').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'pending';
                    const pendingTreatments = allTreatments.filter(t => t.status === 'pending' || !t.status);
                    displayTreatments(pendingTreatments);
                });
                
                document.getElementById('completed-treatments').addEventListener('click', function() {
                    setActiveFilter(this);
                    currentFilter = 'completed';
                    const completedTreatments = allTreatments.filter(t => t.status === 'completed');
                    displayTreatments(completedTreatments);
                });
            };
            
            function setActiveFilter(button) {
                // Réinitialiser tous les boutons
                document.querySelectorAll('#all-treatments, #pending-treatments, #completed-treatments').forEach(btn => {
                    btn.className = "px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition";
                });
                
                // Activer le bouton sélectionné
                button.className = "px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition";
            }
            
            function loadTreatments(db) {
                const transaction = db.transaction(['treatments', 'rabbits'], 'readonly');
                const treatmentsStore = transaction.objectStore('treatments');
                const rabbitsStore = transaction.objectStore('rabbits');
                
                const request = treatmentsStore.getAll();
                
                request.onsuccess = function() {
                    allTreatments = request.result;
                    
                    // Récupérer les informations des lapins pour chaque traitement
                    let pendingRabbitLookups = allTreatments.length;
                    
                    if (pendingRabbitLookups === 0) {
                        document.getElementById('treatments-container').innerHTML = `
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                <p class="text-sm text-yellow-700">
                                    Aucun traitement trouvé dans la base de données locale.
                                </p>
                            </div>
                        `;
                        return;
                    }
                    
                    allTreatments.forEach((treatment, index) => {
                        const rabbitRequest = rabbitsStore.get(treatment.rabbit_id);
                        
                        rabbitRequest.onsuccess = function() {
                            const rabbit = rabbitRequest.result;
                            allTreatments[index].rabbitName = rabbit ? rabbit.name : 'Inconnu';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayTreatments(allTreatments);
                            }
                        };
                        
                        rabbitRequest.onerror = function() {
                            allTreatments[index].rabbitName = 'Inconnu';
                            
                            pendingRabbitLookups--;
                            if (pendingRabbitLookups === 0) {
                                displayTreatments(allTreatments);
                            }
                        };
                    });
                };
                
                request.onerror = function(event) {
                    console.error("Erreur lors de la récupération des traitements:", event.target.error);
                    document.getElementById('treatments-container').innerHTML = `
                        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <p class="text-sm text-red-700">
                                Erreur lors de la récupération des données. Veuillez réessayer.
                            </p>
                        </div>
                    `;
                };
            }
            
            function displayTreatments(treatments) {
                if (treatments.length === 0) {
                    document.getElementById('treatments-container').innerHTML = `
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm text-yellow-700">
                                Aucun traitement ${currentFilter === 'pending' ? 'en attente' : (currentFilter === 'completed' ? 'terminé' : '')} trouvé.
                            </p>
                        </div>
                    `;
                    return;
                }
                
                // Trier les traitements par date
                treatments.sort((a, b) => new Date(a.scheduled_at) - new Date(b.scheduled_at));
                
                let html = `
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                `;
                
                treatments.forEach(treatment => {
                    const scheduledDate = new Date(treatment.scheduled_at);
                    const isOverdue = scheduledDate < new Date() && (treatment.status !== 'completed');
                    const statusClass = treatment.status === 'completed' ? 'bg-green-100 border-green-500' : 
                                       (isOverdue ? 'bg-red-100 border-red-500' : 'bg-blue-100 border-blue-500');
                    const statusText = treatment.status === 'completed' ? 'Terminé' : 
                                      (isOverdue ? 'En retard' : 'À faire');
                    
                    html += `
                        <div class="rounded-lg shadow-md overflow-hidden ${statusClass} border">
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold">${treatment.medication_name || 'Traitement'}</h3>
                                        <p class="text-sm text-gray-600">Lapin: ${treatment.rabbitName}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full ${
                                        treatment.status === 'completed' ? 'bg-green-200 text-green-800' : 
                                        (isOverdue ? 'bg-red-200 text-red-800' : 'bg-blue-200 text-blue-800')
                                    }">
                                        ${statusText}
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-sm"><span class="font-medium">Date prévue:</span> ${formatDate(treatment.scheduled_at)}</p>
                                    <p class="text-sm mt-1"><span class="font-medium">Dosage:</span> ${treatment.dosage || 'Non spécifié'}</p>
                                    <p class="text-sm mt-1"><span class="font-medium">Notes:</span> ${treatment.notes || 'Aucune note'}</p>
                                </div>
                                
                                <div class="mt-4 flex justify-end">
                                    <button onclick="viewTreatmentDetails(${treatment.id})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Voir détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
                
                document.getElementById('treatments-container').innerHTML = html;
            }
            
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('fr-FR', options);
            }
            
            // Exposer la fonction pour l'utiliser dans les boutons
            window.viewTreatmentDetails = function(treatmentId) {
                const dbPromise = indexedDB.open('gestionElevageOffline', 1);
                
                dbPromise.onsuccess = function(event) {
                    const db = event.target.result;
                    const transaction = db.transaction(['treatments', 'rabbits'], 'readonly');
                    const treatmentsStore = transaction.objectStore('treatments');
                    const rabbitsStore = transaction.objectStore('rabbits');
                    
                    const request = treatmentsStore.get(treatmentId);
                    
                    request.onsuccess = function() {
                        const treatment = request.result;
                        
                        if (!treatment) {
                            alert('Traitement non trouvé dans la base de données locale.');
                            return;
                        }
                        
                        // Récupérer les informations du lapin
                        const rabbitRequest = rabbitsStore.get(treatment.rabbit_id);
                        
                        rabbitRequest.onsuccess = function() {
                            const rabbit = rabbitRequest.result;
                            const rabbitName = rabbit ? rabbit.name : 'Inconnu';
                            
                            // Créer une modal pour afficher les détails
                            const modal = document.createElement('div');
                            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50';
                            
                            const scheduledDate = new Date(treatment.scheduled_at);
                            const isOverdue = scheduledDate < new Date() && (treatment.status !== 'completed');
                            const statusClass = treatment.status === 'completed' ? 'bg-green-100 text-green-800' : 
                                              (isOverdue ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
                            const statusText = treatment.status === 'completed' ? 'Terminé' : 
                                              (isOverdue ? 'En retard' : 'À faire');
                            
                            modal.innerHTML = `
                                <div class="relative bg-white rounded-lg shadow-xl mx-auto p-5 w-full max-w-2xl">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-medium text-gray-900">Détails du traitement</h3>
                                        <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <span class="px-3 py-1 text-sm rounded-full ${statusClass}">
                                            ${statusText}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Médicament</h4>
                                            <p class="text-base text-gray-900">${treatment.medication_name || 'Non spécifié'}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Lapin</h4>
                                            <p class="text-base text-gray-900">${rabbitName} (ID: ${treatment.rabbit_id})</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Date prévue</h4>
                                            <p class="text-base text-gray-900">${formatDate(treatment.scheduled_at)}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Dosage</h4>
                                            <p class="text-base text-gray-900">${treatment.dosage || 'Non spécifié'}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                                            <p class="text-base text-gray-900">${treatment.notes || 'Aucune note'}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Date de création</h4>
                                            <p class="text-base text-gray-900">${treatment.created_at ? formatDate(treatment.created_at) : 'Non disponible'}</p>
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
        });
    </script>
</x-app-layout>