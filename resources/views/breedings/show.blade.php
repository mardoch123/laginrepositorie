<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Détails de la portée
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('breedings.edit', $breeding) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                            <a href="{{ route('breedings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Retour
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 text-indigo-600">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Statut: {{ $breeding->status_label }}</h3>
                                <p class="text-sm text-gray-500">
                                    @if($breeding->status === 'in_progress')
                                        Naissance prévue dans {{ now()->diffInDays($breeding->expected_birth_date) }} jours
                                    @elseif($breeding->status === 'imminent')
                                        Naissance imminente (dans moins de 3 jours)
                                    @elseif($breeding->status === 'overdue')
                                        La naissance est en retard de {{ $breeding->expected_birth_date->diffInDays(now()) }} jours
                                    @elseif($breeding->status === 'completed')
                                        Portée terminée avec succès
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Informations sur les parents
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Mère
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->mother->name }} (ID: {{ $breeding->mother->identification_number }})
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Père
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->father->name }} (ID: {{ $breeding->father->identification_number }})
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Informations sur la portée
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Date d'accouplement
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->mating_date->format('d/m/Y') }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Date prévue de naissance
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->expected_birth_date ? $breeding->expected_birth_date->format('d/m/Y') : 'Non définie' }}
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Date réelle de naissance
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->actual_birth_date ? $breeding->actual_birth_date->format('d/m/Y') : 'Non définie' }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Nombre de petits
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $breeding->number_of_kits ?? 'Non défini' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    @if($breeding->notes)
                        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Notes
                                </h3>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                                <p class="text-sm text-gray-900">
                                    {{ $breeding->notes }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>