<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rappels') }}
            </h2>
            <a href="{{ route('reminders.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau rappel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($reminders->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rappel</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau rappel.</p>
                            <div class="mt-6">
                                <a href="{{ route('reminders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Nouveau rappel
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fréquence</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reminders as $reminder)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $reminder->title }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @if($reminder->rabbit)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $reminder->rabbit->name }}
                                                        </span>
                                                    @endif
                                                    @if($reminder->litter)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            Portée #{{ $reminder->litter->id }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $reminder->due_date ? $reminder->due_date->format('d/m/Y') : 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $reminder->time ? $reminder->time->format('H:i') : '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if(!$reminder->frequency)
                                                    Unique
                                                @elseif($reminder->frequency == 'daily')
                                                    Quotidien
                                                @elseif($reminder->frequency == 'weekly')
                                                    Hebdomadaire
                                                    <div class="text-xs text-gray-400">
                                                        @if($reminder->days_of_week)
                                                            @php
                                                                $days = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
                                                                $selectedDays = collect($reminder->days_of_week)->map(function($day) use ($days) {
                                                                    return $days[$day];
                                                                })->join(', ');
                                                            @endphp
                                                            {{ $selectedDays }}
                                                        @endif
                                                    </div>
                                                @elseif($reminder->frequency == 'custom')
                                                    Tous les {{ $reminder->interval_days }} jours
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($reminder->is_completed)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Terminé
                                                        </span>
                                                    @else
                                                        <button type="button" class="toggle-active-btn" data-id="{{ $reminder->id }}" data-active="{{ $reminder->active ? 'true' : 'false' }}">
                                                            <span class="relative inline-block flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $reminder->active ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                                <span class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $reminder->active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                            </span>
                                                            <span class="ml-2 text-sm text-gray-500">{{ $reminder->active ? 'Actif' : 'Inactif' }}</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('reminders.show', $reminder) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('reminders.edit', $reminder) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des boutons de suppression
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce rappel ?')) {
                        this.submit();
                    }
                });
            });

            // Gestion des boutons d'activation/désactivation
            const toggleButtons = document.querySelectorAll('.toggle-active-btn');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
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
                            // Mettre à jour l'état du bouton
                            const toggleSpan = this.querySelector('span.inline-block');
                            const bgSpan = this.querySelector('span.relative');
                            const textSpan = this.querySelector('span.text-sm');
                            
                            if (data.active) {
                                bgSpan.classList.remove('bg-gray-200');
                                bgSpan.classList.add('bg-indigo-600');
                                toggleSpan.classList.remove('translate-x-0');
                                toggleSpan.classList.add('translate-x-5');
                                textSpan.textContent = 'Actif';
                            } else {
                                bgSpan.classList.remove('bg-indigo-600');
                                bgSpan.classList.add('bg-gray-200');
                                toggleSpan.classList.remove('translate-x-5');
                                toggleSpan.classList.add('translate-x-0');
                                textSpan.textContent = 'Inactif';
                            }
                            
                            this.dataset.active = data.active.toString();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
                });
            });
        });
    </script>
</x-app-layout>