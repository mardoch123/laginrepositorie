<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rabbit;
use App\Models\Breeding;
use Carbon\Carbon;
use App\Models\Litter;
use App\Models\Reminder;
use App\Models\Cage;
use App\Models\Treatment;
use App\Models\WeightRecord;
use App\Models\Expense;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'rabbits_count' => Rabbit::where('is_active', true)->count(),
            'active_litters' => Litter::whereIn('status', ['breeding', 'pregnant', 'born'])->count(),
            'urgent_reminders' => Reminder::where('priority', 'urgent')
                ->where('is_completed', false)
                ->where('due_date', '>=', Carbon::now())
                ->count(),
            // Ajout des nouvelles statistiques
            'rabbits' => Rabbit::count(),
            'breedings' => Breeding::count(),
            'cages' => \App\Models\Cage::count(),
        ];

        // Récupérer les traitements en retard
        $overdueTreatments = \App\Models\Treatment::where('status', 'pending')
        ->where('scheduled_at', '<', now()->startOfDay())
        ->with(['rabbit', 'medication'])
        ->get();

        // Récupérer les traitements à effectuer aujourd'hui
        $todayTreatments = \App\Models\Treatment::where('status', 'pending')
        ->whereDate('scheduled_at', now()->toDateString())
        ->with(['rabbit', 'medication'])
        ->get();

        // Données pour le graphique de croissance
        $weightData = [
            'months' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            'weights' => $this->getAverageWeightsByMonth()
        ];

        // Données pour le heatmap de reproduction
        $breedingData = [
            'months' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            'categories' => ['Jeunes femelles', 'Femelles matures', 'Femelles âgées'],
            'series' => $this->getBreedingSuccessRates()
        ];

        // Définir la période pour les statistiques (même période que les autres KPIs)
        $startDate = now()->subMonths(3);
        $endDate = now();

        // Statistiques des ventes
        $sales = Sale::whereBetween('sale_date', [$startDate, $endDate])->get();
        $totalSalesWeight = $sales->sum('weight_kg');
        $totalSalesCount = $sales->count();
        $totalRevenue = $sales->sum('total_price');
        $averagePricePerKg = $totalSalesWeight > 0 ? $totalRevenue / $totalSalesWeight : 0;

        // Statistiques des dépenses
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        $totalExpenses = $expenses->sum('amount');

        // Calcul du bénéfice net
        $netProfit = $totalRevenue - $totalExpenses;

        // Calcul de la marge bénéficiaire
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // KPIs
        $survivalRate = $this->calculateSurvivalRate();
        $monthlyCosts = $this->calculateMonthlyCosts();
        $costsTrend = $this->calculateCostsTrend();
        $productivity = $this->calculateProductivity();
        $productivityTrend = $this->calculateProductivityTrend();
        $healthIndex = $this->calculateHealthIndex();
        $treatmentsCount = Treatment::count();

        // Combiner les traitements pour l'alerte
        $pendingTreatments = $todayTreatments->merge($overdueTreatments);

        $upcomingReminders = Reminder::where('is_completed', false)
            ->where('due_date', '>=', Carbon::now())
            ->where('due_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Récupérer les accouplements récents (derniers 7 jours)
        $recentMatings = Breeding::with(['mother', 'father'])
            ->whereDate('mating_date', '>=', Carbon::now()->subDays(7))
            ->orderBy('mating_date', 'desc')
            ->take(5)
            ->get();
        
        // Récupérer les naissances imminentes (dans les 7 prochains jours)
        $upcomingBirths = Breeding::with('mother')
            ->whereDate('expected_birth_date', '>=', Carbon::now())
            ->whereDate('expected_birth_date', '<=', Carbon::now()->addDays(7))
            ->whereNull('actual_birth_date')
            ->orderBy('expected_birth_date', 'asc')
            ->take(5)
            ->get();
        
        // Récupérer les naissances récentes (derniers 14 jours)
        $recentBirths = Breeding::with('mother')
            ->whereNotNull('actual_birth_date')
            ->whereDate('actual_birth_date', '>=', Carbon::now()->subDays(14))
            ->orderBy('actual_birth_date', 'desc')
            ->take(5)
            ->get();
        
        // Statistiques générales
        $totalRabbits = Rabbit::where('status', 'alive')->count();
        $activeBreedings = Breeding::whereNull('actual_birth_date')->count();

        // Rappels actifs
        $activeReminders = Reminder::where('active', true)
            ->where('is_completed', false)
            ->orderBy('due_date')
            ->orderBy('time')
            ->take(5)
            ->get();
        
        // Mâles reproducteurs (plus de 6 mois)
        $breedingMales = Rabbit::where('gender', 'male')
            ->where('status', 'alive')
            ->where('birth_date', '<=', Carbon::now()->subMonths(6))
            ->count();
        
        // Femelles reproductrices (plus de 5 mois)
        $breedingFemales = Rabbit::where('gender', 'female')
            ->where('status', 'alive')
            ->where('birth_date', '<=', Carbon::now()->subMonths(5))
            ->count();

            // Récupérer les nourritures prévues pour aujourd'hui
            $todayFoodSchedules = \App\Models\FoodSchedule::with('food')
            ->whereDate('scheduled_date', Carbon::today())
            ->where('is_completed', false)
            ->get();
        
        // Nombre total de lapereaux (nés mais pas encore sevrés)
        $totalKits = Breeding::whereNotNull('actual_birth_date')
            ->where(function($query) {
                $query->where('weaning_confirmed', false)
                      ->orWhereNull('weaning_confirmed');
            })
            ->sum('number_of_kits');
            
        // Nombre de lapereaux en engraissement
        $kitsInFattening = Breeding::where('weaning_confirmed', true)
            ->where(function($query) {
                $query->where('fattening_confirmed', false)
                      ->orWhereNull('fattening_confirmed');
            })
            ->sum('number_of_kits');
        
        // Générer les alertes
        $alerts = [];
        
        // Naissances imminentes
        $birthImminent = Breeding::with('mother')
            ->whereDate('expected_birth_date', '>=', Carbon::now())
            ->whereDate('expected_birth_date', '<=', Carbon::now()->addDays(3))
            ->whereNull('actual_birth_date')
            ->get();
        
        foreach ($birthImminent as $breeding) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Naissance imminente',
                'message' => 'La lapine ' . $breeding->mother->name . ' devrait mettre bas le ' . $breeding->expected_birth_date->format('d/m/Y') . ' (dans ' . Carbon::now()->diffInDays($breeding->expected_birth_date) . ' jours).',
                'action' => [
                    'label' => 'Voir détails',
                    'url' => route('breedings.show', $breeding),
                ],
            ];
        }
        
        // Naissances en retard
        $birthOverdue = Breeding::with('mother')
            ->whereDate('expected_birth_date', '<', Carbon::now())
            ->whereNull('actual_birth_date')
            ->get();
        
        foreach ($birthOverdue as $breeding) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Naissance en retard',
                'message' => 'La lapine ' . $breeding->mother->name . ' aurait dû mettre bas le ' . $breeding->expected_birth_date->format('d/m/Y') . ' (il y a ' . $breeding->expected_birth_date->diffInDays(Carbon::now()) . ' jours).',
                'action' => [
                    'label' => 'Mettre à jour',
                    'url' => route('breedings.edit', $breeding),
                ],
            ];
        }
        
        // Sevrages imminents
        $weaningSoon = Breeding::with(['mother', 'father'])
            ->whereNotNull('actual_birth_date')
            ->whereDate('weaning_date', '>=', Carbon::now())
            ->whereDate('weaning_date', '<=', Carbon::now()->addDays(3))
            ->where('weaning_confirmed', false)
            ->get();
        
        foreach ($weaningSoon as $breeding) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Sevrage imminent',
                'message' => 'Les lapereaux de ' . $breeding->mother->name . ' et ' . $breeding->father->name . ' doivent être sevrés le ' . $breeding->weaning_date->format('d/m/Y') . ' (dans ' . Carbon::now()->diffInDays($breeding->weaning_date) . ' jours).',
                'action' => [
                    'label' => 'Préparer le sevrage',
                    'url' => route('breedings.edit', $breeding),
                ],
            ];
        }
        
        // Sevrages en retard
        $weaningOverdue = Breeding::with(['mother', 'father'])
            ->whereNotNull('actual_birth_date')
            ->whereDate('weaning_date', '<', Carbon::now())
            ->where('weaning_confirmed', false)
            ->get();
        
        foreach ($weaningOverdue as $breeding) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Sevrage en retard',
                'message' => 'Les lapereaux de ' . $breeding->mother->name . ' et ' . $breeding->father->name . ' auraient dû être sevrés le ' . $breeding->weaning_date->format('d/m/Y') . ' (il y a ' . $breeding->weaning_date->diffInDays(Carbon::now()) . ' jours).',
                'action' => [
                    'label' => 'Confirmer le sevrage',
                    'url' => route('breedings.edit', $breeding),
                ],
            ];
        }
        
        // Fin d'engraissement imminente
        $fatteningEndingSoon = Breeding::with(['mother', 'father'])
            ->where('weaning_confirmed', true)
            ->where('fattening_confirmed', false)
            ->whereDate('expected_fattening_end_date', '>=', Carbon::now())
            ->whereDate('expected_fattening_end_date', '<=', Carbon::now()->addDays(3))
            ->get();
        
        foreach ($fatteningEndingSoon as $breeding) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Fin d\'engraissement imminente',
                'message' => 'Les lapins de ' . $breeding->mother->name . ' et ' . $breeding->father->name . ' terminent leur engraissement le ' . $breeding->expected_fattening_end_date->format('d/m/Y') . ' (dans ' . Carbon::now()->diffInDays($breeding->expected_fattening_end_date) . ' jours).',
                'action' => [
                    'label' => 'Vérifier l\'engraissement',
                    'url' => route('breedings.edit', $breeding),
                ],
            ];
        }
        
        // Engraissement en retard
        $fatteningOverdue = Breeding::with(['mother', 'father'])
            ->where('weaning_confirmed', true)
            ->where('fattening_confirmed', false)
            ->whereDate('expected_fattening_end_date', '<', Carbon::now())
            ->get();
        
        foreach ($fatteningOverdue as $breeding) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Engraissement terminé',
                'message' => 'Les lapins de ' . $breeding->mother->name . ' et ' . $breeding->father->name . ' ont terminé leur engraissement le ' . $breeding->expected_fattening_end_date->format('d/m/Y') . ' (il y a ' . $breeding->expected_fattening_end_date->diffInDays(Carbon::now()) . ' jours).',
                'action' => [
                    'label' => 'Confirmer la fin d\'engraissement',
                    'url' => route('breedings.edit', $breeding),
                ],
            ];
        }
        
        return view('dashboard', compact(
            'recentMatings',
            'upcomingBirths',
            'recentBirths',
            'totalRabbits',
            'activeBreedings',
            'breedingMales',
            'breedingFemales',
            'totalKits',
            'kitsInFattening',
            'alerts',
            'stats',
            'upcomingReminders',
            'pendingTreatments',
            'todayFoodSchedules',
            'activeReminders',
            'weightData',
            'breedingData',
            'survivalRate',
            'monthlyCosts',
            'costsTrend',
            'productivity',
            'productivityTrend',
            'healthIndex',
            'treatmentsCount',
            'totalSalesWeight',
            'totalSalesCount',
            'totalRevenue',
            'averagePricePerKg',
            'totalExpenses',
            'netProfit',
            'profitMargin',
            'startDate',
            'endDate',
            
        ));
    
  
    }
    
    /**
     * Calcule le poids moyen des lapins par mois
     * 
     * @return array
     */
    private function getAverageWeightsByMonth()
    {
        $weights = [];
        
        // Vérifier si la table existe
        try {
            // Pour chaque mois de l'année
            for ($month = 1; $month <= 12; $month++) {
                // Récupérer le poids moyen des lapins pour ce mois
                $averageWeight = \App\Models\WeightRecord::whereMonth('recorded_at', $month)
                    ->whereYear('recorded_at', now()->year)
                    ->avg('weight') ?? 0;
                
                // Arrondir à 2 décimales
                $weights[] = round($averageWeight, 2);
            }
        } catch (\Exception $e) {
            // Si la table n'existe pas ou autre erreur, retourner des valeurs par défaut
            $weights = array_fill(0, 12, 0);
        }
        
        return $weights;
    }
    
    /**
     * Calcule les taux de réussite de reproduction par catégorie et par mois
     * 
     * @return array
     */
    private function getBreedingSuccessRates()
    {
        $series = [];
        
        // Catégories d'âge des femelles
        $categories = [
            'Jeunes femelles' => ['min' => 5, 'max' => 8],  // 5-8 mois
            'Femelles matures' => ['min' => 9, 'max' => 24], // 9-24 mois
            'Femelles âgées' => ['min' => 25, 'max' => 60]   // 25+ mois
        ];
        
        $index = 0;
        foreach ($categories as $category => $ageRange) {
            $data = [];
            
            // Pour chaque mois de l'année
            for ($month = 1; $month <= 12; $month++) {
                // Calculer le taux de réussite pour cette catégorie et ce mois
                $successRate = $this->calculateBreedingSuccessRate($month, $ageRange['min'], $ageRange['max']);
                $data[] = $successRate;
            }
            
            $series[] = [
                'name' => $category,
                'data' => $data
            ];
            
            $index++;
        }
        
        return $series;
    }
    
    /**
     * Calcule le taux de réussite de reproduction pour un mois et une tranche d'âge donnés
     * 
     * @param int $month
     * @param int $minAge
     * @param int $maxAge
     * @return int
     */
    private function calculateBreedingSuccessRate($month, $minAge, $maxAge)
    {
        try {
            // Récupérer les accouplements pour ce mois et cette tranche d'âge
            $breedings = Breeding::whereRaw("strftime('%m', mating_date) = ?", [sprintf('%02d', $month)])
                ->whereRaw("strftime('%Y', mating_date) = ?", [now()->year])
                ->whereHas('mother', function ($query) use ($minAge, $maxAge) {
                    // Calculer l'âge en mois en utilisant les fonctions SQLite
                    $query->whereRaw("(strftime('%Y', 'now') - strftime('%Y', birth_date)) * 12 + 
                                     (strftime('%m', 'now') - strftime('%m', birth_date)) BETWEEN ? AND ?", 
                                     [$minAge, $maxAge]);
                })
                ->get();
            
            if ($breedings->isEmpty()) {
                return 0;
            }
            
            // Compter les accouplements réussis (avec naissance)
            $successfulBreedings = $breedings->filter(function ($breeding) {
                return $breeding->actual_birth_date !== null;
            })->count();
            
            // Calculer le taux de réussite
            return round(($successfulBreedings / $breedings->count()) * 100);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner 0
            return 0;
        }
    }
    
    /**
     * Calcule le taux de survie des lapereaux
     * 
     * @return float
     */
    private function calculateSurvivalRate()
    {
        // Récupérer les naissances des 3 derniers mois
        $breedings = Breeding::whereNotNull('actual_birth_date')
            ->where('actual_birth_date', '>=', now()->subMonths(3))
            ->get();
        
        if ($breedings->isEmpty()) {
            return 0;
        }
        
        $totalBorn = $breedings->sum('number_of_kits');
        $totalDied = $breedings->sum('number_of_deaths') ?? 0;
        
        if ($totalBorn == 0) {
            return 0;
        }
        
        return round(100 - (($totalDied / $totalBorn) * 100), 1);
    }
    
    /**
     * Calcule les coûts mensuels
     * 
     * @return float
     */
    private function calculateMonthlyCosts()
    {
        try {
            // Récupérer les dépenses du mois en cours
            $expenses = \App\Models\Expense::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount');
            
            return round($expenses, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function calculateCostsTrend()
    {
        try {
            // Récupérer les dépenses du mois en cours
            $currentMonthExpenses = \App\Models\Expense::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount');
            
            // Récupérer les dépenses du mois précédent
            $previousMonthExpenses = \App\Models\Expense::whereMonth('date', now()->subMonth()->month)
                ->whereYear('date', now()->subMonth()->year)
                ->sum('amount');
            
            if ($previousMonthExpenses == 0) {
                return 0;
            }
            
            // Calculer la variation en pourcentage
            return round((($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Calcule la productivité (nombre de lapereaux par femelle)
     * 
     * @return float
     */
    private function calculateProductivity()
    {
        // Récupérer le nombre de femelles reproductrices
        $breedingFemales = Rabbit::where('gender', 'female')
            ->where('status', 'alive')
            ->where('birth_date', '<=', now()->subMonths(5))
            ->count();
        
        if ($breedingFemales == 0) {
            return 0;
        }
        
        // Récupérer le nombre de lapereaux nés au cours des 3 derniers mois
        $totalKits = Breeding::whereNotNull('actual_birth_date')
            ->where('actual_birth_date', '>=', now()->subMonths(3))
            ->sum('number_of_kits');
        
        // Calculer la productivité
        return round($totalKits / $breedingFemales, 1);
    }
    
    /**
     * Calcule la tendance de la productivité par rapport à la période précédente
     * 
     * @return float
     */
    private function calculateProductivityTrend()
    {
        // Période actuelle (3 derniers mois)
        $currentPeriodStart = now()->subMonths(3);
        $currentPeriodEnd = now();
        
        // Période précédente (3 mois avant la période actuelle)
        $previousPeriodStart = now()->subMonths(6);
        $previousPeriodEnd = now()->subMonths(3);
        
        // Productivité actuelle
        $currentProductivity = $this->calculateProductivityForPeriod($currentPeriodStart, $currentPeriodEnd);
        
        // Productivité précédente
        $previousProductivity = $this->calculateProductivityForPeriod($previousPeriodStart, $previousPeriodEnd);
        
        if ($previousProductivity == 0) {
            return 0;
        }
        
        // Calculer la variation en pourcentage
        return round((($currentProductivity - $previousProductivity) / $previousProductivity) * 100, 1);
    }
    
    /**
     * Calcule la productivité pour une période donnée
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    private function calculateProductivityForPeriod($startDate, $endDate)
    {
        try {
            // Récupérer le nombre de femelles reproductrices pendant cette période
            $breedingFemales = Rabbit::where('gender', 'female')
                ->where(function ($query) use ($endDate) {
                    $query->where('status', 'alive')
                          ->orWhere(function ($q) use ($endDate) {
                              $q->where('status', 'dead')
                                ->whereDate('death_date', '>', $endDate);
                          });
                })
                ->where(function ($query) use ($endDate) {
                    // Calculer l'âge en mois en utilisant les fonctions SQLite
                    $fiveMonthsAgo = $endDate->copy()->subMonths(5)->format('Y-m-d');
                    $query->whereDate('birth_date', '<=', $fiveMonthsAgo);
                })
                ->count();
            
            if ($breedingFemales == 0) {
                return 0;
            }
            
            // Récupérer le nombre de lapereaux nés pendant cette période
            $totalKits = Breeding::whereNotNull('actual_birth_date')
                ->whereBetween('actual_birth_date', [$startDate, $endDate])
                ->sum('number_of_kits');
            
            // Calculer la productivité
            return $totalKits / $breedingFemales;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Calcule l'indice de santé du cheptel
     * 
     * @return float
     */
    private function calculateHealthIndex()
    {
        // Facteurs pris en compte pour l'indice de santé
        $survivalRate = $this->calculateSurvivalRate();
        
        // Taux de mortalité des adultes (sur les 3 derniers mois)
        $adultMortalityRate = $this->calculateAdultMortalityRate();
        
        // Nombre de traitements par lapin
        $treatmentsPerRabbit = $this->calculateTreatmentsPerRabbit();
        
        // Calculer l'indice de santé (pondération arbitraire)
        $healthIndex = ($survivalRate * 0.5) + ((100 - $adultMortalityRate) * 0.3) + ((100 - $treatmentsPerRabbit * 10) * 0.2);
        
        // Limiter l'indice entre 0 et 100
        return max(0, min(100, round($healthIndex, 1)));
    }
    
    /**
     * Calcule le taux de mortalité des lapins adultes
     * 
     * @return float
     */
    private function calculateAdultMortalityRate()
    {
        try {
            // Date il y a 6 mois + 3 mois = 9 mois
            $nineMonthsAgo = now()->subMonths(9)->format('Y-m-d');
            $threeMonthsAgo = now()->subMonths(3)->format('Y-m-d');
            
            // Nombre total de lapins adultes il y a 3 mois
            $totalAdultsThreeMonthsAgo = Rabbit::whereDate('birth_date', '<=', $nineMonthsAgo)
                ->where(function ($query) use ($threeMonthsAgo) {
                    $query->where('status', 'alive')
                          ->orWhere(function ($q) use ($threeMonthsAgo) {
                              $q->where('status', 'dead')
                                ->whereDate('death_date', '>=', $threeMonthsAgo);
                          });
                })
                ->count();
            
            if ($totalAdultsThreeMonthsAgo == 0) {
                return 0;
            }
            
            // Date il y a 6 mois
            $sixMonthsAgo = now()->subMonths(6)->format('Y-m-d');
            
            // Nombre de lapins adultes morts au cours des 3 derniers mois
            $adultDeaths = Rabbit::whereDate('birth_date', '<=', $sixMonthsAgo)
                ->where('status', 'dead')
                ->whereBetween('death_date', [$threeMonthsAgo, now()])
                ->count();
            
            // Calculer le taux de mortalité
            return round(($adultDeaths / $totalAdultsThreeMonthsAgo) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Calcule le nombre moyen de traitements par lapin
     * 
     * @return float
     */
    private function calculateTreatmentsPerRabbit()
    {
        // Nombre total de lapins
        $totalRabbits = Rabbit::where('status', 'alive')->count();
        
        if ($totalRabbits == 0) {
            return 0;
        }
        
        // Nombre de traitements au cours des 3 derniers mois
        $treatments = \App\Models\Treatment::whereBetween('scheduled_at', [now()->subMonths(3), now()])->count();
        
        // Calculer le nombre moyen de traitements par lapin
        return round($treatments / $totalRabbits, 2);
    }
}
