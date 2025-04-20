<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier une maladie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <a href="{{ route('health.illness.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Retour à la liste
                        </a>
                    </div>

                    <form action="{{ route('health.illness.update', $illness) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type de maladie</label>
                                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    @foreach($illnessTypes as $value => $label)
                                        <option value="{{ $value }}" {{ $illness->type == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700">Sévérité</label>
                                <select id="severity" name="severity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="mild" {{ $illness->severity == 'mild' ? 'selected' : '' }}>Légère</option>
                                    <option value="moderate" {{ $illness->severity == 'moderate' ? 'selected' : '' }}>Modérée</option>
                                    <option value="severe" {{ $illness->severity == 'severe' ? 'selected' : '' }}>Sévère</option>
                                </select>
                                @error('severity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required onchange="toggleCureDateField()">
                                    <option value="active" {{ $illness->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="cured" {{ $illness->status == 'cured' ? 'selected' : '' }}>Guérie</option>
                                    <option value="chronic" {{ $illness->status == 'chronic' ? 'selected' : '' }}>Chronique</option>
                                    <option value="fatal" {{ $illness->status == 'fatal' ? 'selected' : '' }}>Fatale</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="cure_date_container" class="{{ $illness->status == 'cured' ? '' : 'hidden' }}">
                                <label for="cure_date" class="block text-sm font-medium text-gray-700">Date de guérison</label>
                                <input type="date" id="cure_date" name="cure_date" value="{{ $illness->cure_date ?? date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('cure_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Symptômes</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @php
                                        $selectedSymptoms = is_string($illness->symptoms) ? [$illness->symptoms] : json_decode($illness->symptoms, true);
                                        if (!is_array($selectedSymptoms)) {
                                            $selectedSymptoms = [$illness->symptoms];
                                        }
                                    @endphp
                                    
                                    @foreach($symptoms as $value => $label)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="symptom_{{ $value }}" name="symptoms[]" value="{{ $value }}" 
                                                {{ in_array($value, $selectedSymptoms) ? 'checked' : '' }}
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="symptom_{{ $value }}" class="ml-2 block text-sm text-gray-900">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('symptoms')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $illness->notes }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Traitements associés</h3>
                            
                            @if($treatments->count() > 0)
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($treatments as $treatment)
                                            <li class="py-3">
                                                <div class="flex justify-between">
                                                    <div>
                                                        <p class="font-medium">{{ $treatment->description }}</p>
                                                        <p class="text-sm text-gray-500">
                                                            Début: {{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}
                                                            @if($treatment->end_date)
                                                                | Fin: {{ \Carbon\Carbon::parse($treatment->end_date)->format('d/m/Y') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $treatment->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $treatment->status == 'active' ? 'En cours' : 'Terminé' }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-gray-500 italic">Aucun traitement associé à cette maladie.</p>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('treatments.create', ['illness_id' => $illness->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Ajouter un traitement
                                </a>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCureDateField() {
            const status = document.getElementById('status').value;
            const cureDateContainer = document.getElementById('cure_date_container');
            
            if (status === 'cured') {
                cureDateContainer.classList.remove('hidden');
            } else {
                cureDateContainer.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>