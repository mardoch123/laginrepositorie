<?php

namespace App\Http\Controllers;

use App\Models\Rabbit;
use App\Models\Breeding;
use App\Models\Treatment;
use App\Models\FoodSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Expense;

class ReportController extends Controller
{
    /**
     * Affiche la page d'index des rapports
     */
    public function index()
    {
        return view('reports.index');
    }

    public function breeding()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;
        
        // Statistiques de reproduction
        $totalBreedings = Breeding::count();
        $successfulBreedings = Breeding::whereNotNull('actual_birth_date')->count();
        $successRate = $totalBreedings > 0 ? round(($successfulBreedings / $totalBreedings) * 100, 1) : 0;
        
        // Statistiques de naissance
        $totalBirths = Breeding::whereNotNull('actual_birth_date')->sum('number_of_kits');
        $averageKitsPerLitter = $successfulBreedings > 0 ? round($totalBirths / $successfulBreedings, 1) : 0;
        
        // Statistiques mensuelles
        $monthlyStats = $this->getMonthlyBreedingStats($currentYear);
        $previousYearStats = $this->getMonthlyBreedingStats($previousYear);
        
        return view('reports.breeding', compact(
            'totalBreedings',
            'successfulBreedings',
            'successRate',
            'totalBirths',
            'averageKitsPerLitter',
            'monthlyStats',
            'previousYearStats',
            'currentYear',
            'previousYear'
        ));
    }

     /**
     * Génère un rapport PDF
     */
    public function generate($type)
    {
        switch ($type) {
            case 'breeding':
                return $this->generateBreedingReport();
            case 'financial':
                return $this->generateFinancialReport();
            case 'health':
                return $this->generateHealthReport();
            default:
                return redirect()->back()->with('error', 'Type de rapport invalide.');
        }
    }

    /**
     * Exporte les données en CSV
     */
    public function export(Request $request)
    {
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Logique d'export CSV selon le type
        
        return response()->download('path/to/csv/file.csv');
    }

    /**
     * Affiche le rapport financier
     */
    public function financial()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;
        
        // Dépenses totales
        $totalExpenses = Expense::sum('amount');
        $yearlyExpenses = Expense::whereYear('date', $currentYear)->sum('amount');
        $previousYearExpenses = Expense::whereYear('date', $previousYear)->sum('amount');
        
        // Dépenses par catégorie
        $expensesByCategory = $this->getExpensesByCategory($currentYear);
        $previousYearExpensesByCategory = $this->getExpensesByCategory($previousYear);
        
        // Dépenses mensuelles
        $monthlyExpenses = $this->getMonthlyExpenses($currentYear);
        $previousYearMonthlyExpenses = $this->getMonthlyExpenses($previousYear);
        
        return view('reports.financial', compact(
            'totalExpenses',
            'yearlyExpenses',
            'previousYearExpenses',
            'expensesByCategory',
            'previousYearExpensesByCategory',
            'monthlyExpenses',
            'previousYearMonthlyExpenses',
            'currentYear',
            'previousYear'
        ));
    }

    /**
     * Affiche le rapport de santé
     */
    public function health()
    {
        $currentYear = Carbon::now()->year;
        
        // Statistiques de traitements
        $totalTreatments = Treatment::count();
        $activeTreatments = Treatment::where('status', 'active')->count();
        $completedTreatments = Treatment::where('status', 'completed')->count();
        
        // Traitements par type
        $treatmentsByType = $this->getTreatmentsByType();
        
        // Mortalité
        $totalDeaths = Rabbit::where('status', 'dead')->count();
        $mortalityRate = Rabbit::count() > 0 ? round(($totalDeaths / Rabbit::count()) * 100, 1) : 0;
        
        return view('reports.health', compact(
            'totalTreatments',
            'activeTreatments',
            'completedTreatments',
            'treatmentsByType',
            'totalDeaths',
            'mortalityRate'
        ));
    }


    private function getMonthlyBreedingStats($year)
    {
        $stats = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $breedings = Breeding::whereYear('mating_date', $year)
                ->whereMonth('mating_date', $month)
                ->get();
            
            $totalBreedings = $breedings->count();
            $successfulBreedings = $breedings->filter(function ($breeding) {
                return $breeding->actual_birth_date !== null;
            })->count();
            
            $successRate = $totalBreedings > 0 ? round(($successfulBreedings / $totalBreedings) * 100, 1) : 0;
            
            $stats[] = [
                'month' => Carbon::createFromDate($year, $month, 1)->format('M'),
                'total' => $totalBreedings,
                'successful' => $successfulBreedings,
                'rate' => $successRate
            ];
        }
        
        return $stats;
    }

    private function getExpensesByCategory($year)
    {
        $categories = [
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
        
        $result = [];
        
        foreach ($categories as $key => $name) {
            $amount = Expense::where('category', $key)
                ->whereYear('date', $year)
                ->sum('amount');
            
            $result[] = [
                'category' => $name,
                'amount' => $amount
            ];
        }
        
        return $result;
    }

    private function getMonthlyExpenses($year)
    {
        $expenses = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $amount = Expense::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');
            
            $expenses[] = [
                'month' => Carbon::createFromDate($year, $month, 1)->format('M'),
                'amount' => $amount
            ];
        }
        
        return $expenses;
    }

    private function getTreatmentsByType()
    {
        $types = [
            'preventive' => 'Préventif',
            'curative' => 'Curatif',
            'routine' => 'Routine'
        ];
        
        $result = [];
        
        foreach ($types as $key => $name) {
            $count = Treatment::where('type', $key)->count();
            
            $result[] = [
                'type' => $name,
                'count' => $count
            ];
        }
        
        return $result;
    }

    /**
     * Méthodes pour générer les rapports PDF
     */
    private function generateBreedingReport()
    {
        // Logique pour générer le rapport PDF d'élevage
        $data = [
            'title' => 'Rapport d\'élevage',
            'date' => Carbon::now()->format('d/m/Y'),
            // Autres données
        ];
        
        $pdf = PDF::loadView('reports.pdf.breeding', $data);
        return $pdf->download('rapport-elevage-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    private function generateFinancialReport()
    {
        // Logique pour générer le rapport PDF financier
        $data = [
            'title' => 'Rapport financier',
            'date' => Carbon::now()->format('d/m/Y'),
            // Autres données
        ];
        
        $pdf = PDF::loadView('reports.pdf.financial', $data);
        return $pdf->download('rapport-financier-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    private function generateHealthReport()
    {
        // Logique pour générer le rapport PDF de santé
        $data = [
            'title' => 'Rapport de santé',
            'date' => Carbon::now()->format('d/m/Y'),
            // Autres données
        ];
        
        $pdf = PDF::loadView('reports.pdf.health', $data);
        return $pdf->download('rapport-sante-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Génère un rapport mensuel en PDF
     */
    public function generateMonthly(Request $request)
    {
        // Définir la période (mois en cours par défaut)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Récupérer les données pour le rapport
        $data = [
            'period' => [
                'month' => $startDate->translatedFormat('F'),
                'year' => $year,
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
            ],
            'births' => Breeding::whereBetween('actual_birth_date', [$startDate, $endDate])->get(),
            'matings' => Breeding::whereBetween('mating_date', [$startDate, $endDate])->get(),
            'deaths' => Rabbit::whereBetween('death_date', [$startDate, $endDate])->get(),
            'sales' => Rabbit::whereBetween('sold_at', [$startDate, $endDate])->get(),
            'treatments' => Treatment::whereBetween('scheduled_at', [$startDate, $endDate])->get(),
            'foodSchedules' => FoodSchedule::whereBetween('scheduled_at', [$startDate, $endDate])->get(),
            'statistics' => [
                'totalBirths' => Breeding::whereBetween('actual_birth_date', [$startDate, $endDate])->sum('number_of_kits'),
                'totalDeaths' => Rabbit::whereBetween('death_date', [$startDate, $endDate])->count(),
                'totalSales' => Rabbit::whereBetween('sold_at', [$startDate, $endDate])->count(),
                'totalRevenue' => Rabbit::whereBetween('sold_at', [$startDate, $endDate])->sum('sale_price'),
                'averageWeight' => Rabbit::whereNotNull('current_weight')->avg('current_weight'),
                'survivalRate' => $this->calculateSurvivalRate($startDate, $endDate),
            ]
        ];
        
        // Générer le PDF
        $pdf = PDF::loadView('reports.monthly', $data);
        
        // Télécharger le PDF
        return $pdf->download('rapport-mensuel-' . $startDate->format('Y-m') . '.pdf');
    }
    
    /**
     * Génère un rapport de traitements en PDF
     */
    public function generateTreatments(Request $request)
    {
        // Définir la période
        $startDate = $request->input('start_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date')) 
            : now()->subMonths(1);
            
        $endDate = $request->input('end_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date')) 
            : now();
        
        // Récupérer les traitements pour la période
        $treatments = Treatment::with(['rabbit', 'medication'])
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->orderBy('scheduled_at')
            ->get();
            
        // Regrouper par lapin
        $treatmentsByRabbit = $treatments->groupBy('rabbit_id');
        
        $data = [
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
            ],
            'treatments' => $treatments,
            'treatmentsByRabbit' => $treatmentsByRabbit,
            'statistics' => [
                'totalTreatments' => $treatments->count(),
                'completedTreatments' => $treatments->where('completed', true)->count(),
                'pendingTreatments' => $treatments->where('completed', false)->count(),
            ]
        ];
        
        // Générer le PDF
        $pdf = PDF::loadView('reports.treatments', $data);
        
        // Télécharger le PDF
        return $pdf->download('rapport-traitements-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Calcule le taux de survie des lapereaux pour une période donnée
     */
    private function calculateSurvivalRate($startDate, $endDate)
    {
        $breedings = Breeding::whereBetween('actual_birth_date', [$startDate, $endDate])->get();
        
        if ($breedings->isEmpty()) {
            return 0;
        }
        
        $totalBorn = $breedings->sum('number_of_kits');
        $totalDied = $breedings->sum('number_of_deaths');
        
        if ($totalBorn == 0) {
            return 0;
        }
        
        return round(100 - (($totalDied / $totalBorn) * 100), 1);
    }
}