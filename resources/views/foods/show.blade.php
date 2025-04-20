<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: 15px;">
                {{ __('Détails de la nourriture') }}
            </h2>
            <div>
                <a href="{{ route('foods.edit', $food->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('foods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Nom</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $food->name }}</p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Fréquence</h4>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @switch($food->frequency)
                                            @case('daily')
                                                Quotidienne
                                                @break
                                            @case('alternate_days')
                                                Jours alternés
                                                @break
                                            @case('weekly')
                                                Hebdomadaire
                                                @break
                                            @case('weekdays')
                                                Jours de semaine
                                                @break
                                            @case('weekends')
                                                Week-ends
                                                @break
                                            @default
                                                {{ $food->frequency }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Quantité par lapin</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $food->quantity_per_rabbit }} {{ $food->unit }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Statut</h4>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $food->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $food->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Détails supplémentaires</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Description</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $food->description ?: 'Aucune description' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $food->notes ?: 'Aucune note' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($food->schedules->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des distributions</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($food->schedules->sortByDesc('scheduled_date') as $schedule)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $schedule->scheduled_date->format('d/m/Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $schedule->scheduled_date->locale('fr')->isoFormat('dddd') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $schedule->quantity }} {{ $schedule->unit }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $schedule->is_completed ? 'Distribué' : 'En attente' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if(!$schedule->is_completed)
                                                        <form method="POST" action="{{ route('food-schedules.complete', $schedule->id) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900">Marquer comme distribué</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">Distribué le {{ $schedule->completed_at->format('d/m/Y à H:i') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>