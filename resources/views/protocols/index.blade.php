<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Protocoles de traitement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Protocoles disponibles</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('treatments.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Nouveau traitement
                            </a>
                            <a href="{{ route('protocols.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Appliquer un protocole
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($protocols as $protocol)
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{ $protocol['name'] }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ $protocol['description'] }}</p>
                                <div class="text-xs text-gray-500">
                                    <p class="font-semibold mb-1">Traitements inclus:</p>
                                    <ul class="list-disc pl-5">
                                        @foreach ($protocol['treatments'] as $treatment)
                                            <li>{{ $treatment['medication'] }} (Jour {{ $treatment['days_after'] }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('protocols.show', ['name' => $protocol['name']]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Voir les détails
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Traitements en cours</h3>

                    @if ($activeProtocols->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médicament</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date prévue</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($activeProtocols as $rabbitId => $treatments)
                                        @foreach ($treatments as $treatment)
                                            <tr>
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
                                                        @if ($treatment->scheduled_at->isPast())
                                                            <span class="text-red-500">En retard</span>
                                                        @elseif ($treatment->scheduled_at->isToday())
                                                            <span class="text-green-500">Aujourd'hui</span>
                                                        @else
                                                            Dans {{ $treatment->scheduled_at->diffForHumans() }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ $treatment->notes }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('protocols.complete', $treatment->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Compléter</button>
                                                    </form>
                                                    <form action="{{ route('protocols.cancel', $treatment->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun traitement en cours</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par appliquer un protocole à un lapin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>