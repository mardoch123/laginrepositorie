<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la maladie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <a href="{{ route('health.illness.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Retour à la liste
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="mb-2"><span class="font-medium">Lapin:</span> {{ $illness->rabbit->name }}</p>
                                <p class="mb-2"><span class="font-medium">Type de maladie:</span> {{ $illnessTypes[$illness->type] ?? $illness->type }}</p>
                                <p class="mb-2"><span class="font-medium">Sévérité:</span> 
                                    @if($illness->severity == 'mild')
                                        <span class="text-green-600">Légère</span>
                                    @elseif($illness->severity == 'moderate')
                                        <span class="text-yellow-600">Modérée</span>
                                    @else
                                        <span class="text-red-600">Sévère</span>
                                    @endif
                                </p>
                                <p class="mb-2"><span class="font-medium">Date de détection:</span> {{ \Carbon\Carbon::parse($illness->detection_date)->format('d/m/Y') }}</p>
                                <p class="mb-2"><span class="font-medium">Statut:</span> 
                                    @if($illness->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Active
                                        </span>
                                    @elseif($illness->status == 'recovered')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Guérie
                                        </span>
                                    @elseif($illness->status == 'chronic')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Chronique
                                        </span>
                                    @elseif($illness->status == 'fatal')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Fatale
                                        </span>
                                    @endif
                                </p>
                                @if($illness->status == 'recovered' && $illness->recovery_date)
                                    <p class="mb-2"><span class="font-medium">Date de guérison:</span> {{ \Carbon\Carbon::parse($illness->recovery_date)->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Symptômes</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                @php
                                    $symptomsList = json_decode($illness->symptoms, true);
                                    if (!is_array($symptomsList)) {
                                        $symptomsList = [$illness->symptoms];
                                    }
                                @endphp
                                
                                <ul class="list-disc pl-5">
                                    @foreach($symptomsList as $symptomKey)
                                        <li>{{ $symptoms[$symptomKey] ?? $symptomKey }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($illness->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p>{{ $illness->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($treatments->count() > 0)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Traitements associés</h3>
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($treatments as $treatment)
                                        <li>
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                                        Traitement #{{ $treatment->id }}
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $treatment->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $treatment->status == 'active' ? 'En cours' : 'Terminé' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500">
                                                            {{ $treatment->description }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <p>
                                                            Début: {{ \Carbon\Carbon::parse($treatment->start_date)->format('d/m/Y') }}
                                                            @if($treatment->end_date)
                                                                | Fin: {{ \Carbon\Carbon::parse($treatment->end_date)->format('d/m/Y') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('health.illness.edit', $illness) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            Modifier
                        </a>
                        <form action="{{ route('health.illness.destroy', $illness) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maladie?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>