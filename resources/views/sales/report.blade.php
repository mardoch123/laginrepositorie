<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapport des ventes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filtres -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Filtres</h3>
                        <form action="{{ route('sales.report') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filtrer
                                </button>
                                <a href="{{ route('sales.report') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Résumé des ventes -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800">Total des ventes</h3>
                            <p class="text-2xl font-bold">{{ $totalSales }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800">Chiffre d'affaires</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalRevenue, 2) }} F</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-yellow-800">Poids total vendu</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalWeight, 2) }} kg</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-800">Prix moyen/kg</h3>
                            <p class="text-2xl font-bold">{{ number_format($averagePricePerKg, 2) }} F</p>
                        </div>
                    </div>

                    <!-- Graphique des ventes -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Évolution des ventes</h3>
                        <div class="bg-white p-4 rounded-lg border border-gray-200" style="height: 400px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Ventes par type -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Ventes par type</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de vente</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiffre d'affaires</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% du total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByType as $type)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if($type->sale_type == 'individual')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Individuelle
                                                    </span>
                                                @elseif($type->sale_type == 'group')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        Groupe
                                                    </span>
                                                @elseif($type->sale_type == 'breeding')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Portée
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $type->count }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($type->revenue, 2) }} F</td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                {{ $totalRevenue > 0 ? number_format(($type->revenue / $totalRevenue) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ventes par mois -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Ventes par mois</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de ventes</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids total (kg)</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiffre d'affaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByMonth as $month)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                {{ \Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('F Y') }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $month->count }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($month->weight, 2) }} kg</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($month->revenue, 2) }} F</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Chiffre d\'affaires (F)',
                            data: {!! json_encode($chartRevenue) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Poids vendu (kg)',
                            data: {!! json_encode($chartWeight) !!},
                            backgroundColor: 'rgba(255, 159, 64, 0.5)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1,
                            type: 'line',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Chiffre d\'affaires (F)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Poids (kg)'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>