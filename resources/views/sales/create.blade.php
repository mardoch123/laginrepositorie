<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouvelle vente') }}
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
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                            <p class="font-bold">Des erreurs sont survenues :</p>
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Le reste du formulaire reste inchangé -->
                        <!-- Type de vente -->
                        <div>
                            <label for="sale_type" class="block text-sm font-medium text-gray-700 mb-1">Type de vente</label>
                            <select id="sale_type" name="sale_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required onchange="toggleSaleTypeFields()">
                                <option value="">Sélectionner un type</option>
                                <option value="individual" {{ old('sale_type') == 'individual' ? 'selected' : '' }}>Vente individuelle</option>
                                <option value="group" {{ old('sale_type') == 'group' ? 'selected' : '' }}>Vente en groupe</option>
                                <option value="breeding" {{ old('sale_type') == 'breeding' ? 'selected' : '' }}>Vente de portée</option>
                            </select>
                            @error('sale_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Champs spécifiques au type de vente -->
                        <div id="individual_fields" class="hidden">
                            <label for="rabbit_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un lapin</label>
                            <select id="rabbit_id" name="rabbit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Sélectionner un lapin</option>
                                @foreach($rabbitsForSale as $rabbit)
                                    <option value="{{ $rabbit->id }}" {{ old('rabbit_id') == $rabbit->id ? 'selected' : '' }}>
                                        {{ $rabbit->name }} - 
                                        @if($rabbit->gender == 'male')
                                            ♂ Mâle
                                        @elseif($rabbit->gender == 'female')
                                            ♀ Femelle
                                        @else
                                            Sexe inconnu
                                        @endif
                                        - {{ $rabbit->current_weight ? $rabbit->current_weight . 'g' : 'Poids inconnu' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rabbit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="group_fields" class="hidden">
                            <label for="rabbit_ids" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner des lapins</label>
                            <select id="rabbit_ids" name="rabbit_ids[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" size="5">
                                @foreach($rabbitsForSale as $rabbit)
                                    <option value="{{ $rabbit->id }}" {{ (is_array(old('rabbit_ids')) && in_array($rabbit->id, old('rabbit_ids'))) ? 'selected' : '' }}>
                                        {{ $rabbit->name }} - 
                                        @if($rabbit->gender == 'male')
                                            ♂ Mâle
                                        @elseif($rabbit->gender == 'female')
                                            ♀ Femelle
                                        @else
                                            Sexe inconnu
                                        @endif
                                        - {{ $rabbit->current_weight ? $rabbit->current_weight . 'g' : 'Poids inconnu' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs lapins</p>
                            @error('rabbit_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('rabbit_ids.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            <div class="mt-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Nombre de lapins</label>
                                <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="breeding_fields" class="hidden">
                            <label for="breeding_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner une portée</label>
                            <select id="breeding_id" name="breeding_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Sélectionner une portée</option>
                                @foreach($breedingsForSale as $breeding)
                                    <option value="{{ $breeding->id }}" {{ old('breeding_id') == $breeding->id ? 'selected' : '' }}>
                                        Portée de {{ $breeding->mother->name }} - {{ $breeding->number_of_kits }} lapereaux
                                        ({{ $breeding->number_of_males ?? 0 }} mâles, {{ $breeding->number_of_females ?? 0 }} femelles)
                                    </option>
                                @endforeach
                            </select>
                            @error('breeding_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informations communes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-1">Date de vente</label>
                                <input type="date" id="sale_date" name="sale_date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                @error('sale_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Poids total (kg)</label>
                                <input type="number" id="weight_kg" name="weight_kg" step="0.01" min="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                @error('weight_kg')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_per_kg" class="block text-sm font-medium text-gray-700 mb-1">Prix par kg (F)</label>
                                <select id="price_per_kg" name="price_per_kg" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required onchange="calculateTotal()">
                                    <option value="">Sélectionner un prix</option>
                                    <option value="2500">2500 F</option>
                                    <option value="2750">2750 F</option>
                                    <option value="3000">3000 F</option>
                                    <option value="3250">3250 F</option>
                                    <option value="3500">3500 F</option>
                                    <option value="3750">3750 F</option>
                                    <option value="4000">4000 F</option>
                                    <option value="4250">4250 F</option>
                                    <option value="4500">4500 F</option>
                                    <option value="4750">4750 F</option>
                                    <option value="5000">5000 F</option>
                                </select>
                                @error('price_per_kg')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_price_display" class="block text-sm font-medium text-gray-700 mb-1">Prix total (F)</label>
                                <input type="text" id="total_price_display" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du client</label>
                                <input type="text" id="customer_name" name="customer_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_contact" class="block text-sm font-medium text-gray-700 mb-1">Contact du client</label>
                                <input type="text" id="customer_contact" name="customer_contact" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('customer_contact')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Enregistrer la vente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSaleTypeFields() {
            const saleType = document.getElementById('sale_type').value;
            
            // Cacher tous les champs spécifiques
            document.getElementById('individual_fields').classList.add('hidden');
            document.getElementById('group_fields').classList.add('hidden');
            document.getElementById('breeding_fields').classList.add('hidden');
            
            // Afficher les champs correspondant au type sélectionné
            if (saleType === 'individual') {
                document.getElementById('individual_fields').classList.remove('hidden');
                document.getElementById('rabbit_id').setAttribute('required', 'required');
                document.getElementById('breeding_id').removeAttribute('required');
            } else if (saleType === 'group') {
                document.getElementById('group_fields').classList.remove('hidden');
                document.getElementById('rabbit_ids').setAttribute('required', 'required');
                document.getElementById('rabbit_id').removeAttribute('required');
                document.getElementById('breeding_id').removeAttribute('required');
            } else if (saleType === 'breeding') {
                document.getElementById('breeding_fields').classList.remove('hidden');
                document.getElementById('breeding_id').setAttribute('required', 'required');
                document.getElementById('rabbit_id').removeAttribute('required');
            }
        }
        
        function calculateTotal() {
            const weightKg = parseFloat(document.getElementById('weight_kg').value) || 0;
            const pricePerKg = parseFloat(document.getElementById('price_per_kg').value) || 0;
            const totalPrice = weightKg * pricePerKg;
            
            document.getElementById('total_price_display').value = totalPrice.toFixed(2) + ' F';
        }
        
        // Initialiser les champs au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Appliquer le type de vente sélectionné (pour conserver la sélection après soumission du formulaire)
            toggleSaleTypeFields();
            calculateTotal();
            
            // Ajouter des écouteurs d'événements pour recalculer le total
            document.getElementById('weight_kg').addEventListener('input', calculateTotal);
            document.getElementById('price_per_kg').addEventListener('input', calculateTotal);
            
            // S'assurer que le type de vente est correctement initialisé
            const saleType = document.getElementById('sale_type').value;
            if (saleType) {
                toggleSaleTypeFields();
            }
        });
    </script>
</x-app-layout>