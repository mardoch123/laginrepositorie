<?php

namespace App\Http\Controllers;

use App\Models\Breeding;
use App\Models\Rabbit;
use App\Models\Litter;
use App\Models\Cage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BreedingOptimizationController extends Controller
{
    public function index()
    {
        // Get breeding statistics
        $stats = $this->getBreedingStatistics();
        
        // Get optimization recommendations
        $recommendations = $this->generateRecommendations($stats);
        
        // Get breeding performance by doe
        $doePerformance = $this->getDoePerformance();
        
        // Get monthly output projections
        $monthlyProjections = $this->getMonthlyProjections();
        
        // Get cage utilization
        $cageUtilization = $this->getCageUtilization();
        
        return view('optimization.index', compact(
            'stats', 
            'recommendations', 
            'doePerformance', 
            'monthlyProjections',
            'cageUtilization'
        ));
    }
    
    private function getBreedingStatistics()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Total kits produced in the last 12 months
        $totalKits = Litter::whereYear('birth_date', '>=', Carbon::now()->subYear())
            ->sum('total_kits');
            
        // Average kits per litter
        $avgKitsPerLitter = Litter::whereYear('birth_date', '>=', Carbon::now()->subYear())
            ->avg('total_kits');
            
        // Average weaning to market time (days)
        $avgFatteningDays = Breeding::whereNotNull('weaning_date')
            ->whereNotNull('fattening_end_date')
            ->whereYear('weaning_date', '>=', Carbon::now()->subYear())
            ->select(DB::raw('AVG(julianday(fattening_end_date) - julianday(weaning_date)) as avg_days'))
            ->first()->avg_days ?? 0;
            
        // Breeding success rate
        $totalBreedings = Breeding::whereYear('breeding_date', '>=', Carbon::now()->subYear())->count();
        $successfulBreedings = Breeding::whereYear('breeding_date', '>=', Carbon::now()->subYear())
            ->whereHas('litters')->count();
        $breedingSuccessRate = $totalBreedings > 0 ? ($successfulBreedings / $totalBreedings) * 100 : 0;
        
        // Monthly output (kits reaching market weight)
        $monthlyOutput = [];
        for ($i = 0; $i < 12; $i++) {
            $month = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $year = ($currentMonth - $i) > 0 ? $currentYear : ($currentYear - 1);
            
            $output = Breeding::whereMonth('fattening_end_date', $month)
                ->whereYear('fattening_end_date', $year)
                ->sum('number_of_kits');
                
            $monthName = Carbon::createFromDate($year, $month, 1)->format('M Y');
            $monthlyOutput[$monthName] = $output;
        }
        
        // Reverse to show chronological order
        $monthlyOutput = array_reverse($monthlyOutput);
        
        return [
            'total_kits' => $totalKits,
            'avg_kits_per_litter' => round($avgKitsPerLitter, 1),
            'avg_fattening_days' => round($avgFatteningDays),
            'breeding_success_rate' => round($breedingSuccessRate, 1),
            'monthly_output' => $monthlyOutput,
        ];
    }
    
    private function generateRecommendations($stats)
    {
        $recommendations = [];
        
        // 1. Breeding frequency optimization
        $activeBreedingDoes = Rabbit::where('gender', 'female')
            ->where('status', 'active')
            ->where('purpose', 'breeding')
            ->count();
            
        $avgLittersPerDoePerYear = Breeding::whereHas('litters')
            ->whereYear('breeding_date', '>=', Carbon::now()->subYear())
            ->select('mother_id', DB::raw('COUNT(DISTINCT id) as litter_count'))
            ->groupBy('mother_id')
            ->get()
            ->avg('litter_count') ?? 0;
            
        if ($avgLittersPerDoePerYear < 4) {
            $recommendations[] = [
                'title' => 'Augmenter la fréquence de reproduction',
                'description' => 'Vos lapines produisent en moyenne ' . round($avgLittersPerDoePerYear, 1) . ' portées par an. Envisagez d\'augmenter à 4-6 portées par an pour optimiser la production.',
                'impact' => 'Élevé',
                'action' => 'Réduire l\'intervalle entre le sevrage et la prochaine saillie à 10-14 jours.'
            ];
        }
        
        // 2. Litter size optimization
        if ($stats['avg_kits_per_litter'] < 8) {
            $recommendations[] = [
                'title' => 'Améliorer la taille des portées',
                'description' => 'La taille moyenne de vos portées est de ' . $stats['avg_kits_per_litter'] . ' lapereaux. L\'objectif devrait être de 8-10 lapereaux par portée.',
                'impact' => 'Moyen',
                'action' => 'Vérifier la nutrition des lapines reproductrices et envisager une sélection génétique pour la prolificité.'
            ];
        }
        
        // 3. Fattening period optimization
        if ($stats['avg_fattening_days'] > 35) {
            $recommendations[] = [
                'title' => 'Réduire la période d\'engraissement',
                'description' => 'Vos lapins passent en moyenne ' . $stats['avg_fattening_days'] . ' jours en engraissement. Réduire cette période peut augmenter votre production mensuelle.',
                'impact' => 'Élevé',
                'action' => 'Optimiser l\'alimentation pendant l\'engraissement et envisager des races à croissance plus rapide.'
            ];
        }
        
        // 4. Breeding success rate optimization
        if ($stats['breeding_success_rate'] < 80) {
            $recommendations[] = [
                'title' => 'Améliorer le taux de réussite des accouplements',
                'description' => 'Votre taux de réussite des accouplements est de ' . $stats['breeding_success_rate'] . '%. Augmenter ce taux peut améliorer l\'efficacité globale.',
                'impact' => 'Moyen',
                'action' => 'Vérifier la fertilité des mâles, optimiser le moment des accouplements, et améliorer les conditions d\'élevage.'
            ];
        }
        
        // 5. Cage capacity utilization
        $cageUtilization = $this->getCageUtilization();
        if ($cageUtilization['utilization_rate'] < 80) {
            $recommendations[] = [
                'title' => 'Optimiser l\'utilisation des cages',
                'description' => 'Vos cages sont utilisées à ' . round($cageUtilization['utilization_rate']) . '% de leur capacité. Une meilleure utilisation peut augmenter votre production.',
                'impact' => 'Moyen',
                'action' => 'Réorganiser les lapins dans les cages pour maximiser l\'espace disponible.'
            ];
        }
        
        // 6. Mortality rate optimization
        $mortalityRate = $this->calculateMortalityRate();
        if ($mortalityRate > 10) {
            $recommendations[] = [
                'title' => 'Réduire le taux de mortalité',
                'description' => 'Le taux de mortalité des lapereaux est de ' . round($mortalityRate, 1) . '%. Réduire ce taux augmentera directement votre production.',
                'impact' => 'Élevé',
                'action' => 'Améliorer l\'hygiène, la vaccination, et les soins aux nouveau-nés.'
            ];
        }
        
        return $recommendations;
    }
    
    private function getDoePerformance()
    {
        $does = Rabbit::where('gender', 'female')
            ->where('purpose', 'breeding')
            ->get();
            
        $performance = [];
        
        foreach ($does as $doe) {
            $breedings = Breeding::where('mother_id', $doe->id)
                ->whereYear('breeding_date', '>=', Carbon::now()->subYear())
                ->get();
                
            $totalLitters = $breedings->filter(function ($breeding) {
                return $breeding->litters->count() > 0;
            })->count();
            
            $totalKits = 0;
            $weanedKits = 0;
            
            foreach ($breedings as $breeding) {
                foreach ($breeding->litters as $litter) {
                    $totalKits += $litter->total_kits;
                    $weanedKits += $litter->weaned_kits;
                }
            }
            
            $performance[] = [
                'doe' => $doe,
                'total_litters' => $totalLitters,
                'total_kits' => $totalKits,
                'weaned_kits' => $weanedKits,
                'avg_kits_per_litter' => $totalLitters > 0 ? round($totalKits / $totalLitters, 1) : 0,
                'survival_rate' => $totalKits > 0 ? round(($weanedKits / $totalKits) * 100, 1) : 0,
            ];
        }
        
        // Sort by total kits produced (descending)
        usort($performance, function ($a, $b) {
            return $b['total_kits'] - $a['total_kits'];
        });
        
        return $performance;
    }
    
    private function getMonthlyProjections()
    {
        // Current production metrics
        $stats = $this->getBreedingStatistics();
        
        // Calculate average monthly output from the last 3 months
        $lastThreeMonths = array_slice($stats['monthly_output'], -3, 3);
        $avgMonthlyOutput = array_sum($lastThreeMonths) / 3;
        
        // Project for the next 6 months
        $projections = [];
        $currentDate = Carbon::now();
        
        // Baseline projection (current performance)
        $baselineProjection = [];
        
        // Optimized projection (with recommendations implemented)
        $optimizedProjection = [];
        
        // Improvement factors
        $improvementFactors = [
            1 => 1.05, // 5% improvement in month 1
            2 => 1.10, // 10% improvement in month 2
            3 => 1.15, // 15% improvement in month 3
            4 => 1.20, // 20% improvement in month 4
            5 => 1.25, // 25% improvement in month 5
            6 => 1.30, // 30% improvement in month 6
        ];
        
        for ($i = 1; $i <= 6; $i++) {
            $month = $currentDate->copy()->addMonths($i);
            $monthName = $month->format('M Y');
            
            // Baseline is the current average
            $baseline = round($avgMonthlyOutput);
            
            // Optimized includes improvement factors
            $optimized = round($avgMonthlyOutput * $improvementFactors[$i]);
            
            $projections[$monthName] = [
                'baseline' => $baseline,
                'optimized' => $optimized,
                'difference' => $optimized - $baseline,
                'percentage_increase' => $baseline > 0 ? round((($optimized - $baseline) / $baseline) * 100) : 100
            ];
        }
        
        return $projections;
    }
    
    private function getCageUtilization()
    {
        $cages = Cage::all();
        $totalCapacity = $cages->sum('capacity');
        $totalOccupied = 0;
        
        foreach ($cages as $cage) {
            // Count rabbits in this cage
            $rabbitsCount = Rabbit::where('cage_id', $cage->id)->count();
            
            // Count breeding groups in this cage
            $breedingGroupsCount = Breeding::where('cage_id', $cage->id)
                ->where('status', 'fattening')
                ->sum('number_of_kits');
                
            $totalOccupied += ($rabbitsCount + $breedingGroupsCount);
        }
        
        $utilizationRate = $totalCapacity > 0 ? ($totalOccupied / $totalCapacity) * 100 : 0;
        
        return [
            'total_capacity' => $totalCapacity,
            'total_occupied' => $totalOccupied,
            'available_space' => $totalCapacity - $totalOccupied,
            'utilization_rate' => $utilizationRate,
            'cages' => $cages->map(function ($cage) {
                $rabbitsCount = Rabbit::where('cage_id', $cage->id)->count();
                $breedingGroupsCount = Breeding::where('cage_id', $cage->id)
                    ->where('status', 'fattening')
                    ->sum('number_of_kits');
                $occupied = $rabbitsCount + $breedingGroupsCount;
                
                return [
                    'id' => $cage->id,
                    'name' => $cage->name,
                    'capacity' => $cage->capacity,
                    'occupied' => $occupied,
                    'available' => $cage->capacity - $occupied,
                    'utilization_rate' => $cage->capacity > 0 ? ($occupied / $cage->capacity) * 100 : 0,
                ];
            })
        ];
    }
    
    private function calculateMortalityRate()
    {
        $litters = Litter::whereYear('birth_date', '>=', Carbon::now()->subYear())->get();
        
        $totalBorn = $litters->sum('total_kits');
        $totalWeaned = $litters->sum('weaned_kits');
        
        if ($totalBorn > 0) {
            return (($totalBorn - $totalWeaned) / $totalBorn) * 100;
        }
        
        return 0;
    }
    
    public function simulateProduction(Request $request)
    {
        $validated = $request->validate([
            'breeding_does' => 'required|integer|min:1',
            'litters_per_year' => 'required|numeric|min:1|max:8',
            'kits_per_litter' => 'required|numeric|min:1',
            'survival_rate' => 'required|numeric|min:1|max:100',
            'fattening_days' => 'required|integer|min:20',
        ]);
        
        // Calculate monthly production
        $annualKits = $validated['breeding_does'] * $validated['litters_per_year'] * $validated['kits_per_litter'] * ($validated['survival_rate'] / 100);
        $monthlyKits = round($annualKits / 12);
        
        // Calculate required resources
        $fatteningCagesNeeded = ceil(($monthlyKits * $validated['fattening_days'] / 30) / 10); // Assuming 10 kits per fattening cage
        $breedingCagesNeeded = ceil($validated['breeding_does'] / 1); // Assuming 1 doe per breeding cage
        $totalCagesNeeded = $fatteningCagesNeeded + $breedingCagesNeeded;
        
        // Calculate feed requirements (kg)
        $monthlyFeedKg = ($validated['breeding_does'] * 0.15 * 30) + ($monthlyKits * 0.1 * 30);
        
        // Calculate space requirements (m²)
        $spaceRequired = $totalCagesNeeded * 0.5; // Assuming 0.5m² per cage
        
        $results = [
            'monthly_production' => $monthlyKits,
            'annual_production' => round($annualKits),
            'fattening_cages_needed' => $fatteningCagesNeeded,
            'breeding_cages_needed' => $breedingCagesNeeded,
            'total_cages_needed' => $totalCagesNeeded,
            'monthly_feed_kg' => round($monthlyFeedKg),
            'space_required_m2' => $spaceRequired,
        ];
        
        return response()->json($results);
    }
}