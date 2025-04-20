<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tableau de bord') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Rapports
                </a>
                <a href="{{ route('reports.generate-monthly') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Exporter PDF
                </a>
            </div>
        </div>
    </x-slot>



    <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900">Mode hors ligne</h2>
                    <p class="mt-2 text-gray-600">
                        Préparez votre application pour une utilisation sans connexion internet.
                    </p>
                    <a href="{{ route('offline.sync') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                        Gérer la synchronisation
                    </a>
                </div>
            </div>

            
    <!-- Notifications et rappels (version compacte) -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.reminders-list', ['reminders' => $activeReminders])
        </div>
    </div>
    
    <!-- KPIs principaux -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- KPI 1: Taux de survie des portées -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Taux de survie</p>
                                <p class="text-3xl font-bold text-indigo-700">{{ number_format($survivalRate, 1) }}%</p>
                                <p class="text-xs text-gray-500 mt-1">Moyenne sur 3 mois</p>
                            </div>
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $survivalRate }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- KPI 2: Coûts mensuels -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Coûts mensuels</p>
                                <p class="text-3xl font-bold text-emerald-700">{{ number_format($monthlyCosts, 0) }} F</p>
                                <p class="text-xs text-gray-500 mt-1">Estimation pour {{ now()->format('F Y') }}</p>
                            </div>
                            <div class="p-3 bg-emerald-100 rounded-full">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-emerald-600 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                {{ $costsTrend > 0 ? '+' : '' }}{{ number_format($costsTrend, 1) }}%
                            </span>
                            <span class="text-xs text-gray-500 ml-2">vs mois précédent</span>
                        </div>
                    </div>
                </div>
                
                <!-- KPI 3: Productivité -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-fuchsia-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Productivité</p>
                                <p class="text-3xl font-bold text-purple-700">{{ number_format($productivity, 1) }}</p>
                                <p class="text-xs text-gray-500 mt-1">Lapereaux/femelle/mois</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium {{ $productivityTrend >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $productivityTrend >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                </svg>
                                {{ $productivityTrend > 0 ? '+' : '' }}{{ number_format($productivityTrend, 1) }}%
                            </span>
                            <span class="text-xs text-gray-500 ml-2">vs trimestre précédent</span>
                        </div>
                    </div>
                </div>
                
                <!-- KPI 4: Santé globale -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Santé globale</p>
                                <p class="text-3xl font-bold text-amber-700">{{ number_format($healthIndex, 0) }}/100</p>
                                <p class="text-xs text-gray-500 mt-1">Basé sur {{ $treatmentsCount }} traitements</p>
                            </div>
                            <div class="p-3 bg-amber-100 rounded-full">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $healthIndex > 80 ? 'bg-green-500' : ($healthIndex > 60 ? 'bg-amber-500' : 'bg-red-500') }} rounded-full" style="width: {{ $healthIndex }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    <!-- Rapport financier -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Rapport financier ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                        <div class="bg-white p-4 rounded-lg border border-teal-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Total vendu</p>
                            <p class="text-3xl font-bold text-teal-700">{{ number_format($totalSalesWeight, 1) }} kg</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalSalesCount }} ventes</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-green-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Revenus</p>
                            <p class="text-3xl font-bold text-green-700">{{ number_format($totalRevenue, 0) }} F</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($averagePricePerKg, 0) }} F/kg</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-red-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Dépenses</p>
                            <p class="text-3xl font-bold text-red-700">{{ number_format($totalExpenses, 0) }} F</p>
                            <p class="text-xs text-gray-500 mt-1">Alimentation, soins, etc.</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-blue-200 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">Bénéfice net</p>
                            <p class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                {{ number_format($netProfit, 0) }} F
                            </p>
                            <div class="flex items-center mt-1">
                                <span class="text-xs font-medium {{ $profitMargin >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $profitMargin >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                    </svg>
                                    {{ number_format(abs($profitMargin), 1) }}%
                                </span>
                                <span class="text-xs text-gray-500 ml-2">marge</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('sales.report') }}" class="text-sm font-medium text-teal-600 hover:text-teal-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Voir le rapport financier détaillé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques et visualisations -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Graphique de croissance des lapins -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            Croissance des lapins (poids moyen)
                        </h3>
                        <div id="weight-chart" class="h-80"></div>
                    </div>
                </div>
                
                <!-- Heatmap des périodes de reproduction -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Périodes de reproduction réussies
                        </h3>
                        <div id="breeding-heatmap" class="h-80"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
           
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alerte pour les traitements en attente -->
            @if($pendingTreatments->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <span class="font-medium">Attention!</span> Vous avez {{ $pendingTreatments->count() }} traitement(s) à effectuer.
                                @if($pendingTreatments->where('scheduled_at', '<', now()->startOfDay())->count() > 0)
                                    <span class="font-medium text-red-600">{{ $pendingTreatments->where('scheduled_at', '<', now()->startOfDay())->count() }} en retard!</span>
                                @endif
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('treatments.index') }}" class="text-sm font-medium text-yellow-700 hover:text-yellow-600">
                                    Voir tous les traitements
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                                <a href="{{ route('treatments.calendar') }}" class="ml-4 text-sm font-medium text-yellow-700 hover:text-yellow-600">
                                    Voir le calendrier
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                            
                            <!-- Liste des traitements à effectuer aujourd'hui -->
                            @if($pendingTreatments->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay())->count() > 0)
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-yellow-800">Traitements d'aujourd'hui:</h4>
                                    <ul class="mt-1 list-disc list-inside text-sm text-yellow-700">
                                        @foreach($pendingTreatments->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay()) as $treatment)
                                            <li>
                                                {{ $treatment->rabbit->name }} - {{ $treatment->medication->name }}
                                                <form method="POST" action="{{ route('treatments.done', $treatment->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="ml-2 text-xs text-green-600 hover:text-green-800">Marquer comme fait</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <!-- Liste des traitements en retard -->
                            @if($pendingTreatments->where('scheduled_at', '<', now()->startOfDay())->count() > 0)
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-red-800">Traitements en retard:</h4>
                                    <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                        @foreach($pendingTreatments->where('scheduled_at', '<', now()->startOfDay()) as $treatment)
                                            <li>
                                                {{ $treatment->rabbit->name }} - {{ $treatment->medication->name }} 
                                                ({{ $treatment->scheduled_at->format('d/m/Y') }})
                                                <form method="POST" action="{{ route('treatments.done', $treatment->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="ml-2 text-xs text-green-600 hover:text-green-800">Marquer comme fait</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Nourritures à distribuer -->
            @if($todayFoodSchedules->count() > 0)
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <span class="font-medium">Nourritures à distribuer aujourd'hui:</span>
                            </p>
                            <div class="mt-2">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($todayFoodSchedules as $schedule)
                                        <li class="text-sm text-green-700">
                                            {{ $schedule->food->name }} - {{ $schedule->quantity }} {{ $schedule->unit }}
                                            <form method="POST" action="{{ route('food-schedules.complete', $schedule->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="ml-2 text-xs text-green-600 hover:text-green-800">
                                                    Marquer comme distribué
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('food-schedules.index') }}" class="text-sm font-medium text-green-700 hover:text-green-600">
                                    Voir l'emploi du temps complet
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Alertes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Alertes
                    </h3>
                    
                    @if(count($alerts) > 0)
                        <div class="space-y-4">
                            @foreach($alerts as $alert)
                                <div class="p-4 rounded-lg border-l-4 
                                    @if($alert['type'] == 'danger') border-red-500 bg-red-50 
                                    @elseif($alert['type'] == 'warning') border-yellow-500 bg-yellow-50 
                                    @elseif($alert['type'] == 'success') border-green-500 bg-green-50 
                                    @else border-blue-500 bg-blue-50 @endif">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            @if($alert['type'] == 'danger')
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($alert['type'] == 'warning')
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($alert['type'] == 'success')
                                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium 
                                                @if($alert['type'] == 'danger') text-red-800 
                                                @elseif($alert['type'] == 'warning') text-yellow-800 
                                                @elseif($alert['type'] == 'success') text-green-800 
                                                @else text-blue-800 @endif">
                                                {{ $alert['title'] }}
                                            </h3>
                                            <div class="mt-2 text-sm 
                                                @if($alert['type'] == 'danger') text-red-700 
                                                @elseif($alert['type'] == 'warning') text-yellow-700 
                                                @elseif($alert['type'] == 'success') text-green-700 
                                                @else text-blue-700 @endif">
                                                <p>{{ $alert['message'] }}</p>
                                            </div>
                                            @if(isset($alert['action']))
                                                <div class="mt-4">
                                                    <div class="-mx-2 -my-1.5 flex">
                                                        <a href="{{ $alert['action']['url'] }}" class="px-2 py-1.5 rounded-md text-sm font-medium 
                                                            @if($alert['type'] == 'danger') bg-red-100 text-red-800 hover:bg-red-200 
                                                            @elseif($alert['type'] == 'warning') bg-yellow-100 text-yellow-800 hover:bg-yellow-200 
                                                            @elseif($alert['type'] == 'success') bg-green-100 text-green-800 hover:bg-green-200 
                                                            @else bg-blue-100 text-blue-800 hover:bg-blue-200 @endif">
                                                            {{ $alert['action']['label'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Aucune alerte pour le moment</p>
                    @endif
                </div>
            </div>
    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dates clés -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dates clés à venir</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Accoulements récents -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-700 mb-2">Accoulements récents</h4>
                            @if(count($recentMatings) > 0)
                                <ul class="space-y-2">
                                    @foreach($recentMatings as $breeding)
                                        <li class="border-b border-blue-100 pb-2">
                                            <div class="flex justify-between">
                                                <span>{{ $breeding->mother->name }} & {{ $breeding->father->name }}</span>
                                                <span class="text-sm">{{ $breeding->mating_date->format('d/m/Y') }}</span>
                                            </div>
                                            <a href="{{ route('breedings.show', $breeding) }}" class="text-sm text-blue-600 hover:underline">Voir détails</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic">Aucun accouplement récent</p>
                            @endif
                        </div>
                        
                        <!-- Naissances imminentes -->
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-orange-700 mb-2">Naissances imminentes</h4>
                            @if(count($upcomingBirths) > 0)
                                <ul class="space-y-2">
                                    @foreach($upcomingBirths as $breeding)
                                        <li class="border-b border-orange-100 pb-2">
                                            <div class="flex justify-between">
                                                <span>{{ $breeding->mother->name }}</span>
                                                <span class="text-sm">{{ $breeding->expected_birth_date->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="text-sm">
                                                J-{{ now()->diffInDays($breeding->expected_birth_date, false) }}
                                                <a href="{{ route('breedings.show', $breeding) }}" class="text-orange-600 hover:underline ml-2">Voir détails</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic">Aucune naissance imminente</p>
                            @endif
                        </div>
                        
                        <!-- Naissances récentes -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-700 mb-2">Naissances récentes</h4>
                            @if(count($recentBirths) > 0)
                                <ul class="space-y-2">
                                    @foreach($recentBirths as $breeding)
                                        <li class="border-b border-green-100 pb-2">
                                            <div class="flex justify-between">
                                                <span>{{ $breeding->mother->name }}</span>
                                                <span class="text-sm">{{ $breeding->actual_birth_date->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="text-sm">
                                                {{ $breeding->number_of_kits ?? 0 }} petits
                                                <a href="{{ route('breedings.show', $breeding) }}" class="text-green-600 hover:underline ml-2">Voir détails</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 italic">Aucune naissance récente</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques générales</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Total lapins</p>
                                <p class="text-2xl font-bold">{{ $totalRabbits }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Portées actives</p>
                                <p class="text-2xl font-bold">{{ $activeBreedings }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Mâles reproducteurs</p>
                                <p class="text-2xl font-bold">{{ $breedingMales }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Femelles reproductrices</p>
                                <p class="text-2xl font-bold">{{ $breedingFemales }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('rabbits.create') }}" class="bg-indigo-50 p-4 rounded-lg hover:bg-indigo-100 transition">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="font-medium text-indigo-700">Ajouter un lapin</span>
                                </div>
                            </a>
                            <a href="{{ route('breedings.create') }}" class="bg-green-50 p-4 rounded-lg hover:bg-green-100 transition">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="font-medium text-green-700">Nouvelle portée</span>
                                </div>
                            </a>
                            <a href="{{ route('breedings.calendar') }}" class="bg-blue-50 p-4 rounded-lg hover:bg-blue-100 transition">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-blue-700">Calendrier</span>
                                </div>
                            </a>
                            <a href="{{ route('reports.index') }}" class="bg-yellow-50 p-4 rounded-lg hover:bg-yellow-100 transition">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium text-yellow-700">Rapports</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistiques des lapereaux -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques des lapereaux</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('kits.index') }}" class="block p-6 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Lapereaux non sevrés</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $totalKits }}</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="{{ route('kits.fattening') }}" class="block p-6 bg-purple-50 rounded-lg border border-purple-200 hover:bg-purple-100 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-500 text-white mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">Lapereaux en engraissement</p>
                                    <p class="text-2xl font-bold text-purple-800">{{ $kitsInFattening }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
    </div>

    <!-- Scripts pour les graphiques -->
 
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Données pour le graphique de croissance
            const weightData = @json($weightData);
            
            // Configuration du graphique de croissance
            const weightChartOptions = {
                series: [{
                    name: 'Poids moyen (g)',
                    data: weightData.weights
                }],
                chart: {
                    type: 'line',
                    height: 320,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                colors: ['#4F46E5'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: '#E2E8F0',
                    row: {
                        colors: ['#F8FAFC', 'transparent'],
                        opacity: 0.5
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#4F46E5'],
                    strokeColors: '#FFFFFF',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                },
                xaxis: {
                    categories: weightData.months,
                    title: {
                        text: 'Mois'
                    },
                    labels: {
                        style: {
                            colors: '#64748B'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Poids moyen (g)'
                    },
                    labels: {
                        style: {
                            colors: '#64748B'
                        },
                        formatter: function(val) {
                            return val.toFixed(0) + ' g';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(0) + ' g';
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };
            
            // Initialisation du graphique de croissance
            const weightChart = new ApexCharts(document.querySelector("#weight-chart"), weightChartOptions);
            weightChart.render();
            
            // Données pour le heatmap de reproduction
            const breedingData = @json($breedingData);
            
            // Configuration du heatmap de reproduction
            const breedingHeatmapOptions = {
                series: breedingData.series,
                chart: {
                    height: 320,
                    type: 'heatmap',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ["#9333EA"],
                title: {
                    text: 'Taux de réussite par période',
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 500,
                        color: '#1E293B'
                    }
                },
                plotOptions: {
                    heatmap: {
                        shadeIntensity: 0.5,
                        radius: 0,
                        useFillColorAsStroke: true,
                        colorScale: {
                            ranges: [{
                                from: 0,
                                to: 25,
                                name: 'Faible',
                                color: '#F9A8D4'
                            }, {
                                from: 26,
                                to: 50,
                                name: 'Moyen',
                                color: '#E879F9'
                            }, {
                                from: 51,
                                to: 75,
                                name: 'Bon',
                                color: '#C084FC'
                            }, {
                                from: 76,
                                to: 100,
                                name: 'Excellent',
                                color: '#8B5CF6'
                            }]
                        }
                    }
                },
                xaxis: {
                    categories: breedingData.months,
                    labels: {
                        style: {
                            colors: '#64748B'
                        }
                    }
                },
                yaxis: {
                    categories: breedingData.categories,
                    labels: {
                        style: {
                            colors: '#64748B'
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + '% de réussite';
                        }
                    }
                }
            };
            
            // Initialisation du heatmap de reproduction
            const breedingHeatmap = new ApexCharts(document.querySelector("#breeding-heatmap"), breedingHeatmapOptions);
            breedingHeatmap.render();
        });
    </script>
</x-app-layout>
