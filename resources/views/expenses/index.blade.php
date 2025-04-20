<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dépenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <form action="{{ route('expenses.index') }}" method="GET" class="flex space-x-4 items-end">
                    <div>
                        <label for="month-filter" class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
                        <input type="month" id="month-filter" name="month" value="{{ request('month') }}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" />
                    </div>
                    <div>
                        <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select id="category-filter" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <option value="">Toutes les catégories</option>
                            <option value="food" {{ request('category') == 'food' ? 'selected' : '' }}>Nourriture</option>
                            <option value="medication" {{ request('category') == 'medication' ? 'selected' : '' }}>Médicaments</option>
                            <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Équipement</option>
                            <option value="maintenance" {{ request('category') == 'maintenance' ? 'selected' : '' }}>Entretien</option>
                            <option value="veterinary" {{ request('category') == 'veterinary' ? 'selected' : '' }}>Vétérinaire</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Filtrer
                        </button>
                        @if(request()->has('month') || request()->has('category'))
                            <a href="{{ route('expenses.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                Réinitialiser
                            </a>
                        @endif
                    </div>
                </form>
                <div>
                    <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        {{ __('Ajouter une dépense') }}
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Total des dépenses</div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($totalExpenses, 2, ',', ' ') }} F</div>
                            @if(request()->has('month') || request()->has('category'))
                                <div class="text-xs text-gray-500 mt-1">Filtré</div>
                            @endif
                        </div>
                        
                        @if(isset($expensesByCategory) && count($expensesByCategory) > 0)
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Répartition par catégorie</div>
                            <div class="mt-2 space-y-2">
                                @foreach($expensesByCategory as $category => $amount)
                                    @php
                                        $categories = [
                                            'food' => 'Nourriture',
                                            'medication' => 'Médicaments',
                                            'equipment' => 'Équipement',
                                            'maintenance' => 'Entretien',
                                            'veterinary' => 'Vétérinaire',
                                            'other' => 'Autre'
                                        ];
                                        $categoryName = $categories[$category] ?? $category;
                                        $percentage = $totalExpenses > 0 ? round(($amount / $totalExpenses) * 100) : 0;
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-medium">{{ $categoryName }}</span>
                                        <span class="text-xs font-medium">{{ number_format($amount, 2, ',', ' ') }} ({{ $percentage }}%)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <!-- Le reste du tableau reste inchangé -->
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catégorie
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Méthode de paiement
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $expense->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($expense->category == 'food') bg-green-100 text-green-800 
                                            @elseif($expense->category == 'medication') bg-blue-100 text-blue-800 
                                            @elseif($expense->category == 'equipment') bg-purple-100 text-purple-800 
                                            @elseif($expense->category == 'maintenance') bg-yellow-100 text-yellow-800 
                                            @elseif($expense->category == 'veterinary') bg-red-100 text-red-800 
                                            @else bg-gray-100 text-gray-800 
                                            @endif">
                                            @php
                                                $categories = [
                                                    'food' => 'Nourriture',
                                                    'medication' => 'Médicaments',
                                                    'equipment' => 'Équipement',
                                                    'maintenance' => 'Entretien',
                                                    'veterinary' => 'Vétérinaire',
                                                    'other' => 'Autre'
                                                ];
                                            @endphp
                                            {{ $categories[$expense->category] ?? $expense->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ Str::limit($expense->description, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($expense->amount, 2, ',', ' ') }} F
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $methods = [
                                                'cash' => 'Espèces',
                                                'card' => 'Carte bancaire',
                                                'transfer' => 'Virement',
                                                'check' => 'Chèque',
                                                'other' => 'Autre'
                                            ];
                                        @endphp
                                        {{ $methods[$expense->payment_method] ?? $expense->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Aucune dépense enregistrée
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>