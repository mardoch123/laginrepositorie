<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau diagnostic de santé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="diagnostic-form-container">
                        <form id="diagnostic-form" method="POST" action="{{ route('diagnostics.store') }}">
                            @csrf

                            <div class="mb-6">
                                <label for="rabbit_id" class="block text-sm font-medium text-gray-700">Lapin</label>
                                <select id="rabbit_id" name="rabbit_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Sélectionner un lapin</option>
                                    @foreach($rabbits as $rabbit)
                                        <option value="{{ $rabbit->id }}" data-breed="{{ $rabbit->breed }}" data-gender="{{ $rabbit->gender }}" data-age="{{ $rabbit->date_of_birth ? \Carbon\Carbon::parse($rabbit->date_of_birth)->diffInDays(\Carbon\Carbon::now()) : '' }}" data-weight="{{ $rabbit->weight }}">
                                            {{ $rabbit->name }} (ID: {{ $rabbit->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rabbit_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="rabbit-info" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Informations sur le lapin</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Race: <span id="rabbit-breed" class="font-medium text-gray-900"></span></p>
                                        <p class="text-sm text-gray-600">Sexe: <span id="rabbit-gender" class="font-medium text-gray-900"></span></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Âge: <span id="rabbit-age" class="font-medium text-gray-900"></span> jours</p>
                                        <p class="text-sm text-gray-600">Poids enregistré: <span id="rabbit-weight" class="font-medium text-gray-900"></span> kg</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="observed_date" class="block text-sm font-medium text-gray-700">Date d'observation</label>
                                <input type="date" id="observed_date" name="observed_date" value="{{ old('observed_date', now()->format('Y-m-d')) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('observed_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label for="temperature" class="block text-sm font-medium text-gray-700">Température (°C)</label>
                                    <input type="number" step="0.1" id="temperature" name="temperature" value="{{ old('temperature') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Normale: 38.5-40°C</p>
                                    @error('temperature')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                                    <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('weight')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="appetite_level" class="block text-sm font-medium text-gray-700">Niveau d'appétit</label>
                                    <select id="appetite_level" name="appetite_level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sélectionner</option>
                                        <option value="normal" {{ old('appetite_level') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="reduced" {{ old('appetite_level') == 'reduced' ? 'selected' : '' }}>Réduit</option>
                                        <option value="none" {{ old('appetite_level') == 'none' ? 'selected' : '' }}>Aucun</option>
                                    </select>
                                    @error('appetite_level')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="activity_level" class="block text-sm font-medium text-gray-700">Niveau d'activité</label>
                                <select id="activity_level" name="activity_level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    <option value="normal" {{ old('activity_level') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="reduced" {{ old('activity_level') == 'reduced' ? 'selected' : '' }}>Réduit</option>
                                    <option value="lethargic" {{ old('activity_level') == 'lethargic' ? 'selected' : '' }}>Léthargique</option>
                                </select>
                                @error('activity_level')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="symptoms" class="block text-sm font-medium text-gray-700">Symptômes observés</label>
                                <div class="mt-2 mb-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Diarrhée', 'Perte d\'appétit', 'Léthargie', 'Respiration difficile', 'Écoulement nasal', 'Toux', 'Éternuements', 'Gonflement', 'Boiterie', 'Perte de poils', 'Démangeaisons', 'Plaies', 'Urine anormale', 'Selles anormales'] as $symptom)
                                            <button type="button" class="symptom-tag px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $symptom }}</button>
                                        @endforeach
                                    </div>
                                </div>
                                <textarea id="symptoms" name="symptoms" rows="5" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ old('symptoms') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Décrivez en détail tous les symptômes observés. Cliquez sur les suggestions ci-dessus pour les ajouter.</p>
                                @error('symptoms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="additional_notes" class="block text-sm font-medium text-gray-700">Notes supplémentaires</label>
                                <textarea id="additional_notes" name="additional_notes" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('additional_notes') }}</textarea>
                                @error('additional_notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('diagnostics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Annuler
                                </a>
                                <button type="submit" id="submit-btn" class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg id="loading-icon" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Générer le diagnostic
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="loading-container" class="hidden">
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="animate-spin h-12 w-12 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Génération du diagnostic en cours...</h3>
                            <p class="text-sm text-gray-500">Cela peut prendre jusqu'à une minute. Veuillez patienter.</p>
                        </div>
                    </div>

                    <div id="result-container" class="hidden">
                        <div class="bg-green-50 p-4 rounded-md mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Diagnostic généré avec succès</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>Vous allez être redirigé vers la page du diagnostic.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Afficher les informations du lapin sélectionné
            const rabbitSelect = document.getElementById('rabbit_id');
            const rabbitInfo = document.getElementById('rabbit-info');
            const rabbitBreed = document.getElementById('rabbit-breed');
            const rabbitGender = document.getElementById('rabbit-gender');
            const rabbitAge = document.getElementById('rabbit-age');
            const rabbitWeight = document.getElementById('rabbit-weight');
            const weightInput = document.getElementById('weight');

            rabbitSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    rabbitBreed.textContent = selectedOption.dataset.breed || 'Non spécifié';
                    rabbitGender.textContent = selectedOption.dataset.gender === 'male' ? 'Mâle' : 'Femelle';
                    rabbitAge.textContent = selectedOption.dataset.age || 'Non spécifié';
                    rabbitWeight.textContent = selectedOption.dataset.weight || 'Non spécifié';
                    
                    // Pré-remplir le champ de poids avec le poids enregistré
                    if (selectedOption.dataset.weight) {
                        weightInput.value = selectedOption.dataset.weight;
                    }
                    
                    rabbitInfo.classList.remove('hidden');
                } else {
                    rabbitInfo.classList.add('hidden');
                }
            });

            // Gestion des tags de symptômes
            const symptomTags = document.querySelectorAll('.symptom-tag');
            const symptomsTextarea = document.getElementById('symptoms');

            symptomTags.forEach(tag => {
                tag.addEventListener('click', function() {
                    const symptom = this.textContent.trim();
                    const currentText = symptomsTextarea.value;
                    
                    if (currentText === '') {
                        symptomsTextarea.value = symptom;
                    } else if (!currentText.includes(symptom)) {
                        symptomsTextarea.value = currentText + ', ' + symptom.toLowerCase();
                    }
                    
                    symptomsTextarea.focus();
                });
            });

            // Soumission du formulaire avec AJAX
            const form = document.getElementById('diagnostic-form');
            const formContainer = document.getElementById('diagnostic-form-container');
            const loadingContainer = document.getElementById('loading-container');
            const resultContainer = document.getElementById('result-container');
            const submitBtn = document.getElementById('submit-btn');
            const loadingIcon = document.getElementById('loading-icon');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Afficher l'icône de chargement dans le bouton
                loadingIcon.classList.remove('hidden');
                submitBtn.disabled = true;
                
                // Collecter les données du formulaire
                const formData = new FormData(form);
                
                // Envoyer la requête AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    // Cacher le formulaire et afficher le conteneur de chargement
                    formContainer.classList.add('hidden');
                    loadingContainer.classList.remove('hidden');
                    
                    // Simuler un délai pour l'IA (peut être supprimé en production)
                    setTimeout(() => {
                        // Cacher le conteneur de chargement et afficher le résultat
                        loadingContainer.classList.add('hidden');
                        resultContainer.classList.remove('hidden');
                        
                        // Rediriger vers la page du diagnostic après un court délai
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    }, 2000);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la génération du diagnostic. Veuillez réessayer.');
                    
                    // Réinitialiser l'état du bouton
                    loadingIcon.classList.add('hidden');
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
</x-app-layout>