<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appliquer un protocole') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('protocols.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sélection du protocole -->
                            <div class="md:col-span-2">
                                <label for="protocol_name" class="block text-sm font-medium text-gray-700">Protocole</label>
                                <select id="protocol_name" name="protocol_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionnez un protocole</option>
                                    @foreach ($protocols as $protocol)
                                        <option value="{{ $protocol['name'] }}" {{ old('protocol_name') == $protocol['name'] ? 'selected' : '' }}>
                                            {{ $protocol['name'] }} - {{ $protocol['description'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('protocol_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type de sélection -->
                            <div class="md:col-span-2">
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
                                <div id="individual_selection">
                                    <label for="rabbit_ids" class="block text-sm font-medium text-gray-700">Lapins (sélection multiple possible)</label>
                                    <select id="rabbit_ids" name="rabbit_ids[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" size="6">
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
                                <div id="litter_selection" class="hidden">
                                    <label for="litter_id" class="block text-sm font-medium text-gray-700">Portée</label>
                                    <select id="litter_id" name="litter_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Sélectionnez une portée</option>
                                        @foreach ($litters as $litter)
                                            <option value="{{ $litter->id }}" {{ old('litter_id') == $litter->id ? 'selected' : '' }}>
                                                Portée #{{ $litter->id }} - {{ $litter->mother->name }} ({{ $litter->birth_date->format('d/m/Y') }}) - {{ $litter->kits->count() }} lapereaux
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('litter_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date de début -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('protocols.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Appliquer le protocole
                            </button>
                        </div>
                    </form>
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

            function toggleSelectionType() {
                if (individualRadio.checked) {
                    individualSelection.classList.remove('hidden');
                    litterSelection.classList.add('hidden');
                } else {
                    individualSelection.classList.add('hidden');
                    litterSelection.classList.remove('hidden');
                }
            }

            individualRadio.addEventListener('change', toggleSelectionType);
            litterRadio.addEventListener('change', toggleSelectionType);
        });
    </script>
</x-app-layout>