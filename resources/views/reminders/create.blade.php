<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un rappel') }}
        </h2>
    </x-slot>

    <!-- Add Alpine.js and Choices.js for enhanced select inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .choices__inner {
            background-color: white;
            border-color: #d1d5db;
            border-radius: 0.375rem;
        }
        .choices__input {
            background-color: white;
        }
        .choices__list--dropdown .choices__item--selectable {
            padding-right: 10px;
        }
        .flatpickr-day.selected {
            background: #4f46e5;
            border-color: #4f46e5;
        }
        .priority-low {
            background-color: #dcfce7;
            color: #166534;
        }
        .priority-medium {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .priority-urgent {
            background-color: #fecaca;
            color: #991b1b;
            font-weight: bold;
        }
    </style>

    <div class="py-12" x-data="reminderForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('reminders.store') }}" method="POST" id="reminderForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                            <div class="relative">
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pl-10"
                                    placeholder="Entrez le titre du rappel...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <div class="relative">
                                <textarea name="description" id="description" rows="3" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pl-10"
                                    placeholder="Décrivez ce rappel...">{{ old('description') }}</textarea>
                                <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                            </div>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
                                <div class="relative">
                                    <input type="text" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pl-10"
                                        placeholder="Sélectionnez une date...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('due_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Heure</label>
                                <div class="relative">
                                    <input type="text" name="time" id="time" value="{{ old('time') }}" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pl-10"
                                        placeholder="Sélectionnez une heure...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priority" id="priority" class="priority-select">
                                <option value="low" data-class="priority-low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" data-class="priority-medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" data-class="priority-high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                                <option value="urgent" data-class="priority-urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priority')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="rabbit_id" class="block text-sm font-medium text-gray-700 mb-1">{{ session('animal_type_singular', 'Lapin') }} concerné (optionnel)</label>
                                <select name="rabbit_id" id="rabbit_id" class="searchable-select">
                                    <option value="">Aucun</option>
                                    @foreach($rabbits as $rabbit)
                                        <option value="{{ $rabbit->id }}" {{ old('rabbit_id') == $rabbit->id ? 'selected' : '' }}>
                                            {{ $rabbit->name }} ({{ $rabbit->tattoo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rabbit_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="litter_id" class="block text-sm font-medium text-gray-700 mb-1">Portée concernée (optionnel)</label>
                                <select name="litter_id" id="litter_id" class="searchable-select">
                                    <option value="">Aucune</option>
                                    @foreach($litters as $litter)
                                        <option value="{{ $litter->id }}" {{ old('litter_id') == $litter->id ? 'selected' : '' }}>
                                            Portée #{{ $litter->id }} ({{ $litter->breeding && $litter->breeding->mother ? $litter->breeding->mother->name : 'Mère inconnue' }} - {{ $litter->birth_date ?? 'Date inconnue' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('litter_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-1">Fréquence</label>
                            <select name="frequency" id="frequency" x-on:change="toggleFrequencyOptions()" class="basic-select">
                                <option value="">Aucune (date unique)</option>
                                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                <option value="custom" {{ old('frequency') == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                            </select>
                            @error('frequency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6 weekly-options" x-show="frequency === 'weekly'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jours de la semaine</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $index => $day)
                                    <label class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white shadow-sm hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="days_of_week[]" id="day-{{ $index }}" value="{{ $index }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mr-2" {{ in_array((string)$index, old('days_of_week', [])) ? 'checked' : '' }}>
                                        {{ $day }}
                                    </label>
                                @endforeach
                            </div>
                            @error('days_of_week')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6 custom-options" x-show="frequency === 'custom'" x-transition>
                            <label for="interval_days" class="block text-sm font-medium text-gray-700 mb-1">Intervalle (jours)</label>
                            <div class="relative">
                                <input type="number" name="interval_days" id="interval_days" value="{{ old('interval_days') }}" min="1" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md pl-10"
                                    placeholder="Nombre de jours entre chaque rappel">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('interval_days')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="active" id="active" value="1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" {{ old('active', true) ? 'checked' : '' }}>
                                    <label for="active" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                                <label for="active" class="text-sm text-gray-700">Actif</label>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('reminders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les sélecteurs améliorés
            const searchableSelects = document.querySelectorAll('.searchable-select');
            searchableSelects.forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Rechercher...',
                    itemSelectText: 'Sélectionner',
                    noResultsText: 'Aucun résultat trouvé',
                    noChoicesText: 'Aucun élément à afficher',
                    placeholder: true,
                    placeholderValue: 'Sélectionnez une option',
                });
            });
            
            // Initialiser le sélecteur de priorité avec des classes personnalisées
            const prioritySelect = new Choices('#priority', {
                searchEnabled: false,
                itemSelectText: 'Sélectionner',
                classNames: {
                    item: 'choices__item',
                },
                callbackOnCreateTemplates: function(template) {
                    return {
                        item: (classNames, data) => {
                            return template(`
                                <div class="${classNames.item} ${data.customProperties ? data.customProperties : ''} ${data.highlighted ? classNames.highlightedState : classNames.itemSelectable}" data-item data-id="${data.id}" data-value="${data.value}" ${data.active ? 'aria-selected="true"' : ''} ${data.disabled ? 'aria-disabled="true"' : ''}>
                                    ${data.label}
                                </div>
                            `);
                        },
                        choice: (classNames, data) => {
                            return template(`
                                <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable} ${data.value === 'urgent' ? 'priority-urgent' : ''} ${data.value === 'high' ? 'priority-high' : ''} ${data.value === 'medium' ? 'priority-medium' : ''} ${data.value === 'low' ? 'priority-low' : ''}" data-select-text="${this.config.itemSelectText}" data-choice ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'} data-id="${data.id}" data-value="${data.value}" ${data.groupId > 0 ? 'role="treeitem"' : 'role="option"'}>
                                    ${data.label}
                                </div>
                            `);
                        },
                    };
                }
            });
            
            // Ajouter des classes personnalisées aux options de priorité
            const priorityItems = document.querySelectorAll('.choices__item--choice');
            priorityItems.forEach(item => {
                const value = item.getAttribute('data-value');
                if (value === 'low') item.classList.add('priority-low');
                if (value === 'medium') item.classList.add('priority-medium');
                if (value === 'high') item.classList.add('priority-high');
                if (value === 'urgent') item.classList.add('priority-urgent');
            });
            
            // Initialiser le sélecteur de fréquence
            new Choices('#frequency', {
                searchEnabled: false,
                itemSelectText: 'Sélectionner',
            });
            
            // Initialiser le sélecteur de date
            flatpickr("#due_date", {
                dateFormat: "Y-m-d",
                locale: "fr",
                minDate: "today",
                disableMobile: true,
                allowInput: true,
                altInput: true,
                altFormat: "j F Y",
            });
            
            // Initialiser le sélecteur d'heure
            flatpickr("#time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                disableMobile: true,
                allowInput: true,
                defaultHour: 8,
                defaultMinute: 0,
            });
            
            // Style personnalisé pour le toggle switch
            const style = document.createElement('style');
            style.textContent = `
                .toggle-checkbox:checked {
                    right: 0;
                    border-color: #4f46e5;
                }
                .toggle-checkbox:checked + .toggle-label {
                    background-color: #4f46e5;
                }
                .toggle-label {
                    transition: background-color 0.2s ease;
                }
            `;
            document.head.appendChild(style);
        });
        
        function reminderForm() {
            return {
                frequency: '{{ old('frequency', '') }}',
                toggleFrequencyOptions() {
                    this.frequency = document.getElementById('frequency').value;
                }
            }
        }
    </script>
</x-app-layout>