<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Traitements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filtres</h3>
                    
                    <form action="{{ route('treatments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complétés</option>
                                <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Ignorés</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>En retard</option>
                                <option value="today" {{ request('status') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="rabbit_id" class="block text-sm font-medium text-gray-700 mb-1">Lapin</label>
                            <select id="rabbit_id" name="rabbit_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Tous les lapins</option>
                                @foreach($rabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}" {{ request('rabbit_id') == $rabbit->id ? 'selected' : '' }}>
                                        {{ $rabbit->name }} ({{ $rabbit->tattoo_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="medication_id" class="block text-sm font-medium text-gray-700 mb-1">Médicament</label>
                            <select id="medication_id" name="medication_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Tous les médicaments</option>
                                @foreach($medications as $medication)
                                    <option value="{{ $medication->id }}" {{ request('medication_id') == $medication->id ? 'selected' : '' }}>
                                        {{ $medication->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                            <select id="date_range" name="date_range" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Toutes les dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                <option value="tomorrow" {{ request('date_range') == 'tomorrow' ? 'selected' : '' }}>Demain</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>Cette semaine</option>
                                <option value="next_week" {{ request('date_range') == 'next_week' ? 'selected' : '' }}>Semaine prochaine</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Ce mois</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Filtrer
                            </button>
                            <a href="{{ route('treatments.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des traitements</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('treatments.calendar') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Calendrier
                            </a>
                            <a href="{{ route('treatments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nouveau traitement
                            </a>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-blue-500 text-lg font-bold">{{ $stats['pending'] }}</div>
                            <div class="text-sm text-gray-600">Traitements en attente</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-green-500 text-lg font-bold">{{ $stats['completed'] }}</div>
                            <div class="text-sm text-gray-600">Traitements complétés</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="text-yellow-500 text-lg font-bold">{{ $stats['today'] }}</div>
                            <div class="text-sm text-gray-600">Traitements aujourd'hui</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="text-red-500 text-lg font-bold">{{ $stats['overdue'] }}</div>
                            <div class="text-sm text-gray-600">Traitements en retard</div>
                        </div>
                    </div>

                    @if ($treatments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médicament</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date prévue</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($treatments as $treatment)
                                        <tr class="{{ $treatment->scheduled_at->isPast() && $treatment->status === 'pending' ? 'bg-red-50' : '' }} {{ $treatment->scheduled_at->isToday() && $treatment->status === 'pending' ? 'bg-green-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $treatment->rabbit->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $treatment->rabbit->tattoo_number }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $treatment->medication->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $treatment->medication->dosage }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $treatment->scheduled_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @if ($treatment->scheduled_at->isPast() && $treatment->status === 'pending')
                                                        <span class="text-red-500">En retard ({{ $treatment->scheduled_at->diffForHumans() }})</span>
                                                    @elseif ($treatment->scheduled_at->isToday() && $treatment->status === 'pending')
                                                        <span class="text-green-500">Aujourd'hui</span>
                                                    @elseif ($treatment->status === 'pending')
                                                        Dans {{ $treatment->scheduled_at->diffForHumans() }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($treatment->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        En attente
                                                    </span>
                                                @elseif ($treatment->status === 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Complété {{ $treatment->completed_at ? '(' . $treatment->completed_at->format('d/m/Y') . ')' : '' }}
                                                    </span>
                                                @elseif ($treatment->status === 'skipped')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Ignoré
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $treatment->notes }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if ($treatment->status === 'pending')
                                                    <form action="{{ route('treatments.done', $treatment->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Compléter</button>
                                                    </form>
                                                    <form action="{{ route('treatments.skip', $treatment->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900 mr-2">Ignorer</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('treatments.edit', $treatment->id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $treatments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun traitement</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau traitement.</p>
                            <div class="mt-6">
                                <a href="{{ route('treatments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Nouveau traitement
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>