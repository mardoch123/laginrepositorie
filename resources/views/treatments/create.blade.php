<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau traitement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('treatments.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Type de sélection -->
                        <div class="bg-indigo-50 p-4 rounded-lg mb-6">
                            <label for="rabbit_selection_type" class="block text-sm font-medium text-gray-700 mb-2">Type de sélection</label>
                            <div class="flex space-x-4 mb-4">
                                <div class="flex items-center">
                                    <input id="individual" name="rabbit_selection_type" type="radio" value="individual" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                                    <label for="individual" class="ml-2 block text-sm text-gray-700">Lapins individuels</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="litter" name="rabbit_selection_type" type="radio" value="litter" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="litter" class="ml-2 block text-sm text-gray-700">Portée entière</label>
                                </div>
                            </div>

                            <!-- Sélection de lapins individuels -->
                            <div id="individual_selection" class="transition-all duration-300 ease-in-out">
                                <label for="rabbit_ids" class="block text-sm font-medium text-gray-700">Lapins (sélection multiple possible)</label>
                                <select id="rabbit_ids" name="rabbit_ids[]" multiple class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" size="6">
                                    @foreach ($rabbits as $rabbit)
                                        <option value="{{ $rabbit->id }}" {{ in_array($rabbit->id, old('rabbit_ids', [])) ? 'selected' : '' }}>
                                            {{ $rabbit->name }} ({{ $rabbit->tattoo_number }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs lapins</p>
                                @error('rabbit_ids')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sélection de portée -->
                            <div id="litter_selection" class="hidden transition-all duration-300 ease-in-out">
                                <label for="litter_id" class="block text-sm font-medium text-gray-700">Portée</label>
                                <select id="litter_id" name="litter_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Sélectionnez une portée</option>
                                    @foreach ($litters as $litter)
                                        <option value="{{ $litter->id }}" {{ old('litter_id') == $litter->id ? 'selected' : '' }}>
                                            Portée #{{ $litter->id }} - {{ $litter->mother->name ?? 'Mère inconnue' }} 
                                            ({{ $litter->actual_birth_date ? $litter->actual_birth_date->format('d/m/Y') : ($litter->expected_birth_date ? $litter->expected_birth_date->format('d/m/Y') : 'Date inconnue') }})
                                            - {{ $litter->number_of_kits ?? 0 }} lapereaux
                                        </option>
                                    @endforeach
                                </select>
                                @error('litter_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sélection du médicament avec suggestions -->
                            <div>
                                <label for="medication_id" class="block text-sm font-medium text-gray-700">Médicament</label>
                                <div class="relative">
                                    <select id="medication_id" name="medication_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">Sélectionnez un médicament</option>
                                        @foreach ($medications as $medication)
                                            <option value="{{ $medication->id }}" {{ old('medication_id') == $medication->id ? 'selected' : '' }}
                                                data-dosage="{{ $medication->dosage }}" 
                                                data-price="{{ $medication->price ?? '' }}">
                                                {{ $medication->name }} ({{ $medication->dosage }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="medication_suggestion" class="text-xs text-indigo-600 mt-1 hidden">
                                        Suggestion: <span id="suggestion_text"></span>
                                    </div>
                                </div>
                                @error('medication_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date prévue -->
                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date prévue</label>
                                <input type="date" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at', date('Y-m-d')) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('scheduled_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prix du traitement - NOUVEAU CHAMP -->
                            <div>
                                <label for="cost" class="block text-sm font-medium text-gray-700">Coût du traitement (F)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">F</span>
                                    </div>
                                    <input type="number" name="cost" id="cost" step="0.01" min="0" value="{{ old('cost') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Ce montant sera ajouté aux dépenses</p>
                                @error('cost')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catégorie de dépense -->
                            <div>
                                <label for="expense_category" class="block text-sm font-medium text-gray-700">Catégorie de dépense</label>
                                <select id="expense_category" name="expense_category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="medication" selected>Médicaments</option>
                                    <option value="veterinary">Frais vétérinaires</option>
                                    <option value="supplies">Fournitures médicales</option>
                                    <option value="other">Autre</option>
                                </select>
                                @error('expense_category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('treatments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Créer le traitement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section pour appliquer un protocole prédéfini -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Ou appliquer un protocole prédéfini
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Vous pouvez également appliquer un protocole prédéfini qui créera automatiquement plusieurs traitements selon un calendrier établi.
                    </p>
                    <a href="{{ route('protocols.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Appliquer un protocole
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const individualRadio = document.getElementById('individual');
            const litterRadio = document.getElementById('litter');
            const individualSelection = document.getElementById('individual_selection');
            const litterSelection = document.getElementById('litter_selection');
            const medicationSelect = document.getElementById('medication_id');
            const costInput = document.getElementById('cost');
            const suggestionDiv = document.getElementById('medication_suggestion');
            const suggestionText = document.getElementById('suggestion_text');

            function toggleSelectionType() {
                if (individualRadio.checked) {
                    individualSelection.classList.remove('hidden');
                    litterSelection.classList.add('hidden');
                } else {
                    individualSelection.classList.add('hidden');
                    litterSelection.classList.remove('hidden');
                }
            }

            // Suggestions automatiques pour le prix du médicament
            medicationSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price;
                
                if (price) {
                    costInput.value = price;
                    suggestionDiv.classList.remove('hidden');
                    suggestionText.textContent = `Prix suggéré: ${price}F basé sur le médicament sélectionné`;
                } else {
                    suggestionDiv.classList.add('hidden');
                }
            });

            individualRadio.addEventListener('change', toggleSelectionType);
            litterRadio.addEventListener('change', toggleSelectionType);
        });
    </script>
</x-app-layout>