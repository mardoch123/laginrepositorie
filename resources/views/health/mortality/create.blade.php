<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enregistrer un décès') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Erreurs :</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('health.mortality.store') }}" method="POST">
                        @csrf
                        
                        <!-- Type de sélection -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Type de sélection</label>
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input id="individual" name="selection_type" type="radio" value="individual" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" checked>
                                    <label for="individual" class="ml-2 block text-sm text-gray-700">Lapin individuel</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="litter" name="selection_type" type="radio" value="litter" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="litter" class="ml-2 block text-sm text-gray-700">Lapin de portée</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sélection de lapin individuel -->
                        <div id="individual_selection" class="mb-4">
                            <label for="rabbit_id" class="block text-gray-700 text-sm font-bold mb-2">Lapin</label>
                            <select id="rabbit_id" name="rabbit_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Sélectionnez un lapin</option>
                                @forelse ($rabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}" {{ old('rabbit_id') == $rabbit->id ? 'selected' : '' }}>
                                        {{ $rabbit->name }} ({{ $rabbit->tattoo_number ?? 'Sans tatouage' }})
                                    </option>
                                @empty
                                    <option disabled>Aucun lapin disponible</option>
                                @endforelse
                            </select>
                            @error('rabbit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Sélection de portée -->
                        <div id="litter_selection" class="mb-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="litter_id" class="block text-gray-700 text-sm font-bold mb-2">Portée</label>
                                    <select id="litter_id" name="litter_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Sélectionnez une portée</option>
                                        @forelse ($litters as $litter)
                                            <option value="{{ $litter->id }}" {{ old('litter_id') == $litter->id ? 'selected' : '' }}>
                                                Portée #{{ $litter->id }} - 
                                                Mère: {{ $litter->mother ? $litter->mother->name : 'Inconnue' }} - 
                                                Naissance: {{ $litter->birth_date ? $litter->birth_date->format('d/m/Y') : 'N/A' }} - 
                                                Lapereaux: {{ $litter->number_of_kits ?? 0 }}
                                            </option>
                                        @empty
                                            <option disabled>Aucune portée disponible</option>
                                        @endforelse
                                    </select>
                                    @error('litter_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="kit_count" class="block text-gray-700 text-sm font-bold mb-2">Nombre de lapereaux décédés</label>
                                    <input type="number" id="kit_count" name="kit_count" min="1" value="{{ old('kit_count', 1) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="kit_sex" class="block text-gray-700 text-sm font-bold mb-2">Sexe des lapereaux décédés</label>
                                    <select id="kit_sex" name="kit_sex" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="unknown" {{ old('kit_sex') == 'unknown' ? 'selected' : '' }}>Inconnu</option>
                                        <option value="male" {{ old('kit_sex') == 'male' ? 'selected' : '' }}>Mâle</option>
                                        <option value="female" {{ old('kit_sex') == 'female' ? 'selected' : '' }}>Femelle</option>
                                        <option value="mixed" {{ old('kit_sex') == 'mixed' ? 'selected' : '' }}>Mixte</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="death_date" class="block text-gray-700 text-sm font-bold mb-2">Date du décès</label>
                                <input type="date" id="death_date" name="death_date" value="{{ old('death_date', date('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div>
                                <label for="death_cause" class="block text-gray-700 text-sm font-bold mb-2">Cause du décès</label>
                                <select id="death_cause" name="death_cause" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Sélectionnez une cause</option>
                                    <option value="illness" {{ old('death_cause') == 'illness' ? 'selected' : '' }}>Maladie</option>
                                    <option value="accident" {{ old('death_cause') == 'accident' ? 'selected' : '' }}>Accident</option>
                                    <option value="predator" {{ old('death_cause') == 'predator' ? 'selected' : '' }}>Prédateur</option>
                                    <option value="slaughter" {{ old('death_cause') == 'slaughter' ? 'selected' : '' }}>Abattage</option>
                                    <option value="old_age" {{ old('death_cause') == 'old_age' ? 'selected' : '' }}>Vieillesse</option>
                                    <option value="birth_complication" {{ old('death_cause') == 'birth_complication' ? 'selected' : '' }}>Complication à la naissance</option>
                                    <option value="unknown" {{ old('death_cause') == 'unknown' ? 'selected' : '' }}>Cause inconnue</option>
                                    <option value="other" {{ old('death_cause') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>

                            <div id="illness_details" class="{{ old('death_cause') == 'illness' ? '' : 'hidden' }}">
                                <label for="illness_id" class="block text-gray-700 text-sm font-bold mb-2">Maladie associée</label>
                                <select id="illness_id" name="illness_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Sélectionnez une maladie</option>
                                    @foreach ($illnesses as $illness)
                                        <option value="{{ $illness->id }}" {{ old('illness_id') == $illness->id ? 'selected' : '' }}>
                                            {{ $illness->type }} (détecté le {{ $illness->detection_date->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                                <textarea id="notes" name="notes" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('health.mortality.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deathCauseSelect = document.getElementById('death_cause');
            const illnessDetails = document.getElementById('illness_details');
            const individualRadio = document.getElementById('individual');
            const litterRadio = document.getElementById('litter');
            const individualSelection = document.getElementById('individual_selection');
            const litterSelection = document.getElementById('litter_selection');
            
            // Gestion de l'affichage des détails de maladie
            function toggleIllnessDetails() {
                if (deathCauseSelect.value === 'illness') {
                    illnessDetails.classList.remove('hidden');
                } else {
                    illnessDetails.classList.add('hidden');
                }
            }
            
            // Gestion de l'affichage du type de sélection
            function toggleSelectionType() {
                if (individualRadio.checked) {
                    individualSelection.classList.remove('hidden');
                    litterSelection.classList.add('hidden');
                } else {
                    individualSelection.classList.add('hidden');
                    litterSelection.classList.remove('hidden');
                }
            }
            
            deathCauseSelect.addEventListener('change', toggleIllnessDetails);
            individualRadio.addEventListener('change', toggleSelectionType);
            litterRadio.addEventListener('change', toggleSelectionType);
            
            // Initialisation
            toggleIllnessDetails();
            toggleSelectionType();
        });
    </script>
</x-app-layout>