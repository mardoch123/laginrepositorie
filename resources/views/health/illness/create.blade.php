<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Déclarer une maladie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('health.illness.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="rabbit_id" class="block text-sm font-medium text-gray-700">Lapin</label>
                                <select id="rabbit_id" name="rabbit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionnez un lapin</option>
                                    @foreach ($rabbits as $rabbit)
                                        <option value="{{ $rabbit->id }}" {{ old('rabbit_id') == $rabbit->id ? 'selected' : '' }}>
                                            {{ $rabbit->name }} ({{ $rabbit->tattoo_number ?? 'Sans tatouage' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rabbit_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type de maladie</label>
                                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="coccidiosis" {{ old('type') == 'coccidiosis' ? 'selected' : '' }}>Coccidiose</option>
                                    <option value="pasteurellosis" {{ old('type') == 'pasteurellosis' ? 'selected' : '' }}>Pasteurellose</option>
                                    <option value="myxomatosis" {{ old('type') == 'myxomatosis' ? 'selected' : '' }}>Myxomatose</option>
                                    <option value="vhd" {{ old('type') == 'vhd' ? 'selected' : '' }}>Maladie hémorragique virale</option>
                                    <option value="ear_mites" {{ old('type') == 'ear_mites' ? 'selected' : '' }}>Gale des oreilles</option>
                                    <option value="diarrhea" {{ old('type') == 'diarrhea' ? 'selected' : '' }}>Diarrhée</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700">Sévérité</label>
                                <select id="severity" name="severity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="mild" {{ old('severity') == 'mild' ? 'selected' : '' }}>Légère</option>
                                    <option value="moderate" {{ old('severity') == 'moderate' ? 'selected' : '' }}>Modérée</option>
                                    <option value="severe" {{ old('severity') == 'severe' ? 'selected' : '' }}>Sévère</option>
                                </select>
                                @error('severity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="detection_date" class="block text-sm font-medium text-gray-700">Date de détection</label>
                                <input type="date" id="detection_date" name="detection_date" value="{{ old('detection_date', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('detection_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="recovered" {{ old('status') == 'recovered' ? 'selected' : '' }}>Guérie</option>
                                    <option value="chronic" {{ old('status') == 'chronic' ? 'selected' : '' }}>Chronique</option>
                                    <option value="fatal" {{ old('status') == 'fatal' ? 'selected' : '' }}>Fatale</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="recovery_date_container" class="{{ old('status') == 'recovered' ? '' : 'hidden' }}">
                                <label for="recovery_date" class="block text-sm font-medium text-gray-700">Date de guérison</label>
                                <input type="date" id="recovery_date" name="recovery_date" value="{{ old('recovery_date') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('recovery_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="symptoms" class="block text-sm font-medium text-gray-700">Symptômes</label>
                                <textarea id="symptoms" name="symptoms" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('symptoms') }}</textarea>
                                @error('symptoms')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('health.illness.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
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
            const statusSelect = document.getElementById('status');
            const recoveryDateContainer = document.getElementById('recovery_date_container');
            
            function toggleRecoveryDate() {
                if (statusSelect.value === 'recovered') {
                    recoveryDateContainer.classList.remove('hidden');
                } else {
                    recoveryDateContainer.classList.add('hidden');
                }
            }
            
            statusSelect.addEventListener('change', toggleRecoveryDate);
        });
    </script>
</x-app-layout>