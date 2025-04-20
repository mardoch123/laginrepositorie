<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lapereaux en engraissement') }}
            </h2>
            <button id="openCageModal" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i>Assigner à une cage
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres et recherche -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <label for="cage-filter" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par cage</label>
                            <select id="cage-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Toutes les cages</option>
                                @foreach($cages ?? [] as $cage)
                                    <option value="{{ $cage->id }}">{{ $cage->name }} ({{ $cage->location }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Statut d'engraissement</label>
                            <select id="status-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Tous les statuts</option>
                                <option value="in-progress">En cours</option>
                                <option value="completed">Terminé</option>
                                <option value="imminent">Imminent (≤ 3 jours)</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-3 py-2 sm:text-sm border-gray-300 rounded-md" placeholder="Rechercher...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des lapereaux en engraissement -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($breedings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Portée
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date de sevrage
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jours en engraissement
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cage
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fin d'engraissement prévue
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($breedings as $breeding)
                                        <tr class="breeding-row" data-cage="{{ $breeding->cage_id ?? '' }}" data-status="{{ Carbon\Carbon::now()->gt($breeding->expected_fattening_end_date) ? 'completed' : (Carbon\Carbon::now()->diffInDays($breeding->expected_fattening_end_date) <= 3 ? 'imminent' : 'in-progress') }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected_breedings[]" value="{{ $breeding->id }}" class="breeding-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $breeding->mother->name }} × {{ $breeding->father->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ID: {{ $breeding->id }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $breeding->weaning_date->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $breeding->fattening_days }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $breeding->number_of_kits }} ({{ $breeding->number_of_males }}♂ / {{ $breeding->number_of_females }}♀)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if(isset($breeding->cage))
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            {{ $breeding->cage->name }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Non assigné
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $breeding->expected_fattening_end_date->format('d/m/Y') }}
                                                    @if(Carbon\Carbon::now()->gt($breeding->expected_fattening_end_date))
                                                        <span class="text-red-600 font-medium">(Terminé)</span>
                                                    @elseif(Carbon\Carbon::now()->diffInDays($breeding->expected_fattening_end_date) <= 3)
                                                        <span class="text-yellow-600 font-medium">(Imminent)</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('breedings.edit', $breeding) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <button type="button" class="assign-cage-btn text-green-600 hover:text-green-900" data-breeding-id="{{ $breeding->id }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">Aucun lapereau en engraissement pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour assigner une cage -->
    <div id="cageAssignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" x-data="{ selectedCage: '', selectedBreedings: [] }">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Assigner à une cage</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="assignCageForm" action="{{ route('kits.assign-cage') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="cage_id" class="block text-sm font-medium text-gray-700 mb-1 text-left">Sélectionner une cage</label>
                            <select id="cage_id" name="cage_id" x-model="selectedCage" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Sélectionner une cage</option>
                                @foreach($cages ?? [] as $cage)
                                    <option value="{{ $cage->id }}">
                                        {{ $cage->name }} ({{ $cage->location }}) - 
                                        {{ $cage->current_occupancy }}/{{ $cage->capacity }} occupé(s)
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-xs text-left mt-1">
                                <span x-show="selectedCage" class="text-gray-600">
                                    Capacité: <span x-text="getCageCapacity(selectedCage)"></span> lapins
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1 text-left">Lapereaux sélectionnés</label>
                            <div id="selected-breedings-container" class="mt-1 p-2 border border-gray-300 rounded-md min-h-[100px] max-h-[200px] overflow-y-auto text-left">
                                <p class="text-gray-500 text-sm" id="no-selections-message">Aucun lapereau sélectionné</p>
                                <ul id="selected-breedings-list" class="space-y-1"></ul>
                            </div>
                            <input type="hidden" name="breeding_ids" id="breeding_ids">
                        </div>
                        
                        <div class="flex justify-between mt-4">
                            <button type="button" id="closeCageModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Assigner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des filtres
            const cageFilter = document.getElementById('cage-filter');
            const statusFilter = document.getElementById('status-filter');
            const searchInput = document.getElementById('search');
            const breedingRows = document.querySelectorAll('.breeding-row');
            
            function applyFilters() {
                const cageValue = cageFilter.value;
                const statusValue = statusFilter.value;
                const searchValue = searchInput.value.toLowerCase();
                
                breedingRows.forEach(row => {
                    const rowCage = row.getAttribute('data-cage');
                    const rowStatus = row.getAttribute('data-status');
                    const rowText = row.textContent.toLowerCase();
                    
                    const cageMatch = !cageValue || rowCage === cageValue;
                    const statusMatch = !statusValue || rowStatus === statusValue;
                    const searchMatch = !searchValue || rowText.includes(searchValue);
                    
                    if (cageMatch && statusMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            cageFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
            
            // Gestion du modal
            const openModalBtn = document.getElementById('openCageModal');
            const closeModalBtn = document.getElementById('closeCageModal');
            const modal = document.getElementById('cageAssignmentModal');
            const selectAllCheckbox = document.getElementById('select-all');
            const breedingCheckboxes = document.querySelectorAll('.breeding-checkbox');
            const selectedBreedingsList = document.getElementById('selected-breedings-list');
            const noSelectionsMessage = document.getElementById('no-selections-message');
            const breedingIdsInput = document.getElementById('breeding_ids');
            const assignCageForm = document.getElementById('assignCageForm');
            
            // Fonction pour mettre à jour la liste des sélections
            function updateSelectedBreedingsList() {
                const selectedCheckboxes = document.querySelectorAll('.breeding-checkbox:checked');
                const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                
                if (selectedIds.length > 0) {
                    noSelectionsMessage.style.display = 'none';
                    selectedBreedingsList.innerHTML = '';
                    
                    selectedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr');
                        const breedingInfo = row.querySelector('td:nth-child(2)').textContent.trim();
                        const li = document.createElement('li');
                        li.className = 'text-sm';
                        li.textContent = breedingInfo;
                        selectedBreedingsList.appendChild(li);
                    });
                    
                    breedingIdsInput.value = selectedIds.join(',');
                } else {
                    noSelectionsMessage.style.display = 'block';
                    selectedBreedingsList.innerHTML = '';
                    breedingIdsInput.value = '';
                }
            }
            
            // Gestion de la sélection de tous les éléments
            selectAllCheckbox.addEventListener('change', function() {
                breedingCheckboxes.forEach(cb => {
                    if (cb.closest('tr').style.display !== 'none') {
                        cb.checked = selectAllCheckbox.checked;
                    }
                });
                updateSelectedBreedingsList();
            });
            
            // Gestion des checkboxes individuelles
            breedingCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectedBreedingsList);
            });
            
            // Ouvrir le modal
            openModalBtn.addEventListener('click', function() {
                updateSelectedBreedingsList();
                modal.classList.remove('hidden');
            });
            
            // Fermer le modal
            closeModalBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
            
            // Fermer le modal en cliquant à l'extérieur
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
            
            // Boutons d'assignation individuelle
            const assignCageBtns = document.querySelectorAll('.assign-cage-btn');
            assignCageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const breedingId = this.getAttribute('data-breeding-id');
                    
                    // Décocher toutes les cases
                    breedingCheckboxes.forEach(cb => {
                        cb.checked = false;
                    });
                    
                    // Cocher uniquement celle-ci
                    const checkbox = document.querySelector(`.breeding-checkbox[value="${breedingId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                    
                    updateSelectedBreedingsList();
                    modal.classList.remove('hidden');
                });
            });
            
            // Fonction pour obtenir la capacité d'une cage
            window.getCageCapacity = function(cageId) {
                const cages = @json($cages ?? []);
                const cage = cages.find(c => c.id == cageId);
                return cage ? `${cage.current_occupancy}/${cage.capacity}` : '0/0';
            };
            
            // Validation du formulaire
            assignCageForm.addEventListener('submit', function(e) {
                const selectedCage = document.getElementById('cage_id').value;
                const selectedBreedings = breedingIdsInput.value;
                
                if (!selectedCage) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une cage.');
                    return;
                }
                
                if (!selectedBreedings) {
                    e.preventDefault();
                    alert('Veuillez sélectionner au moins un lapereau.');
                    return;
                }
            });
        });
    </script>
</x-app-layout>