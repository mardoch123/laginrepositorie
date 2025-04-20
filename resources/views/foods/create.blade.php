<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
            {{ __('Ajouter une nourriture') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('foods.store') }}" id="foodForm" class="space-y-6">
                        @csrf

                        <!-- Suggestions rapides -->
                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <h3 class="text-blue-800 font-medium mb-2">Suggestions rapides</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="food-suggestion px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full text-sm transition" 
                                    data-name="Granulés standard" 
                                    data-frequency="daily" 
                                    data-quantity="0.15" 
                                    data-unit="kg" 
                                    data-description="Granulés complets pour lapins adultes">Granulés standard</button>
                                <button type="button" class="food-suggestion px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full text-sm transition" 
                                    data-name="Foin de prairie" 
                                    data-frequency="daily" 
                                    data-quantity="0.10" 
                                    data-unit="kg" 
                                    data-description="Foin de prairie naturel riche en fibres">Foin de prairie</button>
                                <button type="button" class="food-suggestion px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full text-sm transition" 
                                    data-name="Légumes frais" 
                                    data-frequency="weekdays" 
                                    data-quantity="50" 
                                    data-unit="g" 
                                    data-description="Mélange de légumes frais (carottes, céleri, endives)">Légumes frais</button>
                                <button type="button" class="food-suggestion px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-full text-sm transition" 
                                    data-name="Eau fraîche" 
                                    data-frequency="daily" 
                                    data-quantity="0.25" 
                                    data-unit="l" 
                                    data-description="Eau potable fraîche et propre">Eau fraîche</button>
                            </div>
                        </div>

                        <!-- Ajouter cette alerte d'information -->
                        <div class="bg-yellow-50 p-4 rounded-lg mb-6 border-l-4 border-yellow-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Note:</strong> Le système limite automatiquement le nombre de nourritures à 2 par jour lors de la génération des emplois du temps.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom avec autocomplétion -->
                            <div class="relative">
                                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Nom') }}</label>
                                <div class="relative">
                                    <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10" 
                                        type="text" name="name" value="{{ old('name') }}" 
                                        placeholder="Ex: Granulés complets, Foin, Légumes..." 
                                        list="food-suggestions"
                                        required autofocus />
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a7 7 0 100 14 7 7 0 000-14zm-9 7a9 9 0 1118 0 9 9 0 01-18 0z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M10 7a1 1 0 011 1v2a1 1 0 11-2 0V8a1 1 0 011-1z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M10 11a1 1 0 011 1v.01a1 1 0 11-2 0V12a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <datalist id="food-suggestions">
                                    <option value="Granulés complets">
                                    <option value="Foin de prairie">
                                    <option value="Légumes frais">
                                    <option value="Eau fraîche">
                                    <option value="Complément vitaminé">
                                </datalist>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fréquence avec icônes -->
                            <div>
                                <label for="frequency" class="block font-medium text-sm text-gray-700">{{ __('Fréquence') }}</label>
                                <div class="relative">
                                    <select id="frequency" name="frequency" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10" style="height: 45px;">
                                        <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Quotidienne</option>
                                        <option value="alternate_days" {{ old('frequency') == 'alternate_days' ? 'selected' : '' }}>Jours alternés</option>
                                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                        <option value="weekdays" {{ old('frequency') == 'weekdays' ? 'selected' : '' }}>Jours de semaine</option>
                                        <option value="weekends" {{ old('frequency') == 'weekends' ? 'selected' : '' }}>Week-ends</option>
                                    </select>
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2" id="frequency-visual">
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Lundi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Mardi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Mercredi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Jeudi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Vendredi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Samedi"></span>
                                    <span class="inline-block w-6 h-6 rounded-full bg-gray-200" title="Dimanche"></span>
                                </div>
                                @error('frequency')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantité par lapin avec slider -->
                            <div>
                                <label for="quantity_per_rabbit" class="block font-medium text-sm text-gray-700">
                                    {{ __('Quantité par lapin') }}
                                    <span id="quantity-display" class="ml-2 text-indigo-600 font-bold">0.15</span>
                                </label>
                                <div class="relative mt-1">
                                    <input id="quantity_per_rabbit" 
                                        class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10" 
                                        type="number" 
                                        name="quantity_per_rabbit" 
                                        value="{{ old('quantity_per_rabbit', '0.15') }}" 
                                        step="0.01" 
                                        min="0.01" 
                                        required />
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <input type="range" id="quantity_slider" min="0.01" max="1" step="0.01" value="0.15" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-2">
                                @error('quantity_per_rabbit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unité avec icônes -->
                            <div>
                                <label for="unit" class="block font-medium text-sm text-gray-700">{{ __('Unité') }}</label>
                                <div class="relative">
                                    <select style="height: 45px;" id="unit" name="unit" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10">
                                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Grammes (g)</option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogrammes (kg)</option>
                                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Millilitres (ml)</option>
                                        <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Litres (l)</option>
                                    </select>
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut avec toggle switch -->
                            <div>
                                <span class="block font-medium text-sm text-gray-700 mb-2">{{ __('Statut') }}</span>
                                <label for="is_active" class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <div class="block bg-gray-300 w-14 h-8 rounded-full"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                    </div>
                                    <div class="ml-3 text-gray-700 font-medium">
                                        <span id="status-text">Actif</span>
                                    </div>
                                </label>
                                @error('is_active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description avec compteur de caractères -->
                        <div class="mt-4">
                            <div class="flex justify-between">
                                <label for="description" class="block font-medium text-sm text-gray-700">{{ __('Description') }}</label>
                                <span class="text-xs text-gray-500" id="description-counter">0/500</span>
                            </div>
                            <div class="relative">
                                <textarea id="description" name="description" rows="3" maxlength="500" 
                                    placeholder="Décrivez cette nourriture (composition, bénéfices, etc.)"
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10">{{ old('description') }}</textarea>
                                <span class="absolute left-3 top-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes avec compteur de caractères -->
                        <div class="mt-4">
                            <div class="flex justify-between">
                                <label for="notes" class="block font-medium text-sm text-gray-700">{{ __('Notes') }}</label>
                                <span class="text-xs text-gray-500" id="notes-counter">0/500</span>
                            </div>
                            <div class="relative">
                                <textarea id="notes" name="notes" rows="3" maxlength="500" 
                                    placeholder="Ajoutez des notes supplémentaires (conseils d'utilisation, précautions, etc.)"
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10">{{ old('notes') }}</textarea>
                                <span class="absolute left-3 top-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Boutons d'action avec animations -->
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('foods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Annuler
                            </a>
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-600 hover:to-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du toggle switch
            const toggleSwitch = document.getElementById('is_active');
            const statusText = document.getElementById('status-text');
            const toggleDot = document.querySelector('.dot');

            function updateToggle() {
                if (toggleSwitch.checked) {
                    toggleDot.classList.add('translate-x-6');
                    toggleDot.parentElement.querySelector('.block').classList.remove('bg-gray-300');
                    toggleDot.parentElement.querySelector('.block').classList.add('bg-green-400');
                    statusText.textContent = 'Actif';
                    statusText.classList.remove('text-gray-500');
                    statusText.classList.add('text-green-600');
                } else {
                    toggleDot.classList.remove('translate-x-6');
                    toggleDot.parentElement.querySelector('.block').classList.remove('bg-green-400');
                    toggleDot.parentElement.querySelector('.block').classList.add('bg-gray-300');
                    statusText.textContent = 'Inactif';
                    statusText.classList.remove('text-green-600');
                    statusText.classList.add('text-gray-500');
                }
            }

            toggleSwitch.addEventListener('change', updateToggle);
            updateToggle(); // Initialisation

            // Gestion du slider de quantité
            const quantityInput = document.getElementById('quantity_per_rabbit');
            const quantitySlider = document.getElementById('quantity_slider');
            const quantityDisplay = document.getElementById('quantity-display');

            function updateQuantityFromSlider() {
                quantityInput.value = quantitySlider.value;
                quantityDisplay.textContent = quantitySlider.value;
            }

            function updateSliderFromQuantity() {
                const val = parseFloat(quantityInput.value);
                if (!isNaN(val)) {
                    if (val > 1) {
                        quantitySlider.max = Math.ceil(val);
                    }
                    quantitySlider.value = val;
                    quantityDisplay.textContent = val;
                }
            }

            quantitySlider.addEventListener('input', updateQuantityFromSlider);
            quantityInput.addEventListener('input', updateSliderFromQuantity);
            updateSliderFromQuantity(); // Initialisation

            // Visualisation de la fréquence
            const frequencySelect = document.getElementById('frequency');
            const frequencyVisual = document.getElementById('frequency-visual');
            const days = frequencyVisual.querySelectorAll('span');

            function updateFrequencyVisual() {
                // Réinitialiser tous les jours
                days.forEach(day => {
                    day.classList.remove('bg-indigo-500');
                    day.classList.add('bg-gray-200');
                });

                // Mettre à jour selon la fréquence sélectionnée
                switch(frequencySelect.value) {
                    case 'daily':
                        days.forEach(day => {
                            day.classList.remove('bg-gray-200');
                            day.classList.add('bg-indigo-500');
                        });
                        break;
                    case 'alternate_days':
                        for(let i = 0; i < days.length; i += 2) {
                            days[i].classList.remove('bg-gray-200');
                            days[i].classList.add('bg-indigo-500');
                        }
                        break;
                    case 'weekly':
                        days[0].classList.remove('bg-gray-200');
                        days[0].classList.add('bg-indigo-500');
                        break;
                    case 'weekdays':
                        for(let i = 0; i < 5; i++) {
                            days[i].classList.remove('bg-gray-200');
                            days[i].classList.add('bg-indigo-500');
                        }
                        break;
                    case 'weekends':
                        days[5].classList.remove('bg-gray-200');
                        days[5].classList.add('bg-indigo-500');
                        days[6].classList.remove('bg-gray-200');
                        days[6].classList.add('bg-indigo-500');
                        break;
                }
            }

            frequencySelect.addEventListener('change', updateFrequencyVisual);
            updateFrequencyVisual(); // Initialisation

            // Compteurs de caractères
            const descriptionTextarea = document.getElementById('description');
            const descriptionCounter = document.getElementById('description-counter');
            const notesTextarea = document.getElementById('notes');
            const notesCounter = document.getElementById('notes-counter');

            function updateCounter(textarea, counter) {
                const count = textarea.value.length;
                const max = textarea.getAttribute('maxlength');
                counter.textContent = `${count}/${max}`;
                
                if (count > max * 0.8) {
                    counter.classList.add('text-yellow-500');
                } else {
                    counter.classList.remove('text-yellow-500');
                }
                
                if (count > max * 0.95) {
                    counter.classList.add('text-red-500');
                } else {
                    counter.classList.remove('text-red-500');
                }
            }

            descriptionTextarea.addEventListener('input', () => updateCounter(descriptionTextarea, descriptionCounter));
            notesTextarea.addEventListener('input', () => updateCounter(notesTextarea, notesCounter));
            
            // Initialisation des compteurs
            updateCounter(descriptionTextarea, descriptionCounter);
            updateCounter(notesTextarea, notesCounter);

            // Suggestions rapides
            const suggestions = document.querySelectorAll('.food-suggestion');
            
            suggestions.forEach(suggestion => {
                suggestion.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const frequency = this.getAttribute('data-frequency');
                    const quantity = this.getAttribute('data-quantity');
                    const unit = this.getAttribute('data-unit');
                    const description = this.getAttribute('data-description');
                    
                    document.getElementById('name').value = name;
                    document.getElementById('frequency').value = frequency;
                    document.getElementById('quantity_per_rabbit').value = quantity;
                    document.getElementById('unit').value = unit;
                    document.getElementById('description').value = description;
                    
                    // Mettre à jour les éléments visuels
                    updateFrequencyVisual();
                    updateSliderFromQuantity();
                    updateCounter(descriptionTextarea, descriptionCounter);
                    
                    // Animation de confirmation
                    this.classList.add('bg-green-200', 'text-green-800');
                    setTimeout(() => {
                        this.classList.remove('bg-green-200', 'text-green-800');
                    }, 1000);
                });
            });

            // Animation du bouton de soumission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.addEventListener('mouseenter', function() {
                this.classList.add('animate-pulse');
            });
            submitBtn.addEventListener('mouseleave', function() {
                this.classList.remove('animate-pulse');
            });
        });
    </script>
</x-app-layout>