<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Diagnostics de santé') }}
            </h2>
            <div class="flex space-x-2">
                <button id="bulk-delete-btn" disabled class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Supprimer la sélection
                </button>
                <a href="{{ route('diagnostics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau diagnostic
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par date</label>
                            <div class="flex space-x-2">
                                <input type="date" id="date-from" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="De">
                                <input type="date" id="date-to" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="À">
                            </div>
                        </div>
                        <div>
                            <label for="rabbit-filter" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par lapin</label>
                            <select id="rabbit-filter" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Tous les lapins</option>
                                @foreach($rabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}">{{ $rabbit->name }} (ID: {{ $rabbit->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="search-filter" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="search-filter" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Rechercher dans les symptômes...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="bulk-delete-form" action="{{ route('diagnostics.bulk-delete') }}" method="POST" class="hidden">
                        @csrf
                        <!-- Supprimer cette ligne qui définit la méthode DELETE -->
                        <!-- @method('DELETE') -->
                        <input type="hidden" name="selected_ids" id="selected-ids-input">
                    </form>

                    @if($diagnostics->isEmpty())
                        <div class="text-center py-8" id="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun diagnostic</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau diagnostic de santé.</p>
                            <div class="mt-6">
                                <a href="{{ route('diagnostics.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nouveau diagnostic
                                </a>
                            </div>
                        </div>
                        <div id="no-results" class="hidden text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat</h3>
                            <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos filtres de recherche.</p>
                        </div>
                    @else
                        <div id="no-results" class="hidden text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat</h3>
                            <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos filtres de recherche.</p>
                        </div>
                        <div class="overflow-x-auto" id="diagnostics-table-container">
                            <table class="min-w-full divide-y divide-gray-200" id="diagnostics-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3">
                                            <div class="flex items-center">
                                                <input id="select-all" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" data-sort="date">
                                            <div class="flex items-center">
                                                Date
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" data-sort="rabbit">
                                            <div class="flex items-center">
                                                Lapin
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symptômes</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="diagnostics-body">
                                    @foreach($diagnostics as $diagnostic)
                                        <tr class="diagnostic-row hover:bg-gray-50" 
                                            data-id="{{ $diagnostic->id }}" 
                                            data-date="{{ $diagnostic->observed_date->format('Y-m-d') }}" 
                                            data-rabbit-id="{{ $diagnostic->rabbit->id }}" 
                                            data-symptoms="{{ $diagnostic->symptoms }}">
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="diagnostic-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" data-id="{{ $diagnostic->id }}">
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $diagnostic->observed_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                        <span class="text-indigo-700 font-medium">{{ substr($diagnostic->rabbit->name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $diagnostic->rabbit->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            ID: {{ $diagnostic->rabbit->id }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 truncate max-w-xs">
                                                    {{ Str::limit($diagnostic->symptoms, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if(strpos($diagnostic->ai_diagnosis ?? '', 'Erreur') === 0)
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Erreur
                                                    </span>
                                                @elseif(!$diagnostic->ai_diagnosis)
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        En attente
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Complété
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('diagnostics.show', $diagnostic) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1 rounded-full">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('diagnostics.print', $diagnostic) }}" target="_blank" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-1 rounded-full">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                        </svg>
                                                    </a>
                                                    <button type="button" class="delete-btn text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1 rounded-full" data-id="{{ $diagnostic->id }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $diagnostics->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div id="delete-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmer la suppression
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="delete-message">
                                    Êtes-vous sûr de vouloir supprimer ce diagnostic ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                    <button type="button" id="cancel-delete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let selectedIds = [];
            let currentDeleteId = null;
            let isBulkDelete = false;
            
            // Éléments DOM
            const diagnosticRows = document.querySelectorAll('.diagnostic-row');
            const selectAllCheckbox = document.getElementById('select-all');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.getElementById('delete-modal');
            const confirmDeleteBtn = document.getElementById('confirm-delete');
            const cancelDeleteBtn = document.getElementById('cancel-delete');
            const deleteMessage = document.getElementById('delete-message');
            const diagnosticCheckboxes = document.querySelectorAll('.diagnostic-checkbox');
            const dateFromInput = document.getElementById('date-from');
            const dateToInput = document.getElementById('date-to');
            const rabbitFilter = document.getElementById('rabbit-filter');
            const searchFilter = document.getElementById('search-filter');
            const diagnosticsTable = document.getElementById('diagnostics-table-container');
            const noResultsDiv = document.getElementById('no-results');
            const sortableHeaders = document.querySelectorAll('th[data-sort]');
            const bulkDeleteForm = document.getElementById('bulk-delete-form');
            const selectedIdsInput = document.getElementById('selected-ids-input');
            
            // État du tri
            let sortState = {
                column: 'date',
                direction: 'desc'
            };
            
            // Fonctions d'aide
            function updateBulkDeleteButton() {
                bulkDeleteBtn.disabled = selectedIds.length === 0;
            }
            
            function updateSelectAllCheckbox() {
                const allCheckboxes = document.querySelectorAll('.diagnostic-checkbox:not([disabled])');
                const checkedCheckboxes = document.querySelectorAll('.diagnostic-checkbox:checked');
                
                if (allCheckboxes.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCheckboxes.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCheckboxes.length === allCheckboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }
            
            function applyFilters() {
                const dateFrom = dateFromInput.value ? new Date(dateFromInput.value) : null;
                const dateTo = dateToInput.value ? new Date(dateToInput.value) : null;
                const rabbitId = rabbitFilter.value;
                const searchText = searchFilter.value.toLowerCase();
                
                let visibleCount = 0;
                
                diagnosticRows.forEach(row => {
                    const rowDate = new Date(row.dataset.date);
                    const rowRabbitId = row.dataset.rabbitId;
                    const rowSymptoms = row.dataset.symptoms.toLowerCase();
                    
                    let visible = true;
                    
                    if (dateFrom && rowDate < dateFrom) visible = false;
                    if (dateTo && rowDate > dateTo) visible = false;
                    if (rabbitId && rowRabbitId !== rabbitId) visible = false;
                    if (searchText && !rowSymptoms.includes(searchText)) visible = false;
                    
                    row.classList.toggle('hidden', !visible);
                    
                    if (visible) visibleCount++;
                });
                
                // Afficher/masquer le message "Aucun résultat"
                diagnosticsTable.classList.toggle('hidden', visibleCount === 0);
                noResultsDiv.classList.toggle('hidden', visibleCount > 0);
                
                // Mettre à jour l'état des cases à cocher
                updateSelectAllCheckbox();
            }
            
            function sortTable() {
                const tbody = document.getElementById('diagnostics-body');
                const rows = Array.from(tbody.querySelectorAll('tr:not(.hidden)'));
                
                rows.sort((a, b) => {
                    let valueA, valueB;
                    
                    if (sortState.column === 'date') {
                        valueA = new Date(a.dataset.date);
                        valueB = new Date(b.dataset.date);
                    } else if (sortState.column === 'rabbit') {
                        valueA = a.querySelector('td:nth-child(3) .text-sm.font-medium').textContent.trim();
                        valueB = b.querySelector('td:nth-child(3) .text-sm.font-medium').textContent.trim();
                    }
                    
                    if (sortState.direction === 'asc') {
                        return valueA > valueB ? 1 : -1;
                    } else {
                        return valueA < valueB ? 1 : -1;
                    }
                });
                
                // Réorganiser les lignes
                rows.forEach(row => {
                    tbody.appendChild(row);
                });
            }
            
            function showDeleteModal(id = null, bulk = false) {
                currentDeleteId = id;
                isBulkDelete = bulk;
                
                if (bulk) {
                    deleteMessage.textContent = `Êtes-vous sûr de vouloir supprimer les ${selectedIds.length} diagnostics sélectionnés ? Cette action est irréversible.`;
                } else {
                    deleteMessage.textContent = 'Êtes-vous sûr de vouloir supprimer ce diagnostic ? Cette action est irréversible.';
                }
                
                deleteModal.classList.remove('hidden');
            }
            
            function hideDeleteModal() {
                deleteModal.classList.add('hidden');
                currentDeleteId = null;
                isBulkDelete = false;
            }
            
            function performDelete() {
                if (isBulkDelete) {
                    // Suppression en masse
                    selectedIdsInput.value = JSON.stringify(selectedIds);
                    bulkDeleteForm.submit();
                } else {
                    // Suppression individuelle
                    fetch(`/diagnostics/${currentDeleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Supprimer la ligne du tableau
                            document.querySelector(`.diagnostic-row[data-id="${currentDeleteId}"]`).remove();
                            
                            // Vérifier si le tableau est vide
                            if (document.querySelectorAll('.diagnostic-row:not(.hidden)').length === 0) {
                                diagnosticsTable.classList.add('hidden');
                                document.getElementById('empty-state').classList.remove('hidden');
                            }
                        } else {
                            console.error('Erreur lors de la suppression');
                            alert('Une erreur est survenue lors de la suppression du diagnostic.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la suppression du diagnostic.');
                    })
                    .finally(() => {
                        hideDeleteModal();
                    });
                }
            }
            
            // Gestionnaires d'événements
            
            // Sélectionner/désélectionner tous
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                
                diagnosticCheckboxes.forEach(checkbox => {
                    if (!checkbox.closest('tr').classList.contains('hidden')) {
                        checkbox.checked = isChecked;
                        
                        const id = parseInt(checkbox.dataset.id);
                        const index = selectedIds.indexOf(id);
                        
                        if (isChecked && index === -1) {
                            selectedIds.push(id);
                        } else if (!isChecked && index !== -1) {
                            selectedIds.splice(index, 1);
                        }
                    }
                });
                
                updateBulkDeleteButton();
            });
            
            // Sélection individuelle
            diagnosticCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const id = parseInt(this.dataset.id);
                    const index = selectedIds.indexOf(id);
                    
                    if (this.checked && index === -1) {
                        selectedIds.push(id);
                    } else if (!this.checked && index !== -1) {
                        selectedIds.splice(index, 1);
                    }
                    
                    updateBulkDeleteButton();
                    updateSelectAllCheckbox();
                });
            });
            
            // Filtres
            dateFromInput.addEventListener('change', applyFilters);
            dateToInput.addEventListener('change', applyFilters);
            rabbitFilter.addEventListener('change', applyFilters);
            searchFilter.addEventListener('input', applyFilters);
            
            // Tri des colonnes
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.dataset.sort;
                    
                    if (sortState.column === column) {
                        // Inverser la direction si on clique sur la même colonne
                        sortState.direction = sortState.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        // Nouvelle colonne, trier par défaut en ordre descendant
                        sortState.column = column;
                        sortState.direction = 'desc';
                    }
                    
                    // Mettre à jour l'interface pour montrer la colonne et la direction de tri
                    sortableHeaders.forEach(h => {
                        const icon = h.querySelector('svg');
                        if (h.dataset.sort === sortState.column) {
                            icon.classList.add('text-indigo-500');
                            icon.classList.remove('text-gray-400');
                            
                            if (sortState.direction === 'asc') {
                                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>';
                            } else {
                                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>';
                            }
                        } else {
                            icon.classList.remove('text-indigo-500');
                            icon.classList.add('text-gray-400');
                            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>';
                        }
                    });
                    
                    sortTable();
                });
            });
            
            // Suppression individuelle
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    showDeleteModal(id, false);
                });
            });
            
            // Suppression en masse
            bulkDeleteBtn.addEventListener('click', function() {
                if (selectedIds.length > 0) {
                    showDeleteModal(null, true);
                }
            });
            
            // Confirmation de suppression
            confirmDeleteBtn.addEventListener('click', performDelete);
            
            // Annulation de suppression
            cancelDeleteBtn.addEventListener('click', hideDeleteModal);
            
            // Initialisation
            updateBulkDeleteButton();
        });
    </script>
</x-app-layout>