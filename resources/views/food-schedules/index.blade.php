<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
                {{ __('Emploi du temps des nourritures') }}
            </h2>
            <div class="flex items-center space-x-4">
                @if(isset($healthStats) && $healthStats['hasWarning'])
                <div id="health-status">
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span id="health-message">
                            Attention: 
                            @if($healthStats['deaths'] > 0)
                                {{ $healthStats['deaths'] }} décès 
                            @endif
                            
                            @if($healthStats['illnesses'] > 0)
                                @if($healthStats['deaths'] > 0) et @endif
                                {{ $healthStats['illnesses'] }} maladies 
                            @endif
                            
                            la semaine dernière. Régime spécial feuilles recommandé.
                        </span>
                    </span>
                </div>
                @endif
                <form action="{{ route('food-schedules.generate') }}" method="POST" id="generate-form">
                    @csrf
                    <input type="hidden" name="prioritize_leaves" id="prioritize-leaves" value="{{ isset($healthStats) && $healthStats['hasWarning'] ? '1' : '0' }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Générer un nouvel emploi du temps
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 animate-fade-in-down" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Emploi du temps de la semaine</h3>
                        <div class="flex space-x-2">
                            <button id="today-btn" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition">Aujourd'hui</button>
                            <button id="week-btn" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300 transition">Semaine</button>
                        </div>
                    </div>
                    
                    @if($currentWeekSchedules->count() > 0)
                        <div class="space-y-6" id="schedules-container">
                            @foreach($currentWeekSchedules as $date => $schedules)
                                @php
                                    $dateObj = \Carbon\Carbon::parse($date);
                                    $isToday = $dateObj->isToday();
                                    $isPast = $dateObj->isPast() && !$isToday;
                                    $isFuture = $dateObj->isFuture();
                                @endphp
                                <div class="border rounded-lg overflow-hidden schedule-card {{ $isToday ? 'today' : '' }} {{ $isPast ? 'past' : '' }} {{ $isFuture ? 'future' : '' }}" 
                                     data-date="{{ $date }}" 
                                     style="{{ $isToday ? 'border-color: #3b82f6; box-shadow: 0 0 0 1px #3b82f6;' : '' }}">
                                    <div class="px-4 py-3 border-b transition-colors duration-300 {{ $isToday ? 'bg-blue-50' : 'bg-gray-50' }}">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-medium flex items-center">
                                                @if($isToday)
                                                    <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                                                @elseif($isPast)
                                                    <span class="inline-block w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                                                @else
                                                    <span class="inline-block w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                                                @endif
                                                {{ $dateObj->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                                                @if($isToday)
                                                    <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Aujourd'hui</span>
                                                @endif
                                            </h4>
                                            <div class="text-sm text-gray-500">
                                                {{ $schedules->count() }} nourriture(s)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200">
                                        @foreach($schedules as $schedule)
                                            <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        @if(Str::contains(strtolower($schedule->food->name), ['feuille', 'foin', 'herbe', 'légume']))
                                                            <span class="inline-block p-1 bg-green-100 text-green-500 rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M5.05 3.636a1 1 0 010 1.414 7 7 0 000 9.9 1 1 0 01-1.414 1.414 9 9 0 010-12.728 1 1 0 011.414 0zm9.9 0a1 1 0 011.414 0 9 9 0 010 12.728 1 1 0 11-1.414-1.414 7 7 0 000-9.9 1 1 0 010-1.414zM7.879 6.464a1 1 0 010 1.414 3 3 0 000 4.243 1 1 0 11-1.415 1.414 5 5 0 010-7.07 1 1 0 011.415 0zm4.242 0a1 1 0 011.415 0 5 5 0 010 7.072 1 1 0 01-1.415-1.415 3 3 0 000-4.242 1 1 0 010-1.415z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @elseif(Str::contains(strtolower($schedule->food->name), ['eau']))
                                                            <span class="inline-block p-1 bg-blue-100 text-blue-500 rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="inline-block p-1 bg-yellow-100 text-yellow-500 rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h5 class="font-medium">{{ $schedule->food->name }}</h5>
                                                        <p class="text-sm text-gray-600">Quantité: {{ $schedule->quantity }} {{ $schedule->unit }}</p>
                                                        @if($schedule->food->description)
                                                            <p class="text-xs text-gray-500 mt-1 hidden food-details">{{ Str::limit($schedule->food->description, 100) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    @if($schedule->is_completed)
                                                        <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Distribué le {{ $schedule->completed_at->format('d/m/Y à H:i') }}
                                                        </span>
                                                    @else
                                                        @if($isToday || $isPast)
                                                            <form method="POST" action="{{ route('food-schedules.complete', $schedule->id) }}" class="complete-form">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    Marquer comme distribué
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                                                À venir
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8" id="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun emploi du temps</h3>
                            <p class="mt-1 text-sm text-gray-500">Aucun emploi du temps n'a été généré pour cette semaine.</p>
                            <div class="mt-6">
                                <form action="{{ route('food-schedules.generate') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Générer un emploi du temps
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier l'état de santé des lapins
            checkRabbitHealth();
            
            // Filtrage des jours
            const todayBtn = document.getElementById('today-btn');
            const weekBtn = document.getElementById('week-btn');
            const scheduleCards = document.querySelectorAll('.schedule-card');
            
            // Par défaut, afficher uniquement aujourd'hui
            filterSchedules('today');
            
            todayBtn.addEventListener('click', function() {
                filterSchedules('today');
                todayBtn.classList.add('bg-blue-600', 'text-white');
                todayBtn.classList.remove('bg-gray-200', 'text-gray-700');
                weekBtn.classList.add('bg-gray-200', 'text-gray-700');
                weekBtn.classList.remove('bg-blue-600', 'text-white');
            });
            
            weekBtn.addEventListener('click', function() {
                filterSchedules('week');
                weekBtn.classList.add('bg-blue-600', 'text-white');
                weekBtn.classList.remove('bg-gray-200', 'text-gray-700');
                todayBtn.classList.add('bg-gray-200', 'text-gray-700');
                todayBtn.classList.remove('bg-blue-600', 'text-white');
            });
            
            function filterSchedules(filter) {
                scheduleCards.forEach(card => {
                    if (filter === 'today') {
                        card.style.display = card.classList.contains('today') ? 'block' : 'none';
                    } else {
                        card.style.display = 'block';
                    }
                });
            }
            
            // Animation pour les formulaires de complétion
            const completeForms = document.querySelectorAll('.complete-form');
            completeForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button');
                    button.disabled = true;
                    button.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Distribution...
                    `;
                });
            });
            
            // Afficher les détails de la nourriture au survol
            const foodItems = document.querySelectorAll('.schedule-card .divide-y > div');
            foodItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const details = this.querySelector('.food-details');
                    if (details) {
                        details.classList.remove('hidden');
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    const details = this.querySelector('.food-details');
                    if (details) {
                        details.classList.add('hidden');
                    }
                });
            });
            
            // Fonction pour vérifier l'état de santé des lapins
            function checkRabbitHealth() {
                // Simuler une vérification d'API - à remplacer par un vrai appel API
                fetch('/api/rabbit-health-check')
                    .then(response => response.json())
                    .catch(() => {
                        // Fallback pour la démo - à supprimer en production
                        return {
                            status: Math.random() > 0.5 ? 'warning' : 'normal',
                            deaths: Math.floor(Math.random() * 3),
                            illnesses: Math.floor(Math.random() * 5)
                        };
                    })
                    .then(data => {
                        const healthStatus = document.getElementById('health-status');
                        const healthMessage = document.getElementById('health-message');
                        const prioritizeLeavesInput = document.getElementById('prioritize-leaves');
                        
                        if (data.status === 'warning' || data.deaths > 1 || data.illnesses > 2) {
                            healthStatus.classList.remove('hidden');
                            prioritizeLeavesInput.value = "1";
                            
                            let message = 'Attention: ';
                            if (data.deaths > 0) {
                                message += `${data.deaths} décès `;
                            }
                            
                            if (data.illnesses > 0) {
                                message += data.deaths > 0 ? 'et ' : '';
                                message += `${data.illnesses} maladies `;
                            }
                            
                            message += 'cette semaine. Régime spécial feuilles recommandé.';
                            healthMessage.textContent = message;
                        } else {
                            healthStatus.classList.add('hidden');
                            prioritizeLeavesInput.value = "0";
                        }
                    });
            }
        });
    </script>
</x-app-layout>