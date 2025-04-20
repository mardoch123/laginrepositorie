<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Diagnostic de santé') }} #{{ $diagnostic->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('diagnostics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('diagnostics.print', $diagnostic) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Imprimer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Informations sur le lapin -->
                        <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informations sur le lapin
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Nom:</span>
                                    <span class="text-sm text-gray-900">{{ $rabbit->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">ID:</span>
                                    <span class="text-sm text-gray-900">{{ $rabbit->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Race:</span>
                                    <span class="text-sm text-gray-900">{{ $rabbit->breed }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Sexe:</span>
                                    <span class="text-sm text-gray-900">{{ $rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Âge:</span>
                                    <span class="text-sm text-gray-900">{{ $rabbit->age }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Poids:</span>
                                    <span class="text-sm text-gray-900">{{ $diagnostic->weight ?? $rabbit->weight ?? 'Non spécifié' }} kg</span>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('rabbits.show', $rabbit) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Voir la fiche complète →</a>
                                </div>
                            </div>
                        </div>

                        <!-- Détails du diagnostic -->
                        <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Détails du diagnostic
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Date d'observation:</span>
                                    <span class="text-sm text-gray-900">{{ $diagnostic->observed_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Température:</span>
                                    <span class="text-sm text-gray-900">{{ $diagnostic->temperature ? $diagnostic->temperature . '°C' : 'Non spécifiée' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Appétit:</span>
                                    <span class="text-sm text-gray-900">
                                        @if($diagnostic->appetite_level == 'normal')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Normal</span>
                                        @elseif($diagnostic->appetite_level == 'reduced')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Réduit</span>
                                        @elseif($diagnostic->appetite_level == 'none')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Aucun</span>
                                        @else
                                            Non spécifié
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Activité:</span>
                                    <span class="text-sm text-gray-900">
                                        @if($diagnostic->activity_level == 'normal')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Normale</span>
                                        @elseif($diagnostic->activity_level == 'reduced')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Réduite</span>
                                        @elseif($diagnostic->activity_level == 'lethargic')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Léthargique</span>
                                        @else
                                            Non spécifiée
                                        @endif
                                    </span>
                                </div>
                                <div class="pt-2">
                                    <span class="text-sm font-medium text-gray-500">Symptômes:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ $diagnostic->symptoms }}</p>
                                </div>
                                @if($diagnostic->additional_notes)
                                <div class="pt-2">
                                    <span class="text-sm font-medium text-gray-500">Notes supplémentaires:</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ $diagnostic->additional_notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Historique de santé -->
                        <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Historique de santé
                            </h3>
                            @if($healthHistory->isEmpty())
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">Aucun antécédent médical enregistré.</p>
                                </div>
                            @else
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                    @foreach($healthHistory as $record)
                                        <div class="bg-white p-3 rounded-md shadow-sm">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-gray-500">{{ $record->date->format('d/m/Y') }}</span>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $record->condition }}</span>
                                            </div>
                                            @if($record->treatment)
                                                <p class="text-xs text-gray-700"><span class="font-medium">Traitement:</span> {{ $record->treatment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnostic IA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Diagnostic assisté par IA
                    </h3>

                    @if(!$diagnostic->ai_diagnosis)
                        <div class="flex items-center justify-center p-6 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Diagnostic en cours de génération</h3>
                                <p class="mt-1 text-sm text-gray-500">Veuillez patienter pendant que l'IA analyse les données.</p>
                            </div>
                        </div>
                    @elseif(strpos($diagnostic->ai_diagnosis, 'Erreur') === 0)
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ $diagnostic->ai_diagnosis }}</h3>
                            </div>
                        </div>
                    @else
                        <div class="prose prose-indigo max-w-none">
                            {!! Illuminate\Support\Str::markdown(e($diagnostic->ai_diagnosis)) !!}
                        </div>
                    @endif

                    @if($diagnostic->veterinarian_notes)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Notes du vétérinaire</h4>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-800">{{ $diagnostic->veterinarian_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($diagnostic->treatment_plan)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Plan de traitement</h4>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-800">{{ $diagnostic->treatment_plan }}</p>
                            </div>
                        </div>
                    @endif

                    @if($diagnostic->follow_up_date)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Suivi prévu</h4>
                            <div class="bg-blue-50 p-4 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-800">Date de suivi: <span class="font-medium">{{ $diagnostic->follow_up_date->format('d/m/Y') }}</span></p>
                            </div>
                        </div>
                    @endif

                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Ajouter des notes vétérinaires</h4>
                            <form action="{{ route('diagnostics.update', $diagnostic) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="veterinarian_notes" class="block text-sm font-medium text-gray-700">Notes du vétérinaire</label>
                                        <textarea id="veterinarian_notes" name="veterinarian_notes" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $diagnostic->veterinarian_notes }}</textarea>
                                    </div>
                                    <div>
                                        <label for="treatment_plan" class="block text-sm font-medium text-gray-700">Plan de traitement</label>
                                        <textarea id="treatment_plan" name="treatment_plan" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $diagnostic->treatment_plan }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="follow_up_date" class="block text-sm font-medium text-gray-700">Date de suivi</label>
                                    <input type="date" id="follow_up_date" name="follow_up_date" value="{{ $diagnostic->follow_up_date ? $diagnostic->follow_up_date->format('Y-m-d') : '' }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div class="mt-6">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>