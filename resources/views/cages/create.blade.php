<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une cage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('cages.store') }}" class="space-y-6">
                        @csrf

                        <!-- Nom de la cage -->
                        <div>
                            <x-input-label for="name" :value="__('Nom de la cage')" />
                            <div class="relative">
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', 'Cage-' . date('Ymd') . '-' . rand(100, 999))" required autofocus />
                                <button type="button" id="generateName" class="absolute inset-y-0 right-0 px-3 flex items-center bg-gray-100 hover:bg-gray-200 rounded-r-md">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            <div id="name-suggestions" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Emplacement -->
                        <div>
                            <x-input-label for="location" :value="__('Emplacement')" />
                            <div class="relative">
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="Ex: Bâtiment A, Zone 1, Extérieur..." />
                                <button type="button" id="showLocations" class="absolute inset-y-0 right-0 px-3 flex items-center bg-gray-100 hover:bg-gray-200 rounded-r-md">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            <div id="location-suggestions" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <div class="relative">
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Décrivez la cage, sa taille, ses caractéristiques...">{{ old('description') }}</textarea>
                                <button type="button" id="generateDescription" class="absolute top-2 right-2 p-1 bg-gray-100 hover:bg-gray-200 rounded-md">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <div id="description-suggestions" class="mt-2 flex flex-col gap-2"></div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <x-input-label for="is_active" :value="__('Statut')" />
                            <div class="mt-2 flex space-x-4">
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-indigo-500 transition-colors">
                                    <input type="radio" class="form-radio text-indigo-600 hidden" name="is_active" value="1" checked>
                                    <span class="h-5 w-5 border border-gray-300 rounded-full flex items-center justify-center mr-2 radio-button">
                                        <span class="h-3 w-3 rounded-full bg-indigo-600 opacity-0 transition-opacity duration-200"></span>
                                    </span>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Active</span>
                                        <p class="text-xs text-gray-500">La cage est disponible pour y placer des lapins</p>
                                    </div>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-indigo-500 transition-colors">
                                    <input type="radio" class="form-radio text-indigo-600 hidden" name="is_active" value="0">
                                    <span class="h-5 w-5 border border-gray-300 rounded-full flex items-center justify-center mr-2 radio-button">
                                        <span class="h-3 w-3 rounded-full bg-indigo-600 opacity-0 transition-opacity duration-200"></span>
                                    </span>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Inactive</span>
                                        <p class="text-xs text-gray-500">La cage n'est pas disponible pour le moment</p>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('cages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Annuler
                            </a>
                            <x-primary-button>
                                {{ __('Enregistrer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Suggestions pour les noms de cage
            const nameExamples = [
                'Cage-Principale', 'Cage-Maternité', 'Cage-Engraissement', 
                'Cage-Reproduction', 'Cage-Sevrage', 'Cage-Quarantaine',
                'Cage-Isolement', 'Cage-Extérieure', 'Cage-Intérieure'
            ];
            
            // Suggestions pour les emplacements
            const locationExamples = [
                'Bâtiment A', 'Bâtiment B', 'Zone 1', 'Zone 2', 
                'Extérieur Nord', 'Extérieur Sud', 'Hangar Principal',
                'Abri Couvert', 'Serre', 'Étable'
            ];
            
            // Suggestions pour les descriptions
            const descriptionExamples = [
                'Cage standard pour lapins adultes avec mangeoire et abreuvoir automatique.',
                'Grande cage spacieuse adaptée pour une lapine et sa portée.',
                'Cage d\'engraissement avec espace optimisé pour 3-4 lapereaux.',
                'Cage de reproduction avec nid amovible et espace confortable.',
                'Cage d\'isolement avec surveillance facile pour lapins malades.'
            ];
            
            // Fonction pour générer un nom aléatoire
            document.getElementById('generateName').addEventListener('click', function() {
                const randomPrefix = ['Cage', 'Box', 'Enclos', 'Habitat'][Math.floor(Math.random() * 4)];
                const randomNumber = Math.floor(Math.random() * 900) + 100;
                document.getElementById('name').value = `${randomPrefix}-${randomNumber}`;
                
                // Afficher des suggestions
                showSuggestions('name-suggestions', nameExamples, 'name');
            });
            
            // Fonction pour afficher les suggestions d'emplacement
            document.getElementById('showLocations').addEventListener('click', function() {
                showSuggestions('location-suggestions', locationExamples, 'location');
            });
            
            // Fonction pour générer une description
            document.getElementById('generateDescription').addEventListener('click', function() {
                showSuggestions('description-suggestions', descriptionExamples, 'description');
            });
            
            // Fonction pour afficher les suggestions
            function showSuggestions(containerId, suggestions, targetFieldId) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';
                
                suggestions.forEach(suggestion => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded text-sm transition-colors';
                    button.textContent = suggestion;
                    button.addEventListener('click', function() {
                        document.getElementById(targetFieldId).value = suggestion;
                    });
                    container.appendChild(button);
                });
            }
            
            // Style pour les boutons radio personnalisés
            const radioButtons = document.querySelectorAll('input[type="radio"][name="is_active"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Réinitialiser tous les styles
                    document.querySelectorAll('.radio-button span').forEach(span => {
                        span.classList.add('opacity-0');
                    });
                    document.querySelectorAll('input[type="radio"][name="is_active"]').forEach(r => {
                        r.closest('label').classList.remove('border-indigo-500', 'bg-indigo-50');
                        r.closest('label').classList.add('border-gray-200');
                    });
                    
                    // Appliquer le style au bouton sélectionné
                    if (this.checked) {
                        this.closest('label').classList.remove('border-gray-200');
                        this.closest('label').classList.add('border-indigo-500', 'bg-indigo-50');
                        this.nextElementSibling.querySelector('span').classList.remove('opacity-0');
                    }
                });
                
                // Initialiser l'état des boutons radio
                if (radio.checked) {
                    radio.closest('label').classList.remove('border-gray-200');
                    radio.closest('label').classList.add('border-indigo-500', 'bg-indigo-50');
                    radio.nextElementSibling.querySelector('span').classList.remove('opacity-0');
                }
            });
        });
    </script>
</x-app-layout>