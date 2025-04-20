<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Ajouter une dépense') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-100">
                    <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Date') }}
                                </label>
                                <input id="date" name="date" type="date" 
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-shadow duration-200"
                                       required>
                                @error('date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Montant -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Montant (F)') }}
                                </label>
                                <input id="amount" name="amount" type="number" step="0.01" 
                                       value="{{ old('amount') }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Catégorie') }}
                                </label>
                                <select id="category" name="category" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="food">Nourriture</option>
                                    <option value="medication">Médicaments</option>
                                    <option value="equipment">Équipement</option>
                                    <option value="maintenance">Entretien</option>
                                    <option value="veterinary">Vétérinaire</option>
                                    <option value="other">Autre</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Méthode de paiement -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Méthode de paiement') }}
                                </label>
                                <select id="payment_method" name="payment_method" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="transfer">Virement</option>
                                    <option value="check">Chèque</option>
                                    <option value="other">Autre</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Description') }}
                                </label>
                                <textarea id="description" name="description" rows="3"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-6">
                            <a href="{{ route('expenses.index') }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>