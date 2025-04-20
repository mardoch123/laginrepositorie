<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Modifier la portée
                        </h2>
                    </div>

                    <form action="{{ route('breedings.update', $breeding) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mère (affichage seulement) -->
                            <div>
                                <label for="mother_display" class="block text-sm font-medium text-gray-700">Mère</label>
                                <div class="mt-1 p-2 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $breeding->mother->name }} (ID: {{ $breeding->mother->identification_number }})
                                </div>
                                <input type="hidden" name="mother_id" value="{{ $breeding->mother_id }}">
                            </div>

                            <!-- Père (affichage seulement) -->
                            <div>
                                <label for="father_display" class="block text-sm font-medium text-gray-700">Père</label>
                                <div class="mt-1 p-2 bg-gray-50 border border-gray-300 rounded-md">
                                    {{ $breeding->father->name }} (ID: {{ $breeding->father->identification_number }})
                                </div>
                                <input type="hidden" name="father_id" value="{{ $breeding->father_id }}">
                            </div>

                            <!-- Date d'accouplement -->
                            <div>
                                <label for="mating_date" class="block text-sm font-medium text-gray-700">Date d'accouplement</label>
                                <input type="date" name="mating_date" id="mating_date" value="{{ old('mating_date', $breeding->mating_date->format('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('mating_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">La date prévue de naissance sera recalculée si vous modifiez la date d'accouplement.</p>
                            </div>

                            <!-- Date de naissance réelle -->
                            <div>
                                <label for="actual_birth_date" class="block text-sm font-medium text-gray-700">Date de naissance réelle</label>
                                <input type="date" name="actual_birth_date" id="actual_birth_date" value="{{ old('actual_birth_date', $breeding->actual_birth_date ? $breeding->actual_birth_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('actual_birth_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nombre total de petits -->
                            <div>
                                <label for="number_of_kits" class="block text-sm font-medium text-gray-700">Nombre total de petits</label>
                                <input type="number" name="number_of_kits" id="number_of_kits" value="{{ old('number_of_kits', $breeding->number_of_kits) }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('number_of_kits')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nombre de mâles -->
                            <div>
                                <label for="number_of_males" class="block text-sm font-medium text-gray-700">Nombre de mâles</label>
                                <input type="number" name="number_of_males" id="number_of_males" value="{{ old('number_of_males', $breeding->number_of_males ?? 0) }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('number_of_males')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nombre de femelles -->
                            <div>
                                <label for="number_of_females" class="block text-sm font-medium text-gray-700">Nombre de femelles</label>
                                <input type="number" name="number_of_females" id="number_of_females" value="{{ old('number_of_females', $breeding->number_of_females ?? 0) }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('number_of_females')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Après le champ "Nombre de femelles" -->
                            <div>
                                <label for="weaning_date" class="block text-sm font-medium text-gray-700">Date de sevrage</label>
                                <input type="date" name="weaning_date" id="weaning_date" value="{{ old('weaning_date', $breeding->weaning_date ? $breeding->weaning_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('weaning_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Calculée automatiquement (30 jours après la naissance)</p>
                            </div>

                            <div>
                                <label for="weaning_confirmed" class="flex items-center mt-1">
                                    <input type="checkbox" name="weaning_confirmed" id="weaning_confirmed" value="1" {{ old('weaning_confirmed', $breeding->weaning_confirmed) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Sevrage confirmé</span>
                                </label>
                                @error('weaning_confirmed')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fattening_start_date" class="block text-sm font-medium text-gray-700">Date de début d'engraissement</label>
                                <input type="date" name="fattening_start_date" id="fattening_start_date" value="{{ old('fattening_start_date', $breeding->fattening_start_date ? $breeding->fattening_start_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('fattening_start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Par défaut, même date que le sevrage</p>
                            </div>

                            <div>
                                <label for="expected_fattening_end_date" class="block text-sm font-medium text-gray-700">Date prévue de fin d'engraissement</label>
                                <input type="date" name="expected_fattening_end_date" id="expected_fattening_end_date" value="{{ old('expected_fattening_end_date', $breeding->expected_fattening_end_date ? $breeding->expected_fattening_end_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                                <p class="text-sm text-gray-500 mt-1">Calculée automatiquement (75 jours après le début d'engraissement)</p>
                            </div>

                            <div>
                                <label for="fattening_confirmed" class="flex items-center mt-1">
                                    <input type="checkbox" name="fattening_confirmed" id="fattening_confirmed" value="1" {{ old('fattening_confirmed', $breeding->fattening_confirmed) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Engraissement terminé</span>
                                </label>
                                @error('fattening_confirmed')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes', $breeding->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('breedings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalInput = document.getElementById('number_of_kits');
        const malesInput = document.getElementById('number_of_males');
        const femalesInput = document.getElementById('number_of_females');
        const weaningConfirmedInput = document.getElementById('weaning_confirmed');
        const fatteningStartDateInput = document.getElementById('fattening_start_date');
        const weaningDateInput = document.getElementById('weaning_date');
        
        // Fonction pour mettre à jour le total
        function updateTotal() {
            if (malesInput.value !== '' && femalesInput.value !== '') {
                const males = parseInt(malesInput.value) || 0;
                const females = parseInt(femalesInput.value) || 0;
                totalInput.value = males + females;
            }
        }
        
        // Fonction pour distribuer le total
        function distributeTotal() {
            if (totalInput.value !== '') {
                const total = parseInt(totalInput.value) || 0;
                const males = parseInt(malesInput.value) || 0;
                const females = parseInt(femalesInput.value) || 0;
                
                // Si un seul des deux champs est rempli, calculer l'autre
                if (males > 0 && females === 0) {
                    femalesInput.value = Math.max(0, total - males);
                } else if (females > 0 && males === 0) {
                    malesInput.value = Math.max(0, total - females);
                }
            }
        }
        
        // Fonction pour gérer le sevrage et l'engraissement
        function handleWeaningConfirmed() {
            if (weaningConfirmedInput.checked) {
                // Si le sevrage est confirmé, définir la date de début d'engraissement à la date de sevrage
                if (weaningDateInput.value) {
                    fatteningStartDateInput.value = weaningDateInput.value;
                }
            }
        }
        
        // Ajouter les écouteurs d'événements
        malesInput.addEventListener('change', updateTotal);
        femalesInput.addEventListener('change', updateTotal);
        totalInput.addEventListener('change', distributeTotal);
        weaningConfirmedInput.addEventListener('change', handleWeaningConfirmed);
        
        // Exécuter la fonction au chargement pour initialiser les valeurs
        handleWeaningConfirmed();
    });
    </script>
</x-app-layout>