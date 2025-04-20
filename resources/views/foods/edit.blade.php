<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
                {{ __('Modifier la nourriture') }}
            </h2>
            <a href="{{ route('foods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('foods.update', $food->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nom</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $food->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $food->description) }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="frequency" class="block font-medium text-sm text-gray-700">Fréquence</label>
                                    <select name="frequency" id="frequency" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="daily" {{ old('frequency', $food->frequency) == 'daily' ? 'selected' : '' }}>Quotidien</option>
                                        <option value="alternate_days" {{ old('frequency', $food->frequency) == 'alternate_days' ? 'selected' : '' }}>Jours alternés</option>
                                        <option value="weekly" {{ old('frequency', $food->frequency) == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                        <option value="weekdays" {{ old('frequency', $food->frequency) == 'weekdays' ? 'selected' : '' }}>Jours de semaine</option>
                                        <option value="weekends" {{ old('frequency', $food->frequency) == 'weekends' ? 'selected' : '' }}>Week-ends</option>
                                    </select>
                                    @error('frequency')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label for="quantity_per_rabbit" class="block font-medium text-sm text-gray-700">Quantité par lapin</label>
                                    <input type="number" step="0.01" name="quantity_per_rabbit" id="quantity_per_rabbit" value="{{ old('quantity_per_rabbit', $food->quantity_per_rabbit) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('quantity_per_rabbit')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="unit" class="block font-medium text-sm text-gray-700">Unité</label>
                                    <select name="unit" id="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="g" {{ old('unit', $food->unit) == 'g' ? 'selected' : '' }}>Grammes (g)</option>
                                        <option value="kg" {{ old('unit', $food->unit) == 'kg' ? 'selected' : '' }}>Kilogrammes (kg)</option>
                                        <option value="ml" {{ old('unit', $food->unit) == 'ml' ? 'selected' : '' }}>Millilitres (ml)</option>
                                        <option value="l" {{ old('unit', $food->unit) == 'l' ? 'selected' : '' }}>Litres (l)</option>
                                    </select>
                                    @error('unit')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="block font-medium text-sm text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes', $food->notes) }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_active" name="is_active" type="checkbox" {{ old('is_active', $food->is_active) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active" class="font-medium text-gray-700">Actif</label>
                                            <p class="text-gray-500">Cochez cette case si cette nourriture est actuellement utilisée</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>