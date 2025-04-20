<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registre des maladies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <div>
                        <input type="month" id="month-filter" class="block" placeholder="Filtrer par mois" />
                    </div>
                    <div>
                        <select id="type-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="coccidiosis">Coccidiose</option>
                            <option value="pasteurellosis">Pasteurellose</option>
                            <option value="myxomatosis">Myxomatose</option>
                            <option value="vhd">Maladie hémorragique virale</option>
                            <option value="ear_mites">Gale des oreilles</option>
                            <option value="diarrhea">Diarrhée</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                </div>
                <div>
                    <a href="{{ route('health.illness.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        {{ __('Déclarer une maladie') }}
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lapin
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sévérité
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date de détection
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date de guérison
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($illnesses as $illness)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('rabbits.show', $illness->rabbit) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $illness->rabbit->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $illness->rabbit->tattoo_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($illness->type == 'coccidiosis') bg-red-100 text-red-800 
                                            @elseif($illness->type == 'pasteurellosis') bg-orange-100 text-orange-800 
                                            @elseif($illness->type == 'myxomatosis') bg-purple-100 text-purple-800 
                                            @elseif($illness->type == 'vhd') bg-yellow-100 text-yellow-800 
                                            @elseif($illness->type == 'ear_mites') bg-blue-100 text-blue-800 
                                            @elseif($illness->type == 'diarrhea') bg-green-100 text-green-800 
                                            @else bg-gray-100 text-gray-800 
                                            @endif">
                                            @php
                                                $types = [
                                                    'coccidiosis' => 'Coccidiose',
                                                    'pasteurellosis' => 'Pasteurellose',
                                                    'myxomatosis' => 'Myxomatose',
                                                    'vhd' => 'Maladie hémorragique virale',
                                                    'ear_mites' => 'Gale des oreilles',
                                                    'diarrhea' => 'Diarrhée',
                                                    'other' => 'Autre'
                                                ];
                                            @endphp
                                            {{ $types[$illness->type] ?? $illness->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($illness->severity == 'mild') bg-green-100 text-green-800 
                                            @elseif($illness->severity == 'moderate') bg-yellow-100 text-yellow-800 
                                            @elseif($illness->severity == 'severe') bg-red-100 text-red-800 
                                            @else bg-gray-100 text-gray-800 
                                            @endif">
                                            @php
                                                $severities = [
                                                    'mild' => 'Légère',
                                                    'moderate' => 'Modérée',
                                                    'severe' => 'Sévère'
                                                ];
                                            @endphp
                                            {{ $severities[$illness->severity] ?? $illness->severity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $illness->detection_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $illness->recovery_date ? $illness->recovery_date->format('d/m/Y') : 'En cours' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($illness->status == 'active') bg-red-100 text-red-800 
                                            @elseif($illness->status == 'recovered') bg-green-100 text-green-800 
                                            @elseif($illness->status == 'fatal') bg-gray-100 text-gray-800 
                                            @else bg-blue-100 text-blue-800 
                                            @endif">
                                            @php
                                                $statuses = [
                                                    'active' => 'Active',
                                                    'recovered' => 'Guéri',
                                                    'fatal' => 'Fatale',
                                                    'chronic' => 'Chronique'
                                                ];
                                            @endphp
                                            {{ $statuses[$illness->status] ?? $illness->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('health.illness.edit', $illness) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                        <a href="{{ route('health.illness.show', $illness) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Aucune maladie enregistrée
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $illnesses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>