<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle portée') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('breedings.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mère (femelle) -->
                            <div>
                                <x-input-label for="mother_id" :value="__('Mère')" />
                                <select id="mother_id" name="mother_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionner une femelle</option>
                                    @foreach($females as $female)
                                        <option value="{{ $female->id }}" {{ old('mother_id') == $female->id ? 'selected' : '' }}>
                                            {{ $female->name }} ({{ $female->identification_number }}) - {{ $female->age_months }} mois
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('mother_id')" class="mt-2" />
                                @if(count($females) === 0)
                                    <p class="mt-2 text-sm text-red-600">Aucune femelle éligible (minimum 5 mois)</p>
                                @endif
                            </div>

                            <!-- Père (mâle) -->
                            <div>
                                <x-input-label for="father_id" :value="__('Père')" />
                                <select id="father_id" name="father_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionner un mâle</option>
                                    @foreach($males as $male)
                                        <option value="{{ $male->id }}" {{ old('father_id') == $male->id ? 'selected' : '' }}>
                                            {{ $male->name }} ({{ $male->identification_number }}) - {{ $male->age_months }} mois
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('father_id')" class="mt-2" />
                                @if(count($males) === 0)
                                    <p class="mt-2 text-sm text-red-600">Aucun mâle éligible (minimum 6 mois)</p>
                                @endif
                            </div>

                            <!-- Date d'accouplement -->
                            <div>
                                <x-input-label for="mating_date" :value="__('Date d\'accouplement')" />
                                <x-text-input id="mating_date" class="block mt-1 w-full" type="date" name="mating_date" :value="old('mating_date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('mating_date')" class="mt-2" />
                            </div>

                            <!-- Date de naissance prévue (calculée automatiquement) -->
                            <div>
                                <x-input-label for="expected_birth_date" :value="__('Date de naissance prévue')" />
                                <x-text-input id="expected_birth_date" class="block mt-1 w-full bg-gray-100" type="date" name="expected_birth_date" :value="old('expected_birth_date')" readonly />
                                <p class="mt-1 text-sm text-gray-500">Calculée automatiquement (31 jours après l'accouplement)</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('breedings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
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
            // Calculer la date de naissance prévue
            const matingDateInput = document.getElementById('mating_date');
            const expectedBirthDateInput = document.getElementById('expected_birth_date');
            
            function updateExpectedBirthDate() {
                const matingDate = new Date(matingDateInput.value);
                if (!isNaN(matingDate.getTime())) {
                    // Ajouter 31 jours à la date d'accouplement
                    const expectedDate = new Date(matingDate);
                    expectedDate.setDate(expectedDate.getDate() + 31);
                    
                    // Formater la date au format YYYY-MM-DD
                    const year = expectedDate.getFullYear();
                    const month = String(expectedDate.getMonth() + 1).padStart(2, '0');
                    const day = String(expectedDate.getDate()).padStart(2, '0');
                    
                    expectedBirthDateInput.value = `${year}-${month}-${day}`;
                }
            }
            
            // Calculer la date initiale
            updateExpectedBirthDate();
            
            // Mettre à jour lorsque la date d'accouplement change
            matingDateInput.addEventListener('change', updateExpectedBirthDate);
        });
    </script>
</x-app-layout>