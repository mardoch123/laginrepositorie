<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Registre de mortalité') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres et bouton -->
            <div class="mb-6 flex flex-wrap gap-4 justify-between items-center">
                <div class="flex flex-wrap gap-3">
                    <input type="month" id="month-filter" 
                           class="w-48 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm transition-colors">

                    <select id="cause-filter" 
                            class="w-48 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm">
                        <option value="">Toutes les causes</option>
                        <option value="illness">Maladie</option>
                        <option value="accident">Accident</option>
                        <option value="predator">Prédateur</option>
                        <option value="slaughter">Abattage</option>
                        <option value="old_age">Vieillesse</option>
                        <option value="birth_complication">Complication à la naissance</option>
                        <option value="unknown">Cause inconnue</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <a href="{{ route('health.mortality.create') }}" 
                   class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Déclarer un décès') }}
                </a>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto ring-1 ring-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Race
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Naissance
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Décès
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Âge
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cause
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($deadRabbits as $rabbit)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3.5 text-sm text-gray-600">
                                        #{{ $rabbit->id }}
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-gray-900">
                                        {{ $rabbit->name }}
                                    </td>
                                    <td class="px-4 py-3.5 text-sm text-gray-600">
                                        {{ $rabbit->breed->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-sm text-gray-500">
                                        {{ $rabbit->birth_date?->format('d/m/Y') ?? 'Inconnue' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-sm text-gray-500">
                                        {{ $rabbit->death_date?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-sm text-gray-500">
                                        @if($rabbit->birth_date && $rabbit->death_date)
                                            {{ $rabbit->birth_date->diffInDays($rabbit->death_date) }} jours
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @php
                                            $causeStyles = [
                                                'illness' => 'bg-red-100 text-red-800',
                                                'accident' => 'bg-amber-100 text-amber-800',
                                                'predator' => 'bg-orange-100 text-orange-800',
                                                'slaughter' => 'bg-gray-100 text-gray-800',
                                                'old_age' => 'bg-blue-100 text-blue-800',
                                                'birth_complication' => 'bg-pink-100 text-pink-800',
                                                'unknown' => 'bg-gray-100 text-gray-800',
                                                'other' => 'bg-gray-100 text-gray-800'
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $causeStyles[$rabbit->death_cause] ?? 'bg-gray-100' }}">
                                            {{ $causes[$rabbit->death_cause] ?? $rabbit->death_cause }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <a href="{{ route('rabbits.show', $rabbit) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-sm text-gray-500 text-center">
                                        Aucun décès enregistré
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($deadRabbits->hasPages())
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        {{ $deadRabbits->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>