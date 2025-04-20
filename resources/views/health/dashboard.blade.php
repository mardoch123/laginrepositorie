<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de bord de santé') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('health.mortality.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ __('Signaler un décès') }}
                </a>
                <a href="{{ route('health.illness.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Signaler une maladie') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-3 rounded-full mr-4">
                                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Total des lapins</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalRabbits }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Lapins vivants</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $aliveRabbits }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-full mr-4">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Lapins décédés</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $deadRabbits }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Taux de mortalité</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $mortalityRate }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
                       <!-- Statistiques de santé -->
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Graphique de mortalité par mois -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mortalité par mois</h3>
                        <div class="h-64">
                            <canvas id="mortalityChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Graphique des maladies par type -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Maladies par type</h3>
                        <div class="h-64">
                            <canvas id="illnessChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Maladies actives et traitements -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Maladies actives -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Maladies actives</h3>
                            <a href="{{ route('health.illness.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                        </div>
                        
                        @if($activeIllnesses == 0)
                            <div class="flex flex-col items-center justify-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600 text-lg font-medium">Aucune maladie active</p>
                                <p class="text-gray-500 text-sm mt-1 max-w-sm text-center">Tous vos lapins sont en bonne santé</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maladie</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sévérité</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détecté le</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(App\Models\Illness::where('status', 'active')->with('rabbit')->take(5)->get() as $illness)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $illness->rabbit->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        @php
                                                            $illnessTypes = [
                                                                'coccidiosis' => 'Coccidiose',
                                                                'pasteurellosis' => 'Pasteurellose',
                                                                'myxomatosis' => 'Myxomatose',
                                                                'vhd' => 'Maladie hémorragique virale (VHD)',
                                                                'ear_mites' => 'Gale des oreilles',
                                                                'fur_mites' => 'Gale du pelage',
                                                                'diarrhea' => 'Diarrhée',
                                                                'respiratory' => 'Problème respiratoire',
                                                                'eye_infection' => 'Infection oculaire',
                                                                'abscess' => 'Abcès',
                                                                'dental_problem' => 'Problème dentaire',
                                                                'digestive' => 'Trouble digestif',
                                                                'other' => 'Autre'
                                                            ];
                                                        @endphp
                                                        {{ $illnessTypes[$illness->type] ?? $illness->type }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($illness->severity == 'mild') bg-green-100 text-green-800 
                                                        @elseif($illness->severity == 'moderate') bg-yellow-100 text-yellow-800 
                                                        @elseif($illness->severity == 'severe') bg-red-100 text-red-800 
                                                        @endif">
                                                        @if($illness->severity == 'mild') Légère
                                                        @elseif($illness->severity == 'moderate') Modérée
                                                        @elseif($illness->severity == 'severe') Sévère
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $illness->detection_date->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('health.illness.edit', $illness) }}" class="text-indigo-600 hover:text-indigo-900">Mettre à jour</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Traitements actifs -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Traitements en cours</h3>
                            <a href="{{ route('treatments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                        </div>
                        
                        @php
                            $pendingTreatments = App\Models\Treatment::where('status', 'pending')
                                ->with('rabbit', 'medication')
                                ->orderBy('scheduled_at')
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @if($pendingTreatments->isEmpty())
                            <div class="flex flex-col items-center justify-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <p class="text-gray-600 text-lg font-medium">Aucun traitement en cours</p>
                                <p class="text-gray-500 text-sm mt-1 max-w-sm text-center">Tous les traitements sont terminés</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médicament</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programmé pour</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pendingTreatments as $treatment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $treatment->rabbit ? $treatment->rabbit->name : 'Portée #' . $treatment->breeding_id }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $treatment->medication->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $treatment->scheduled_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ Str::limit($treatment->notes, 30) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('treatments.edit', $treatment) }}" class="text-indigo-600 hover:text-indigo-900">Gérer</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Derniers décès -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Derniers décès</h3>
                        <a href="{{ route('health.mortality.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                    </div>
                    
                    @if($deadRabbits == 0)
                        <div class="flex flex-col items-center justify-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600 text-lg font-medium">Aucun décès enregistré</p>
                            <p class="text-gray-500 text-sm mt-1 max-w-sm text-center">Tous vos lapins sont en vie</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Race</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date du décès</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cause</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(App\Models\Rabbit::where('status', 'dead')->whereNotNull('death_date')->orderBy('death_date', 'desc')->take(5)->get() as $rabbit)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $rabbit->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $rabbit->breed ?? 'Non spécifiée' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $rabbit->death_date ? $rabbit->death_date->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($rabbit->death_cause == 'illness') bg-red-100 text-red-800 
                                                    @elseif($rabbit->death_cause == 'accident') bg-yellow-100 text-yellow-800 
                                                    @elseif($rabbit->death_cause == 'old_age') bg-blue-100 text-blue-800 
                                                    @else bg-gray-100 text-gray-800 
                                                    @endif">
                                                    @php
                                                        $causes = [
                                                            'illness' => 'Maladie',
                                                            'accident' => 'Accident',
                                                            'predator' => 'Prédateur',
                                                            'slaughter' => 'Abattage',
                                                            'old_age' => 'Vieillesse',
                                                            'birth_complication' => 'Complication à la naissance',
                                                            'unknown' => 'Cause inconnue',
                                                            'other' => 'Autre'
                                                        ];
                                                    @endphp
                                                    {{ $causes[$rabbit->death_cause] ?? $rabbit->death_cause }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('rabbits.show', $rabbit) }}" class="text-indigo-600 hover:text-indigo-900">Détails</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique de mortalité par mois
            const mortalityCtx = document.getElementById('mortalityChart').getContext('2d');
            const mortalityChart = new Chart(mortalityCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($mortalityByMonth, 'month')) !!},
                    datasets: [{
                        label: 'Décès',
                        data: {!! json_encode(array_column($mortalityByMonth, 'count')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Graphique des maladies par type
            const illnessData = {!! json_encode($illnessesByType) !!};
            const illnessCtx = document.getElementById('illnessChart').getContext('2d');
            const illnessChart = new Chart(illnessCtx, {
                type: 'doughnut',
                data: {
                    labels: illnessData.map(item => item.type),
                    datasets: [{
                        data: illnessData.map(item => item.count),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(236, 72, 153, 0.7)',
                            'rgba(6, 182, 212, 0.7)',
                            'rgba(132, 204, 22, 0.7)',
                            'rgba(249, 115, 22, 0.7)',
                            'rgba(168, 85, 247, 0.7)',
                            'rgba(234, 179, 8, 0.7)',
                            'rgba(75, 85, 99, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>