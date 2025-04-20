<x-app-layout>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendrier des traitements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Calendrier des traitements</h3>
                        <a href="{{ route('treatments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nouveau traitement
                        </a>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-blue-500 text-lg font-bold">{{ $stats['pending'] }}</div>
                            <div class="text-sm text-gray-600">Traitements en attente</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-green-500 text-lg font-bold">{{ $stats['completed'] }}</div>
                            <div class="text-sm text-gray-600">Traitements complétés</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="text-yellow-500 text-lg font-bold">{{ $stats['today'] }}</div>
                            <div class="text-sm text-gray-600">Traitements aujourd'hui</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="text-red-500 text-lg font-bold">{{ $stats['overdue'] }}</div>
                            <div class="text-sm text-gray-600">Traitements en retard</div>
                        </div>
                    </div>

                    <!-- Légende du calendrier -->
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #FCD34D;"></div>
                            <span class="text-sm">En attente</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #6EE7B7;"></div>
                            <span class="text-sm">Complété</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #F87171;"></div>
                            <span class="text-sm">Annulé</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #E5E7EB;"></div>
                            <span class="text-sm">Ignoré</span>
                        </div>
                    </div>

                    <!-- Calendrier -->
                    <div id="calendar" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails du traitement -->
    <div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index:9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                <div class="mt-2 px-7 py-3">
                    <div class="text-sm text-gray-500 text-left">
                        <p><strong>Lapin:</strong> <span id="modal-rabbit"></span></p>
                        <p><strong>Médicament:</strong> <span id="modal-medication"></span></p>
                        <p><strong>Dosage:</strong> <span id="modal-dosage"></span></p>
                        <p><strong>Date prévue:</strong> <span id="modal-date"></span></p>
                        <p><strong>Statut:</strong> <span id="modal-status"></span></p>
                        <p><strong>Notes:</strong> <span id="modal-notes"></span></p>
                    </div>
                </div>
                <div class="items-center px-4 py-3" id="modal-actions">
                    <button id="modal-close" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <style>
        .fc-event {
            cursor: pointer;
        }
        .fc .fc-event.overdue {
            border-left: 4px solid #EF4444 !important;
        }
        .fc .fc-event.today {
            border-left: 4px solid #10B981 !important;
        }
        .fc .fc-event.completed-date {
            opacity: 0.8;
        }
    </style>
  
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const eventModal = document.getElementById('eventModal');
            const modalClose = document.getElementById('modal-close');
            
            // Initialiser le calendrier
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: {
                    url: '{{ route("treatments.calendar.events") }}',
                    failure: function() {
                        alert('Erreur lors du chargement des traitements');
                    }
                },
                eventClick: function(info) {
                    // Ne pas ouvrir le modal pour les dates de complétion
                    if (info.event.extendedProps.status === 'completed_date') {
                        return;
                    }
                    
                    // Remplir le modal avec les détails de l'événement
                    document.getElementById('modal-title').textContent = info.event.title;
                    document.getElementById('modal-rabbit').textContent = info.event.extendedProps.rabbit;
                    document.getElementById('modal-medication').textContent = info.event.extendedProps.medication;
                    document.getElementById('modal-dosage').textContent = info.event.extendedProps.dosage;
                    document.getElementById('modal-date').textContent = new Date(info.event.start).toLocaleDateString('fr-FR');
                    
                    // Traduire le statut
                    let status = '';
                    switch(info.event.extendedProps.status) {
                        case 'pending':
                            status = 'En attente';
                            break;
                        case 'completed':
                            status = 'Complété';
                            if (info.event.extendedProps.completed_at) {
                                status += ' le ' + new Date(info.event.extendedProps.completed_at).toLocaleDateString('fr-FR');
                            }
                            break;
                        case 'cancelled':
                            status = 'Annulé';
                            break;
                        case 'skipped':
                            status = 'Ignoré';
                            break;
                        default:
                            status = info.event.extendedProps.status;
                    }
                    document.getElementById('modal-status').textContent = status;
                    
                    document.getElementById('modal-notes').textContent = info.event.extendedProps.notes || 'Aucune note';
                    
                    // Ajouter les boutons d'action si le traitement est en attente
                    const actionsContainer = document.getElementById('modal-actions');
                    actionsContainer.innerHTML = '';
                    
                    // Bouton fermer
                    const closeButton = document.createElement('button');
                    closeButton.className = 'px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300';
                    closeButton.textContent = 'Fermer';
                    closeButton.onclick = function() {
                        eventModal.classList.add('hidden');
                    };
                    actionsContainer.appendChild(closeButton);
                    
                    // Ajouter les boutons d'action si le traitement est en attente
                    if (info.event.extendedProps.status === 'pending') {
                        // Bouton compléter
                        const completeButton = document.createElement('button');
                        completeButton.className = 'ml-3 px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300';
                        completeButton.textContent = 'Compléter';
                        completeButton.onclick = function() {
                            window.location.href = '{{ url("treatments") }}/' + info.event.extendedProps.id + '/done';
                        };
                        actionsContainer.appendChild(completeButton);
                        
                        // Bouton ignorer
                        const skipButton = document.createElement('button');
                        skipButton.className = 'ml-3 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300';
                        skipButton.textContent = 'Ignorer';
                        skipButton.onclick = function() {
                            window.location.href = '{{ url("treatments") }}/' + info.event.extendedProps.id + '/skip';
                        };
                        actionsContainer.appendChild(skipButton);
                    }
                    
                    // Afficher le modal
                    eventModal.classList.remove('hidden');
                },
                eventDidMount: function(info) {
                    // Ajouter des classes supplémentaires pour le style
                    if (info.event.extendedProps.status === 'pending') {
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        
                        const eventDate = new Date(info.event.start);
                        eventDate.setHours(0, 0, 0, 0);
                        
                        if (eventDate < today) {
                            info.el.classList.add('overdue');
                        } else if (eventDate.getTime() === today.getTime()) {
                            info.el.classList.add('today');
                        }
                    }
                }
            });
            
            calendar.render();
            
            // Fermer le modal quand on clique sur le bouton fermer
            modalClose.addEventListener('click', function() {
                eventModal.classList.add('hidden');
            });
            
            // Fermer le modal quand on clique en dehors
            window.addEventListener('click', function(event) {
                if (event.target === eventModal) {
                    eventModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>