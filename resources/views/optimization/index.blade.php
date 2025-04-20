<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Optimisation de la Production') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques de production -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques de production</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-indigo-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-indigo-600 font-medium">Total lapereaux (12 mois)</p>
                            <p class="text-3xl font-bold text-indigo-800">{{ $stats['total_kits'] }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-green-600 font-medium">Lapereaux par portée</p>
                            <p class="text-3xl font-bold text-green-800">{{ $stats['avg_kits_per_litter'] }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-yellow-600 font-medium">Jours d'engraissement</p>
                            <p class="text-3xl font-bold text-yellow-800">{{ $stats['avg_fattening_days'] }}</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-red-600 font-medium">Taux de réussite</p>
                            <p class="text-3xl font-bold text-red-800">{{ $stats['breeding_success_rate'] }}%</p>
                        </div>
                    </div>
                    
                    <h4 class="text-md font-medium text-gray-700 mb-2">Production mensuelle (12 derniers mois)</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <canvas id="monthlyOutputChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Recommandations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recommandations pour augmenter la production</h3>
                    
                    @if(count($recommendations) > 0)
                        <div class="space-y-4">
                            @foreach($recommendations as $recommendation)
                                <div class="border rounded-lg overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
                                        <h4 class="font-medium text-gray-800">{{ $recommendation['title'] }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($recommendation['impact'] == 'Élevé') bg-red-100 text-red-800
                                            @elseif($recommendation['impact'] == 'Moyen') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            Impact: {{ $recommendation['impact'] }}
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-gray-700 mb-3">{{ $recommendation['description'] }}</p>
                                        <div class="bg-blue-50 p-3 rounded-md">
                                            <p class="text-blue-800 text-sm font-medium">Action recommandée:</p>
                                            <p class="text-blue-700">{{ $recommendation['action'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Aucune recommandation disponible pour le moment.</p>
                    @endif
                </div>
            </div>
            
            <!-- Simulateur de production -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Simulateur de production</h3>
                    
                    <form id="productionSimulator" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                            <div>
                                <label for="breeding_does" class="block text-sm font-medium text-gray-700 mb-1">Nombre de lapines</label>
                                <input type="number" id="breeding_does" name="breeding_does" min="1" value="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="litters_per_year" class="block text-sm font-medium text-gray-700 mb-1">Portées par an</label>
                                <input type="number" id="litters_per_year" name="litters_per_year" min="1" max="8" step="0.5" value="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="kits_per_litter" class="block text-sm font-medium text-gray-700 mb-1">Lapereaux par portée</label>
                                <input type="number" id="kits_per_litter" name="kits_per_litter" min="1" step="0.5" value="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="survival_rate" class="block text-sm font-medium text-gray-700 mb-1">Taux de survie (%)</label>
                                <input type="number" id="survival_rate" name="survival_rate" min="1" max="100" value="85" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="fattening_days" class="block text-sm font-medium text-gray-700 mb-1">Jours d'engraissement</label>
                                <input type="number" id="fattening_days" name="fattening_days" min="20" value="35" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Calculer la production
                        </button>
                    </form>
                    
                    <div id="simulationResults" class="hidden">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Résultats de la simulation</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm text-indigo-600 font-medium">Production mensuelle</p>
                                <p class="text-3xl font-bold text-indigo-800" id="monthly_production">-</p>
                                <p class="text-xs text-indigo-500">lapereaux par mois</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-600 font-medium">Production annuelle</p>
                                <p class="text-3xl font-bold text-green-800" id="annual_production">-</p>
                                <p class="text-xs text-green-500">lapereaux par an</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm text-purple-600 font-medium">Cages nécessaires</p>
                                <p class="text-3xl font-bold text-purple-800" id="total_cages_needed">-</p>
                                <p class="text-xs text-purple-500"><span id="breeding_cages_needed">-</span> reproduction + <span id="fattening_cages_needed">-</span> engraissement</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <p class="text-sm text-yellow-600 font-medium">Aliment mensuel</p>
                                <p class="text-3xl font-bold text-yellow-800" id="monthly_feed_kg">-</p>
                                <p class="text-xs text-yellow-500">kg d'aliment par mois</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4">
                                <p class="text-sm text-red-600 font-medium">Espace requis</p>
                                <p class="text-3xl font-bold text-red-800" id="space_required_m2">-</p>
                                <p class="text-xs text-red-500">m² d'espace total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Projections mensuelles -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Projections de production (6 prochains mois)</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <canvas id="projectionsChart" height="100"></canvas>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Production actuelle</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Production optimisée</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Différence</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Augmentation</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($monthlyProjections as $month => $projection)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $month }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $projection['baseline'] }} lapereaux</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $projection['optimized'] }} lapereaux</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+{{ $projection['difference'] }} lapereaux</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                +{{ $projection['percentage_increase'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Performance des lapines -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Performance des lapines reproductrices</h3>
                    
                    @if(count($doePerformance) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapine</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Portées</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total lapereaux</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapereaux sevrés</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne par portée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de survie</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($doePerformance as $performance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $performance['doe']->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ID: {{ $performance['doe']->id }} | Âge: {{ $performance['doe']->birth_date ? $performance['doe']->birth_date->diffInMonths(now()) : 'N/A' }} mois
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $performance['total_litters'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $performance['total_kits'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $performance['weaned_kits'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $performance['avg_kits_per_litter'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($performance['survival_rate'] >= 90) bg-green-100 text-green-800
                                                    @elseif($performance['survival_rate'] >= 75) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $performance['survival_rate'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Aucune donnée de performance disponible.</p>
                    @endif
                </div>
            </div>
            
            <!-- Utilisation des cages -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisation des cages</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-indigo-600 font-medium">Capacité totale</p>
                            <p class="text-3xl font-bold text-indigo-800">{{ $cageUtilization['total_capacity'] }}</p>
                            <p class="text-xs text-indigo-500">emplacements</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-green-600 font-medium">Occupés</p>
                            <p class="text-3xl font-bold text-green-800">{{ $cageUtilization['total_occupied'] }}</p>
                            <p class="text-xs text-green-500">emplacements utilisés</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-yellow-600 font-medium">Taux d'utilisation</p>
                            <p class="text-3xl font-bold text-yellow-800">{{ round($cageUtilization['utilization_rate']) }}%</p>
                            <p class="text-xs text-yellow-500">{{ $cageUtilization['available_space'] }} emplacements disponibles</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <canvas id="cageUtilizationChart" height="100"></canvas>
                    </div>
                    
                    @if(count($cageUtilization['cages']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacité</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupé</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisation</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cageUtilization['cages'] as $cage)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $cage['name'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $cage['capacity'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $cage['occupied'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $cage['available'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="h-2.5 rounded-full 
                                                        @if($cage['utilization_rate'] > 90) bg-red-600
                                                        @elseif($cage['utilization_rate'] > 70) bg-yellow-600
                                                        @else bg-green-600 @endif" 
                                                        style="width: {{ min($cage['utilization_rate'], 100) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500 mt-1 block">{{ round($cage['utilization_rate']) }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Aucune donnée de cage disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique de production mensuelle
            const monthlyOutputCtx = document.getElementById('monthlyOutputChart').getContext('2d');
            const monthlyOutputData = @json($stats['monthly_output']);
            
            new Chart(monthlyOutputCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(monthlyOutputData),
                    datasets: [{
                        label: 'Lapereaux produits',
                        data: Object.values(monthlyOutputData),
                        backgroundColor: 'rgba(79, 70, 229, 0.6)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de lapereaux'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mois'
                            }
                        }
                    }
                }
            });
            
            // Graphique de projections
            const projectionsCtx = document.getElementById('projectionsChart').getContext('2d');
            const projectionsData = @json($monthlyProjections);
            
            new Chart(projectionsCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(projectionsData),
                    datasets: [
                        {
                            label: 'Production actuelle',
                            data: Object.values(projectionsData).map(item => item.baseline),
                            backgroundColor: 'rgba(107, 114, 128, 0.2)',
                            borderColor: 'rgba(107, 114, 128, 1)',
                            borderWidth: 2,
                            tension: 0.1
                        },
                        {
                            label: 'Production optimisée',
                            data: Object.values(projectionsData).map(item => item.optimized),
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 2,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de lapereaux'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mois'
                            }
                        }
                    }
                }
            });
            
            // Graphique d'utilisation des cages
            const cageUtilizationCtx = document.getElementById('cageUtilizationChart').getContext('2d');
            const cageData = @json($cageUtilization['cages']);
            
            new Chart(cageUtilizationCtx, {
                type: 'bar',
                data: {
                    labels: cageData.map(cage => cage.name),
                    datasets: [
                        {
                            label: 'Occupé',
                            data: cageData.map(cage => cage.occupied),
                            backgroundColor: 'rgba(79, 70, 229, 0.6)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Disponible',
                            data: cageData.map(cage => cage.available),
                            backgroundColor: 'rgba(209, 213, 219, 0.6)',
                            borderColor: 'rgba(209, 213, 219, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Nombre d\'emplacements'
                            }
                        },
                        x: {
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Cages'
                            }
                        }
                    }
                }
            });
            
            // Simulateur de production
            const productionSimulator = document.getElementById('productionSimulator');
            const simulationResults = document.getElementById('simulationResults');
            
            productionSimulator.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(productionSimulator);
                const data = {
                    breeding_does: parseInt(formData.get('breeding_does')),
                    litters_per_year: parseFloat(formData.get('litters_per_year')),
                    kits_per_litter: parseFloat(formData.get('kits_per_litter')),
                    survival_rate: parseFloat(formData.get('survival_rate')),
                    fattening_days: parseInt(formData.get('fattening_days'))
                };
                
                fetch('{{ route("optimization.simulate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    // Afficher les résultats
                    document.getElementById('monthly_production').textContent = result.monthly_production;
                    document.getElementById('annual_production').textContent = result.annual_production;
                    document.getElementById('total_cages_needed').textContent = result.total_cages_needed;
                    document.getElementById('breeding_cages_needed').textContent = result.breeding_cages_needed;
                    document.getElementById('fattening_cages_needed').textContent = result.fattening_cages_needed;
                    document.getElementById('monthly_feed_kg').textContent = result.monthly_feed_kg;
                    document.getElementById('space_required_m2').textContent = result.space_required_m2;
                    
                    // Afficher la section des résultats
                    simulationResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la simulation.');
                });
            });
        });
    </script>
</x-app-layout>