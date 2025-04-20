<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le rappel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('reminders.update', $reminder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $reminder->title) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $reminder->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $reminder->due_date ? $reminder->due_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('due_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                            <select name="priority" id="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="low" {{ old('priority', $reminder->priority) == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ old('priority', $reminder->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority', $reminder->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                                <option value="urgent" {{ old('priority', $reminder->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priority')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="frequency" class="block text-sm font-medium text-gray-700">Fréquence</label>
                            <select name="frequency" id="frequency" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Aucune (date unique)</option>
                                <option value="daily" {{ old('frequency', $reminder->frequency) == 'daily' ? 'selected' : '' }}>Quotidien</option>
                                <option value="weekly" {{ old('frequency', $reminder->frequency) == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                <option value="custom" {{ old('frequency', $reminder->frequency) == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                            </select>
                            @error('frequency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4 weekly-options" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700">Jours de la semaine</label>
                            <div class="mt-2 space-y-2">
                                @foreach(['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $index => $day)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="days_of_week[]" id="day-{{ $index }}" value="{{ $index }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($index, old('days_of_week', $reminder->days_of_week ?? [])) ? 'checked' : '' }}>
                                        <label for="day-{{ $index }}" class="ml-2 block text-sm text-gray-700">{{ $day }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('days_of_week')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4 custom-options" style="display: none;">
                            <label for="interval_days" class="block text-sm font-medium text-gray-700">Intervalle (jours)</label>
                            <input type="number" name="interval_days" id="interval_days" value="{{ old('interval_days', $reminder->interval_days) }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('interval_days')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="time" class="block text-sm font-medium text-gray-700">Heure</label>
                            <input type="time" name="time" id="time" value="{{ old('time', $reminder->time ? $reminder->time->format('H:i') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="active" id="active" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('active', $reminder->active) ? 'checked' : '' }}>
                                <label for="active" class="ml-2 block text-sm text-gray-700">Actif</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_completed" id="is_completed" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_completed', $reminder->is_completed) ? 'checked' : '' }}>
                                <label for="is_completed" class="ml-2 block text-sm text-gray-700">Terminé</label>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <a href="{{ route('reminders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
            const frequencySelect = document.getElementById('frequency');
            const weeklyOptions = document.querySelector('.weekly-options');
            const customOptions = document.querySelector('.custom-options');
            
            // Fonction pour afficher/masquer les options en fonction de la fréquence
            function toggleOptions() {
                const selectedValue = frequencySelect.value;
                
                weeklyOptions.style.display = selectedValue === 'weekly' ? 'block' : 'none';
                customOptions.style.display = selectedValue === 'custom' ? 'block' : 'none';
            }
            
            // Initialiser l'affichage
            toggleOptions();
            
            // Écouter les changements
            frequencySelect.addEventListener('change', toggleOptions);
        });
    </script>
</x-app-layout>