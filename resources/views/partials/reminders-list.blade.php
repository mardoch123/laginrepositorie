<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Rappels à venir</h2>
            </div>
            <a href="{{ route('reminders.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau rappel
            </a>
        </div>

        @if($reminders->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <div class="bg-white p-3 rounded-full shadow-sm mb-4">
                    <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-lg font-medium">Aucun rappel actif pour le moment</p>
                <p class="text-gray-400 text-sm mt-2 max-w-sm text-center">Créez des rappels pour suivre vos tâches importantes liées à votre élevage</p>
                <a href="{{ route('reminders.create') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer un rappel
                </a>
            </div>
        @else
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-medium text-gray-500">Filtrer par priorité:</div>
                    <div class="flex space-x-2" id="priority-filters">
                        <button class="filter-btn active px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-priority="all">Tous</button>
                        <button class="filter-btn px-3 py-1 text-xs rounded-full bg-red-50 text-red-700 hover:bg-red-100 transition-colors" data-priority="urgent">Urgent</button>
                        <button class="filter-btn px-3 py-1 text-xs rounded-full bg-orange-50 text-orange-700 hover:bg-orange-100 transition-colors" data-priority="high">Haute</button>
                        <button class="filter-btn px-3 py-1 text-xs rounded-full bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition-colors" data-priority="medium">Moyenne</button>
                        <button class="filter-btn px-3 py-1 text-xs rounded-full bg-green-50 text-green-700 hover:bg-green-100 transition-colors" data-priority="low">Basse</button>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" id="reminder-search" placeholder="Rechercher un rappel..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="reminders-container">
                @foreach($reminders as $reminder)
                    <div class="reminder-card bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100 group hover:border-indigo-200" 
                         data-priority="{{ $reminder->priority }}" 
                         data-title="{{ $reminder->title }}" 
                         data-description="{{ $reminder->description }}">
                        <div class="p-5 relative">
                            <!-- Indicateur de priorité -->
                            <div class="absolute top-0 left-0 w-1 h-full 
                                @if($reminder->priority == 'low') bg-green-500 
                                @elseif($reminder->priority == 'medium') bg-yellow-500 
                                @elseif($reminder->priority == 'high') bg-orange-500 
                                @elseif($reminder->priority == 'urgent') bg-red-500 
                                @endif">
                            </div>
                            
                            <div class="ml-2">
                                <!-- En-tête avec priorité et date d'échéance -->
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($reminder->priority == 'low') bg-green-100 text-green-800 
                                        @elseif($reminder->priority == 'medium') bg-yellow-100 text-yellow-800 
                                        @elseif($reminder->priority == 'high') bg-orange-100 text-orange-800 
                                        @elseif($reminder->priority == 'urgent') bg-red-100 text-red-800 
                                        @endif">
                                        @if($reminder->priority == 'low') Basse
                                        @elseif($reminder->priority == 'medium') Moyenne
                                        @elseif($reminder->priority == 'high') Haute
                                        @elseif($reminder->priority == 'urgent') Urgente
                                        @endif
                                    </span>
                                    
                                    <div class="flex items-center text-sm">
                                        <div class="bg-gray-100 p-1 rounded mr-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium {{ $reminder->due_date && $reminder->due_date->isPast() ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $reminder->due_date ? $reminder->due_date->format('d/m/Y') : 'Date non définie' }}
                                            @if($reminder->due_date && $reminder->due_date->isPast())
                                                <span class="text-xs ml-1">(en retard)</span>
                                            @elseif($reminder->due_date && $reminder->due_date->isToday())
                                                <span class="text-xs ml-1">(aujourd'hui)</span>
                                            @elseif($reminder->due_date && $reminder->due_date->isTomorrow())
                                                <span class="text-xs ml-1">(demain)</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Titre et heure -->
                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-indigo-700 transition-colors duration-200">{{ Str::limit($reminder->title, 40) }}</h3>
                                    
                                    @if($reminder->time)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <div class="bg-gray-100 p-1 rounded mr-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span>{{ $reminder->time->format('H:i') }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Description avec animation d'expansion -->
                                @if($reminder->description)
                                <div class="mb-4 relative">
                                    <div class="text-sm text-gray-500 bg-gray-50 p-3 rounded-lg description-container overflow-hidden" style="max-height: 80px; transition: max-height 0.3s ease-in-out;">
                                        {{ $reminder->description }}
                                    </div>
                                    <button class="expand-btn absolute bottom-0 right-0 bg-gradient-to-l from-gray-50 via-gray-50 text-xs text-indigo-600 px-2 py-1 hover:text-indigo-800 transition-colors">Voir plus</button>
                                </div>
                                @endif
                                
                                <!-- Tags -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($reminder->rabbit)
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                            {{ $reminder->rabbit->name }}
                                        </div>
                                    @endif
                                    @if($reminder->litter)
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Portée #{{ $reminder->litter->id }}
                                        </div>
                                    @endif
                                    @if($reminder->frequency)
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-teal-50 text-teal-700 border border-teal-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            @if($reminder->frequency == 'daily') Quotidien
                                            @elseif($reminder->frequency == 'weekly') Hebdomadaire
                                            @elseif($reminder->frequency == 'custom') Tous les {{ $reminder->interval_days }} jours
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pied de carte avec actions -->
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                @if($reminder->last_executed)
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Dernière: {{ $reminder->last_executed->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('reminders.show', $reminder) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors duration-200" title="Voir les détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('reminders.edit', $reminder) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('reminders.toggle', $reminder) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $reminder->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-teal-50 text-teal-600 hover:bg-teal-100' }} transition-colors duration-200" title="{{ $reminder->is_active ? 'Désactiver' : 'Activer' }}">
                                        @if($reminder->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('reminders.update', $reminder) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_completed" value="1">
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200" title="Marquer comme terminé">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('reminders.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Voir tous les rappels
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des filtres de priorité
    const filterButtons = document.querySelectorAll('.filter-btn');
    const reminderCards = document.querySelectorAll('.reminder-card');
    const searchInput = document.getElementById('reminder-search');
    
    // Fonction pour filtrer les rappels
    function filterReminders() {
        const activeFilter = document.querySelector('.filter-btn.active').dataset.priority;
        const searchTerm = searchInput.value.toLowerCase();
        
        reminderCards.forEach(card => {
            const cardPriority = card.dataset.priority;
            const cardTitle = card.dataset.title.toLowerCase();
            const cardDescription = card.dataset.description ? card.dataset.description.toLowerCase() : '';
            
            const matchesPriority = activeFilter === 'all' || cardPriority === activeFilter;
            const matchesSearch = cardTitle.includes(searchTerm) || cardDescription.includes(searchTerm);
            
            if (matchesPriority && matchesSearch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Afficher un message si aucun résultat
        const container = document.getElementById('reminders-container');
        const noResults = document.getElementById('no-results-message');
        
        if (![...reminderCards].some(card => card.style.display === 'block')) {
            if (!noResults) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'col-span-full py-8 text-center';
                message.innerHTML = `
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Aucun rappel ne correspond à votre recherche</p>
                `;
                container.appendChild(message);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    // Gestionnaire d'événements pour les boutons de filtre
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterReminders();
        });
    });
    
    // Gestionnaire d'événements pour la recherche
    searchInput.addEventListener('input', filterReminders);
    
    // Gestion de l'expansion des descriptions
    document.querySelectorAll('.expand-btn').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.previousElementSibling;
            if (container.style.maxHeight === '80px') {
                container.style.maxHeight = '1000px';
                this.textContent = 'Voir moins';
            } else {
                container.style.maxHeight = '80px';
                this.textContent = 'Voir plus';
            }
        });
    });
    
    // Animation d'entrée pour les cartes
    reminderCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 50 * index);
    });
    
    // Test FCM (conservé de l'original)
    const testButton = document.getElementById('test-fcm');
    if (testButton) {
        testButton.addEventListener('click', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/send-fcm-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notification envoyée avec succès!');
                    console.log('Résultat FCM:', data.result);
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'envoi de la notification');
            });
        });
    }
});
</script>