<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un lapin') }}
        </h2>
    </x-slot>

    <!-- Notification container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('rabbits.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dans la section du nom du lapin -->
                            <div>
                                <x-input-label for="name" :value="__('Nom')" />
                                <div class="flex">
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <button type="button" id="generateName" class="ml-2 mt-1 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                <div id="name-suggestions" class="mt-2 flex flex-wrap gap-2 hidden"></div>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Numéro d'identification -->
                            <div>
                                <x-input-label for="identification_number" :value="__('Numéro d\'identification')" />
                                <div class="flex">
                                    <x-text-input id="identification_number" class="block mt-1 w-full" type="text" name="identification_number" :value="old('identification_number', 'LAP-' . date('Ymd') . '-' . rand(1000, 9999))" required />
                                    <button type="button" id="generateId" class="ml-2 mt-1 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('identification_number')" class="mt-2" />
                            </div>

                            <!-- Sexe -->
                            <div>
                                <x-input-label for="gender" :value="__('Sexe')" />
                                <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Mâle</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femelle</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <x-input-label for="birth_date" :value="__('Date de naissance')" />
                                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>

                            <!-- Race -->
                            <div>
                                <x-input-label for="breed" :value="__('Race')" />
                                <select id="breed" name="breed" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Néo-Zélandais Blanc" {{ old('breed') == 'Néo-Zélandais Blanc' ? 'selected' : '' }}>Néo-Zélandais Blanc</option>
                                    <option value="Californien" {{ old('breed') == 'Californien' ? 'selected' : '' }}>Californien</option>
                                    <option value="Rex" {{ old('breed') == 'Rex' ? 'selected' : '' }}>Rex</option>
                                    <option value="Géant des Flandres" {{ old('breed') == 'Géant des Flandres' ? 'selected' : '' }}>Géant des Flandres</option>
                                    <option value="Nain de Hollande" {{ old('breed') == 'Nain de Hollande' ? 'selected' : '' }}>Nain de Hollande</option>
                                    <option value="Angora" {{ old('breed') == 'Angora' ? 'selected' : '' }}>Angora</option>
                                    <option value="Bélier" {{ old('breed') == 'Bélier' ? 'selected' : '' }}>Bélier</option>
                                    <option value="Autre" {{ old('breed') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <x-input-error :messages="$errors->get('breed')" class="mt-2" />
                            </div>

                            <!-- Couleur -->
                            <div>
                                <x-input-label for="color" :value="__('Couleur')" />
                                <select id="color" name="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Blanc" {{ old('color') == 'Blanc' ? 'selected' : '' }}>Blanc</option>
                                    <option value="Noir" {{ old('color') == 'Noir' ? 'selected' : '' }}>Noir</option>
                                    <option value="Gris" {{ old('color') == 'Gris' ? 'selected' : '' }}>Gris</option>
                                    <option value="Marron" {{ old('color') == 'Marron' ? 'selected' : '' }}>Marron</option>
                                    <option value="Roux" {{ old('color') == 'Roux' ? 'selected' : '' }}>Roux</option>
                                    <option value="Fauve" {{ old('color') == 'Fauve' ? 'selected' : '' }}>Fauve</option>
                                    <option value="Tacheté" {{ old('color') == 'Tacheté' ? 'selected' : '' }}>Tacheté</option>
                                    <option value="Tricolore" {{ old('color') == 'Tricolore' ? 'selected' : '' }}>Tricolore</option>
                                    <option value="Autre" {{ old('color') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                            </div>

                            <!-- Cage -->
                            <div>
                                <x-input-label for="cage_id" :value="__('Cage')" />
                                <div class="flex">
                                    <select id="cage_id" name="cage_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Sélectionner une cage</option>
                                        @foreach($cages as $cage)
                                            <option value="{{ $cage->id }}" {{ old('cage_id') == $cage->id ? 'selected' : '' }}>{{ $cage->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="openCageModal" class="ml-2 mt-1 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('cage_id')" class="mt-2" />
                            </div>

                            <!-- Statut -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="alive" {{ old('status', 'alive') == 'alive' ? 'selected' : '' }}>Vivant</option>
                                    <option value="dead" {{ old('status') == 'dead' ? 'selected' : '' }}>Mort</option>
                                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendu</option>
                                    <option value="given" {{ old('status') == 'given' ? 'selected' : '' }}>Donné</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Actif -->
                            <div class="flex items-center mt-4">
                                <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <x-input-label for="is_active" :value="__('Actif')" class="ml-2" />
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('rabbits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
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

    <!-- Modal pour ajouter une cage -->
    <div id="cageModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Ajouter une nouvelle cage
                        </h3>
                        <div class="mt-4">
                            <!-- Notification container dans le modal -->
                            <div id="modal-notification" class="mb-4 hidden"></div>
                            
                            <form id="cageForm">
                                <div class="mb-4">
                                    <x-input-label for="cage_name" :value="__('Nom de la cage')" />
                                    <x-text-input id="cage_name" class="block mt-1 w-full" type="text" name="cage_name" required />
                                    <div id="cage_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="cage_location" :value="__('Emplacement')" />
                                    <x-text-input id="cage_location" class="block mt-1 w-full" type="text" name="cage_location" />
                                    <div id="cage_location_error" class="mt-1 text-sm text-red-600 hidden"></div>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="cage_description" :value="__('Description')" />
                                    <textarea id="cage_description" name="cage_description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    <div id="cage_description_error" class="mt-1 text-sm text-red-600 hidden"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveCage" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Enregistrer
                </button>
                <button type="button" id="closeCageModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour afficher les notifications
            function showNotification(message, type = 'success') {
                const container = document.getElementById('notification-container');
                const notification = document.createElement('div');
                
                // Classes Tailwind pour les différents types de notifications
                const classes = {
                    success: 'bg-green-100 border-l-4 border-green-500 text-green-700',
                    error: 'bg-red-100 border-l-4 border-red-500 text-red-700',
                    warning: 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
                    info: 'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
                };
                
                notification.className = `${classes[type]} p-4 rounded shadow-md mb-2 flex justify-between items-center`;
                notification.innerHTML = `
                    <div>${message}</div>
                    <button class="text-gray-500 hover:text-gray-800 focus:outline-none">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(notification);
                
                // Ajouter un gestionnaire d'événements pour fermer la notification
                notification.querySelector('button').addEventListener('click', function() {
                    notification.remove();
                });
                
                // Supprimer automatiquement la notification après 5 secondes
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
            
            // Fonction pour afficher les notifications dans le modal
            function showModalNotification(message, type = 'success') {
                const container = document.getElementById('modal-notification');
                
                // Classes Tailwind pour les différents types de notifications
                const classes = {
                    success: 'bg-green-100 border-l-4 border-green-500 text-green-700',
                    error: 'bg-red-100 border-l-4 border-red-500 text-red-700',
                    warning: 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
                    info: 'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
                };
                
                container.className = `${classes[type]} p-4 rounded shadow-md mb-4`;
                container.textContent = message;
                container.classList.remove('hidden');
                
                // Masquer la notification après 3 secondes
                setTimeout(() => {
                    container.classList.add('hidden');
                }, 3000);
            }
            
            // Fonction pour afficher les erreurs de validation
            function showValidationError(field, message) {
                const errorElement = document.getElementById(`${field}_error`);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            }
            
            // Fonction pour réinitialiser les erreurs de validation
            function resetValidationErrors() {
                const errorElements = document.querySelectorAll('[id$="_error"]');
                errorElements.forEach(element => {
                    element.textContent = '';
                    element.classList.add('hidden');
                });
            }

            document.getElementById('generateName').addEventListener('click', function() {
                const gender = document.getElementById('gender').value;
                const color = document.getElementById('color').value;
                const breed = document.getElementById('breed').value;
                
                // Liste de noms pour les lapins mâles
                const maleNames = [
                    'Oreo', 'Thumper', 'Bugs', 'Coco', 'Pépère', 'Caramel', 'Noisette', 'Grisou', 
                    'Flocon', 'Milo', 'Oscar', 'Simba', 'Léo', 'Rocky', 'Bandit', 'Pantoufle',
                    'Chocolat', 'Éclair', 'Filou', 'Jumpy', 'Pogo', 'Rouky', 'Snoopy', 'Toby'
                ];
                
                // Liste de noms pour les lapines femelles
                const femaleNames = [
                    'Clémentine', 'Cannelle', 'Neige', 'Luna', 'Nala', 'Caline', 'Praline', 'Vanille',
                    'Perle', 'Rosette', 'Framboise', 'Mirabelle', 'Pistache', 'Réglisse', 'Sucre',
                    'Violette', 'Cerise', 'Doucette', 'Fleur', 'Guimauve', 'Noisette', 'Pêche', 'Truffe'
                ];
                
                // Noms basés sur les couleurs
                const colorNames = {
                    'Blanc': ['Flocon', 'Neige', 'Blanchette', 'Coton', 'Perle'],
                    'Noir': ['Charbon', 'Ébène', 'Réglisse', 'Shadow', 'Onyx'],
                    'Gris': ['Grisou', 'Cendre', 'Silver', 'Fumée', 'Perle'],
                    'Marron': ['Chocolat', 'Noisette', 'Brownie', 'Cannelle', 'Caramel'],
                    'Roux': ['Rouky', 'Roussette', 'Ginger', 'Abricot', 'Renard'],
                    'Fauve': ['Simba', 'Lion', 'Fauve', 'Doré', 'Miel'],
                };
                
                // Noms basés sur les races
                const breedNames = {
                    'Néo-Zélandais Blanc': ['Kiwi', 'Auckland', 'Wellington'],
                    'Californien': ['Hollywood', 'Malibu', 'Sunny'],
                    'Rex': ['King', 'Queen', 'Royal', 'Velours'],
                    'Géant des Flandres': ['Titan', 'Goliath', 'Hercule', 'Géant'],
                    'Nain de Hollande': ['Tulipe', 'Amsterdam', 'Mini', 'Tiny'],
                    'Angora': ['Duvet', 'Plume', 'Soyeux', 'Doudou'],
                    'Bélier': ['Floppy', 'Dumbo', 'Oreille', 'Pendouille'],
                };
                
                // Sélection des noms appropriés
                let names = [];
                
                // Ajouter les noms basés sur le sexe
                if (gender === 'male') {
                    names = [...maleNames];
                } else {
                    names = [...femaleNames];
                }
                
                // Ajouter les noms basés sur la couleur si disponible
                if (color && colorNames[color]) {
                    names = [...names, ...colorNames[color]];
                }
                
                // Ajouter les noms basés sur la race si disponible
                if (breed) {
                    for (const breedKey in breedNames) {
                        if (breed.includes(breedKey)) {
                            names = [...names, ...breedNames[breedKey]];
                            break;
                        }
                    }
                }
                
                // Mélanger le tableau pour obtenir un ordre aléatoire
                names.sort(() => Math.random() - 0.5);
                
                // Sélectionner un nom aléatoire
                const randomName = names[Math.floor(Math.random() * names.length)];
                
                // Mettre à jour le champ de nom
                document.getElementById('name').value = randomName;
                
                // Afficher les suggestions de noms
                const suggestionsContainer = document.getElementById('name-suggestions');
                if (suggestionsContainer) {
                    suggestionsContainer.innerHTML = '';
                    suggestionsContainer.classList.remove('hidden');
                    
                    // Afficher 5 suggestions de noms
                    const suggestions = [];
                    while (suggestions.length < 5 && suggestions.length < names.length) {
                        const suggestion = names[Math.floor(Math.random() * names.length)];
                        if (!suggestions.includes(suggestion) && suggestion !== randomName) {
                            suggestions.push(suggestion);
                        }
                    }
                    
                    suggestions.forEach(name => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm';
                        button.textContent = name;
                        button.addEventListener('click', function() {
                            document.getElementById('name').value = name;
                        });
                        suggestionsContainer.appendChild(button);
                    });
                }
            });

            // Génération d'ID
            document.getElementById('generateId').addEventListener('click', function() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const random = Math.floor(Math.random() * 9000) + 1000;
                document.getElementById('identification_number').value = `LAP-${year}${month}${day}-${random}`;
            });

            // Modal de cage
            const cageModal = document.getElementById('cageModal');
            const openCageModal = document.getElementById('openCageModal');
            const closeCageModal = document.getElementById('closeCageModal');
            const saveCage = document.getElementById('saveCage');
            const cageSelect = document.getElementById('cage_id');

            openCageModal.addEventListener('click', function() {
                resetValidationErrors();
                document.getElementById('modal-notification').classList.add('hidden');
                cageModal.classList.remove('hidden');
            });

            closeCageModal.addEventListener('click', function() {
                cageModal.classList.add('hidden');
            });

            saveCage.addEventListener('click', function() {
                resetValidationErrors();
                
                const name = document.getElementById('cage_name').value;
                const location = document.getElementById('cage_location').value;
                const description = document.getElementById('cage_description').value;

                if (!name) {
                    showValidationError('cage_name', 'Le nom de la cage est requis');
                    return;
                }

                // Envoyer les données au serveur via AJAX
                fetch('{{ route("cages.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        location: location,
                        description: description,
                        is_active: true
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Une erreur est survenue');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Ajouter la nouvelle cage au select
                        const option = document.createElement('option');
                        option.value = data.cage.id;
                        option.text = data.cage.name;
                        option.selected = true;
                        cageSelect.appendChild(option);
                        
                        // Afficher une notification de succès
                        showNotification('Cage ajoutée avec succès', 'success');
                        
                        // Fermer le modal
                        cageModal.classList.add('hidden');
                        
                        // Réinitialiser le formulaire
                        document.getElementById('cageForm').reset();
                    } else {
                        showModalNotification(data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showModalNotification(error.message || 'Une erreur est survenue lors de la création de la cage', 'error');
                });
            });
        });
    </script>
</x-app-layout>