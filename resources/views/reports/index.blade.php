<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Générer des rapports</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rapport mensuel -->
                        <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-200">
                            <div class="flex items-center mb-4">
                                <div class="p-3 bg-indigo-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-indigo-800">Rapport mensuel</h4>
                            </div>
                            
                            <p class="text-sm text-indigo-700 mb-4">
                                Générez un rapport complet des activités du mois avec les statistiques de naissances, décès, ventes et traitements.
                            </p>
                            
                            <form action="{{ route('reports.generate-monthly') }}" method="GET" class="mt-4">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="month" class="block text-sm font-medium text-indigo-700 mb-1">Mois</label>
                                        <select id="month" name="month" class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ $month }}" {{ now()->month == $month ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-indigo-700 mb-1">Année</label>
                                        <select id="year" name="year" class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach(range(now()->year - 2, now()->year) as $year)
                                                <option value="{{ $year }}" {{ now()->year == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Générer le rapport PDF
                                </button>
                            </form>
                        </div>
                        
                        <!-- Rapport de traitements -->
                        <div class="bg-emerald-50 rounded-lg p-6 border border-emerald-200">
                            <div class="flex items-center mb-4">
                                <div class="p-3 bg-emerald-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-emerald-800">Rapport de traitements</h4>
                            </div>
                            
                            <p class="text-sm text-emerald-700 mb-4">
                                Générez un rapport détaillé des traitements médicaux effectués sur une période spécifique.
                            </p>
                            
                            <form action="{{ route('reports.generate-treatments') }}" method="GET" class="mt-4">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-emerald-700 mb-1">Date de début</label>
                                        <input type="date" id="start_date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-emerald-700 mb-1">Date de fin</label>
                                        <input type="date" id="end_date" name="end_date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Générer le rapport PDF
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>