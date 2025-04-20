<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\FoodSchedule;
use App\Models\Rabbit;
use App\Models\HealthRecord; // Ajout du modèle pour les incidents de santé
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FoodScheduleController extends Controller
{
    public function index()
    {
        $currentWeekSchedules = FoodSchedule::with('food')
            ->whereBetween('scheduled_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->orderBy('scheduled_date')
            ->get()
            ->groupBy(function($schedule) {
                return $schedule->scheduled_date->format('Y-m-d');
            });
            
        // Récupérer les statistiques de santé pour la semaine précédente
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        
        $healthStats = null;
        
        // Vérifier si la table health_records existe
        if (Schema::hasTable('health_records')) {
            $deathCount = DB::table('health_records')
                ->whereBetween('recorded_at', [$lastWeekStart, $lastWeekEnd])
                ->where('type', 'death')
                ->count();
                
            $illnessCount = DB::table('health_records')
                ->whereBetween('recorded_at', [$lastWeekStart, $lastWeekEnd])
                ->where('type', 'illness')
                ->count();
                
            $healthStats = [
                'deaths' => $deathCount,
                'illnesses' => $illnessCount,
                'hasWarning' => ($deathCount > 1 || $illnessCount > 2)
            ];
        }
            
        return view('food-schedules.index', compact('currentWeekSchedules', 'healthStats'));
    }
    
    public function markAsCompleted(Request $request, FoodSchedule $foodSchedule)
    {
        $foodSchedule->update([
            'is_completed' => true,
            'completed_at' => Carbon::now(),
            'notes' => $request->notes
        ]);
        
        return redirect()->back()->with('success', 'Nourriture marquée comme distribuée.');
    }
    
    public function generateManually()
    {
        // Exécuter la commande manuellement
        \Artisan::call('food:generate-schedule');
        
        return redirect()->route('food-schedules.index')
            ->with('success', 'Emploi du temps des nourritures généré avec succès.');
    }
    
    public function generateSchedules(Request $request)
    {
        try {
            // Vérifier si les tables existent
            if (!Schema::hasTable('foods') || !Schema::hasTable('food_schedules')) {
                return redirect()->route('food-schedules.index')
                    ->with('error', 'Les tables nécessaires n\'existent pas. Veuillez contacter l\'administrateur.');
            }

            // Récupérer le paramètre pour privilégier les feuilles
            $prioritizeLeaves = (bool) $request->input('prioritize_leaves', false);

            // Supprimer les emplois du temps futurs non complétés
            FoodSchedule::where('is_completed', false)
                ->whereDate('scheduled_date', '>=', Carbon::today())
                ->delete();

            $today = Carbon::today();
            $endDate = $today->copy()->addDays(7);
            $rabbitCount = Rabbit::where('is_active', true)->count();
            
            if ($rabbitCount == 0) {
                return redirect()->route('food-schedules.index')
                    ->with('error', 'Aucun lapin actif trouvé pour générer des emplois du temps.');
            }
            
            // Récupérer les nourritures actives
            $query = Food::where('is_active', true);
            
            // Si on doit privilégier les feuilles, on les met en premier
            if ($prioritizeLeaves) {
                $query->orderByRaw("
                    CASE 
                        WHEN LOWER(name) LIKE '%feuille%' THEN 1
                        WHEN LOWER(name) LIKE '%foin%' THEN 2
                        WHEN LOWER(name) LIKE '%herbe%' THEN 3
                        WHEN LOWER(name) LIKE '%légume%' THEN 4
                        ELSE 5
                    END
                ");
            }
            
            $foods = $query->get();
            
            if ($foods->count() == 0) {
                return redirect()->route('food-schedules.index')
                    ->with('error', 'Aucune nourriture active trouvée pour générer des emplois du temps.');
            }
            
            $schedulesCreated = 0;
            
            // Générer les dates pour chaque jour de la période
            $currentDate = $today->copy();
            $dateRange = [];
            
            while ($currentDate->lte($endDate)) {
                $dateRange[] = $currentDate->copy()->format('Y-m-d');
                $currentDate->addDay();
            }
            
            // Pour chaque date, générer les emplois du temps en limitant à 2 nourritures par jour
            foreach ($dateRange as $date) {
                $dateCarbon = Carbon::parse($date);
                
                // Vérifier combien de nourritures sont déjà programmées pour cette date
                $existingSchedulesCount = FoodSchedule::whereDate('scheduled_date', $date)->count();
                
                // Si déjà 2 ou plus, passer à la date suivante
                if ($existingSchedulesCount >= 2) {
                    continue;
                }
                
                // Nombre de créneaux disponibles
                $availableSlots = 2 - $existingSchedulesCount;
                
                // Trouver les nourritures qui devraient être programmées ce jour
                $foodsForToday = [];
                
                foreach ($foods as $food) {
                    $shouldSchedule = false;
                    $dayOfWeek = $dateCarbon->dayOfWeek; // 0 (dimanche) à 6 (samedi)
                    
                    switch ($food->frequency) {
                        case 'daily':
                            $shouldSchedule = true;
                            break;
                        case 'alternate_days':
                            $shouldSchedule = $dateCarbon->diffInDays($today) % 2 == 0;
                            break;
                        case 'weekly':
                            $shouldSchedule = $dayOfWeek == 1; // Lundi
                            break;
                        case 'weekdays':
                            $shouldSchedule = $dayOfWeek >= 1 && $dayOfWeek <= 5; // Lundi à vendredi
                            break;
                        case 'weekends':
                            $shouldSchedule = $dayOfWeek == 0 || $dayOfWeek == 6; // Dimanche ou samedi
                            break;
                    }
                    
                    if ($shouldSchedule) {
                        // Vérifier si cette nourriture n'est pas déjà programmée pour cette date
                        $exists = FoodSchedule::where('food_id', $food->id)
                            ->whereDate('scheduled_date', $date)
                            ->exists();
                            
                        if (!$exists) {
                            $foodsForToday[] = $food;
                        }
                    }
                }
                
                // Limiter le nombre de nourritures à ajouter selon les créneaux disponibles
                $foodsToSchedule = array_slice($foodsForToday, 0, $availableSlots);
                
                foreach ($foodsToSchedule as $food) {
                    FoodSchedule::create([
                        'food_id' => $food->id,
                        'scheduled_date' => $date,
                        'quantity' => $food->quantity_per_rabbit * $rabbitCount,
                        'unit' => $food->unit,
                        'is_completed' => false,
                    ]);
                    
                    $schedulesCreated++;
                }
            }
            
            return redirect()->route('food-schedules.index')
                ->with('success', $schedulesCreated . ' emploi(s) du temps alimentaire(s) généré(s) avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('food-schedules.index')
                ->with('error', 'Erreur lors de la génération des emplois du temps : ' . $e->getMessage());
        }
    }
    
    private function getDatesForFrequency($frequency, $startDate, $endDate)
    {
        $dates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 (dimanche) à 6 (samedi)
            
            switch ($frequency) {
                case 'daily':
                    $dates[] = $currentDate->copy();
                    break;
                    
                case 'alternate_days':
                    if ($currentDate->diffInDays($startDate) % 2 == 0) {
                        $dates[] = $currentDate->copy();
                    }
                    break;
                    
                case 'weekly':
                    if ($currentDate->dayOfWeek == 1) { // Lundi
                        $dates[] = $currentDate->copy();
                    }
                    break;
                    
                case 'weekdays':
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // Lundi à vendredi
                        $dates[] = $currentDate->copy();
                    }
                    break;
                    
                case 'weekends':
                    if ($dayOfWeek == 0 || $dayOfWeek == 6) { // Dimanche ou samedi
                        $dates[] = $currentDate->copy();
                    }
                    break;
            }
            
            $currentDate->addDay();
        }
        
        return $dates;
    }
    
    // Endpoint API pour vérifier l'état de santé des lapins
    public function checkRabbitHealth()
    {
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        
        // Vérifier si la table health_records existe
        if (Schema::hasTable('health_records')) {
            $deathCount = DB::table('health_records')
                ->whereBetween('recorded_at', [$lastWeekStart, $lastWeekEnd])
                ->where('type', 'death')
                ->count();
                
            $illnessCount = DB::table('health_records')
                ->whereBetween('recorded_at', [$lastWeekStart, $lastWeekEnd])
                ->where('type', 'illness')
                ->count();
                
            $status = ($deathCount > 1 || $illnessCount > 2) ? 'warning' : 'normal';
            
            return response()->json([
                'status' => $status,
                'deaths' => $deathCount,
                'illnesses' => $illnessCount
            ]);
        }
        
        // Si la table n'existe pas, retourner un statut normal
        return response()->json([
            'status' => 'normal',
            'deaths' => 0,
            'illnesses' => 0
        ]);
    }
}