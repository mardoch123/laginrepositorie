<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le traitement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('treatments.update', $treatment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sélection du lapin -->
                            <div>
                                <label for="rabbit_id" class="block text-sm font-medium text-gray-700">Lapin</label>
                                <select id="rabbit_id" name="rabbit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionnez un lapin</option>
                                    @foreach ($rabbits as $rabbit)
                                        <option value="{{ $rabbit->id }}" {{ (old('rabbit_id', $treatment->rabbit_id) == $rabbit->id) ? 'selected' : '' }}>
                                            {{ $rabbit->name }} ({{ $rabbit->tattoo_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rabbit_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sélection du médicament -->
                            <div>
                                <label for="medication_id" class="block text-sm font-medium text-gray-700">Médicament</label>
                                <select id="medication_id" name="medication_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Sélectionnez un médicament</option>
                                    @foreach ($medications as $medication)
                                        <option value="{{ $medication->id }}" {{ (old('medication_id', $treatment->medication_id) == $medication->id) ? 'selected' : '' }}>
                                            {{ $medication->name }} ({{ $medication->dosage }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('medication_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date prévue -->
                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date prévue</label>
                                <input type="date" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at', $treatment->scheduled_at->format('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('scheduled_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="pending" {{ (old('status', $treatment->status) == 'pending') ? 'selected' : '' }}>En attente</option>
                                    <option value="completed" {{ (old('status', $treatment->status) == 'completed') ? 'selected' : '' }}>Complété</option>
                                    <option value="cancelled" {{ (old('status', $treatment->status) == 'cancelled') ? 'selected' : '' }}>Annulé</option>
                                    <option value="skipped" {{ (old('status', $treatment->status) == 'skipped') ? 'selected' : '' }}>Ignoré</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes', $treatment->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('treatments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
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
</x-app-layout>