<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Affiche la liste des dépenses
     */
    public function index(Request $request)
    {
        $query = Expense::query();
        
        // Filtre par mois
        if ($request->filled('month')) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereYear('date', $date->year)
                  ->whereMonth('date', $date->month);
        }
        
        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Calculer les statistiques
        $totalExpenses = $query->sum('amount');
        
        // Récupérer les dépenses par catégorie pour les statistiques
        $expensesByCategory = $query->clone()
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();
        
        // Récupérer les dépenses paginées
        $expenses = $query->orderBy('date', 'desc')->paginate(15);
        
        return view('expenses.index', compact('expenses', 'totalExpenses', 'expensesByCategory'));
    }

    /**
     * Affiche le formulaire de création d'une dépense
     */
    public function create()
    {
        $categories = $this->getExpenseCategories();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle dépense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:50',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Dépense enregistrée avec succès.');
    }

    /**
     * Affiche les détails d'une dépense
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Affiche le formulaire d'édition d'une dépense
     */
    public function edit(Expense $expense)
    {
        $categories = $this->getExpenseCategories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Met à jour une dépense
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:50',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Dépense mise à jour avec succès.');
    }

    /**
     * Supprime une dépense
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Dépense supprimée avec succès.');
    }

    /**
     * Affiche les dépenses par catégorie
     */
    public function byCategory($category)
    {
        $expenses = Expense::where('category', $category)
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        $totalInCategory = Expense::where('category', $category)->sum('amount');
        
        return view('expenses.by-category', compact('expenses', 'category', 'totalInCategory'));
    }

    /**
     * Affiche les dépenses par période
     */
    public function byPeriod($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'this-month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $title = 'Ce mois-ci';
                break;
            case 'last-month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                $title = 'Mois dernier';
                break;
            case 'this-year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $title = 'Cette année';
                break;
            case 'last-year':
                $start = $now->copy()->subYear()->startOfYear();
                $end = $now->copy()->subYear()->endOfYear();
                $title = 'Année dernière';
                break;
            default:
                $start = $now->copy()->subDays(30);
                $end = $now;
                $title = '30 derniers jours';
        }
        
        $expenses = Expense::whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        $totalInPeriod = Expense::whereBetween('date', [$start, $end])->sum('amount');
        
        return view('expenses.by-period', compact('expenses', 'period', 'title', 'totalInPeriod', 'start', 'end'));
    }

    /**
     * Retourne les catégories de dépenses
     */
    private function getExpenseCategories()
    {
        return [
            'alimentation' => 'Alimentation',
            'materiel' => 'Matériel',
            'veterinaire' => 'Soins vétérinaires',
            'medicaments' => 'Médicaments',
            'cages' => 'Cages et équipements',
            'transport' => 'Transport',
            'marketing' => 'Marketing et vente',
            'salaires' => 'Salaires',
            'taxes' => 'Taxes et impôts',
            'autres' => 'Autres dépenses'
        ];
    }

    /**
     * Retourne les dépenses par catégorie
     */
    private function getExpensesByCategory()
    {
        $categories = $this->getExpenseCategories();
        $result = [];
        
        foreach ($categories as $key => $name) {
            $result[$key] = [
                'name' => $name,
                'total' => Expense::where('category', $key)->sum('amount'),
                'count' => Expense::where('category', $key)->count(),
            ];
        }
        
        return $result;
    }
}