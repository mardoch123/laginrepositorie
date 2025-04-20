<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la vente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Informations de base -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-1">Date de vente</label>
                                <input type="date" id="sale_date" name="sale_date" value="{{ $sale->sale_date->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                @error('sale_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Poids total (kg)</label>
                                <input type="number" id="weight_kg" name="weight_kg" step="0.01" min="0.01" value="{{ $sale->weight_kg }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                @error('weight_kg')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_per_kg" class="block text-sm font-medium text-gray-700 mb-1">Prix par kg (F)</label>
                                <input type="number" id="price_per_kg" name="price_per_kg" step="0.01" min="0.01" value="{{ $sale->price_per_kg }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required onchange="calculateTotal()" onkeyup="calculateTotal()">
                                @error('price_per_kg')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_price_display" class="block text-sm font-medium text-gray-700 mb-1">Prix total (F)</label>
                                <input type="text" id="total_price_display" readonly value="{{ number_format($sale->total_price, 2) }} F" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du client</label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ $sale->customer_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_contact" class="block text-sm font-medium text-gray-700 mb-1">Contact du client</label>
                                <input type="text" id="customer_contact" name="customer_contact" value="{{ $sale->customer_contact }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('customer_contact')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $sale->notes }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('sales.show', $sale->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const weightKg = parseFloat(document.getElementById('weight_kg').value) || 0;
            const pricePerKg = parseFloat(document.getElementById('price_per_kg').value) || 0;
            const totalPrice = weightKg * pricePerKg;
            
            document.getElementById('total_price_display').value = totalPrice.toFixed(2) + ' F';
        }
        
        // Initialiser les champs au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des écouteurs d'événements pour recalculer le total
            document.getElementById('weight_kg').addEventListener('input', calculateTotal);
            document.getElementById('price_per_kg').addEventListener('input', calculateTotal);
        });
    </script>
</x-app-layout>