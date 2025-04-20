<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du rappel') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('reminders.edit', $reminder) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="inline-block delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du rappel</h3>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Titre</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $reminder->title }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Description</h4>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $reminder->description ?: 'Aucune description' }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Date d'échéance</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $reminder->due_date ? $reminder->due_date->format('d/m/Y') : 'Non définie' }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Priorité</h4>
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
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Statut</h4>
                                    <div class="mt-1">
                                        @if($reminder->is_completed)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Terminé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reminder->active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $reminder->active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($reminder->rabbit)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">{{ session('animal_type_singular', 'Lapin') }} concerné</h4>
                                    <div class="mt-1">
                                        <a href="{{ route('rabbits.show', $reminder->rabbit) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $reminder->rabbit->name }} ({{ $reminder->rabbit->tattoo }})
                                        </a>
                                    </div>
                                </div>
                                @endif
                                
                                @if($reminder->litter)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Portée concernée</h4>
                                    <div class="mt-1">
                                        @if($reminder->litter->breeding)
                                            <a href="{{ route('breedings.show', $reminder->litter->breeding->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Portée #{{ $reminder->litter->id }} ({{ $reminder->litter->breeding->mother ? $reminder->litter->breeding->mother->name : 'Mère inconnue' }} - {{ $reminder->litter->birth_date ? $reminder->litter->birth_date->format('d/m/Y') : 'Date inconnue' }})
                                            </a>
                                        @else
                                            <span class="text-gray-500">
                                                Portée #{{ $reminder->litter->id }} (Données d'accouplement non disponibles)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres de récurrence</h3>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Fréquence</h4>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if(!$reminder->frequency)
                                            Unique
                                        @elseif($reminder->frequency == 'daily')
                                            Quotidien
                                        @elseif($reminder->frequency == 'weekly')
                                            Hebdomadaire
                                        @elseif($reminder->frequency == 'custom')
                                            Tous les {{ $reminder->interval_days }} jours
                                        @endif
                                    </p>
                                </div>
                                
                                @if($reminder->frequency == 'weekly' && $reminder->days_of_week)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Jours de la semaine</h4>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @php
                                            $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                                        @endphp
                                        @foreach($reminder->days_of_week as $day)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $days[$day] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($reminder->time)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Heure</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $reminder->time->format('H:i') }}</p>
                                </div>
                                @endif
                                
                                @if($reminder->last_executed)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Dernière exécution</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $reminder->last_executed->format('d/m/Y H:i') }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Actions</h3>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="flex flex-col space-y-3">
                                    @if(!$reminder->is_completed)
                                    <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_completed" value="1">
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Marquer comme terminé
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_completed" value="0">
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Réactiver le rappel
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <button type="button" id="toggle-active-btn" data-id="{{ $reminder->id }}" data-active="{{ $reminder->active ? 'true' : 'false' }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        {{ $reminder->active ? 'Désactiver' : 'Activer' }} le rappel
                                    </button>
                                    
                                    <a href="{{ route('reminders.logs', $reminder) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Voir l'historique
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Historique récent</h3>
                        @if($logs->isEmpty())
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm text-center">
                                <p class="text-sm text-gray-500">Aucun historique disponible pour ce rappel.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($logs as $log)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $log->executed_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $log->success ? 'Succès' : 'Échec' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $log->notes ?: 'Aucune note' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 text-right">
                                <a href="{{ route('reminders.logs', $reminder) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                                    Voir tout l'historique
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du bouton de suppression
            const deleteForm = document.querySelector('.delete-form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce rappel ?')) {
                        this.submit();
                    }
                });
            }

            // Gestion du bouton d'activation/désactivation
            const toggleButton = document.getElementById('toggle-active-btn');
            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const reminderId = this.dataset.id;
                    const isActive = this.dataset.active === 'true';
                    
                    fetch(`/reminders/${reminderId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            active: !isActive
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recharger la page pour refléter les changements
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
                });
            }
        });
    </script>
</x-app-layout>