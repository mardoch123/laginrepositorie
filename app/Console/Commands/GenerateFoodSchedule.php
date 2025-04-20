<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenerateFoodSchedule extends Command
{
    protected $signature = 'food:generate-schedule';
    protected $description = 'Génère l\'emploi du temps des nourritures pour la semaine à venir';

    public function handle()
    {
        $this->info('Génération de l\'emploi du temps des nourritures...');

        try {
            // Vérifier la connexion à la base de données
            try {
                DB::connection()->getPdo();
                $this->info('Base de données connectée: ' . DB::connection()->getDatabaseName());
                
                // Afficher les tables existantes pour déboguer
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                $this->info('Tables existantes:');
                foreach ($tables as $table) {
                    $this->info('- ' . $table->name);
                }
            } catch (\Exception $e) {
                $this->error('Impossible de se connecter à la base de données: ' . $e->getMessage());
                return 1;
            }

            // Vérifier si les tables existent
            if (!Schema::hasTable('foods')) {
                $this->error('La table "foods" n\'existe pas. Veuillez exécuter les migrations.');
                return 1;
            }
            
            if (!Schema::hasTable('food_schedules')) {
                $this->error('La table "food_schedules" n\'existe pas. Veuillez exécuter les migrations.');
                return 1;
            }

            // Supprimer les emplois du temps non complétés du passé
            DB::table('food_schedules')
                ->where('is_completed', 0)
                ->whereDate('scheduled_date', '<', Carbon::today()->toDateString())
                ->delete();

            $today = Carbon::today();
            $endDate = $today->copy()->addDays(7);
            
            // Vérifier si la table rabbits existe
            if (!Schema::hasTable('rabbits')) {
                $this->error('La table "rabbits" n\'existe pas. Veuillez exécuter les migrations.');
                return 1;
            }
            
            $rabbitCount = DB::table('rabbits')->where('is_active', 1)->count();
            $this->info('Nombre de lapins actifs: ' . $rabbitCount);

            if ($rabbitCount == 0) {
                $this->error('Aucun lapin actif trouvé pour générer des emplois du temps.');
                return 1;
            }

            // Récupérer les nourritures actives
            $foods = DB::table('foods')->where('is_active', 1)->get();
            $this->info('Nombre de nourritures actives: ' . $foods->count());
            
            if ($foods->count() == 0) {
                $this->error('Aucune nourriture active trouvée pour générer des emplois du temps.');
                return 1;
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
                $this->info('Traitement de la date: ' . $dateCarbon->format('d/m/Y'));
                
                // Vérifier combien de nourritures sont déjà programmées pour cette date
                $existingSchedulesCount = DB::table('food_schedules')
                    ->whereDate('scheduled_date', $date)
                    ->count();
                
                $this->info('Nombre d\'emplois du temps existants pour cette date: ' . $existingSchedulesCount);
                
                // Si déjà 2 ou plus, passer à la date suivante
                if ($existingSchedulesCount >= 2) {
                    $this->info('Limite de 2 nourritures par jour déjà atteinte pour le ' . $dateCarbon->format('d/m/Y'));
                    continue;
                }
                
                // Nombre de créneaux disponibles
                $availableSlots = 2 - $existingSchedulesCount;
                $this->info('Créneaux disponibles: ' . $availableSlots);
                
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
                        $exists = DB::table('food_schedules')
                            ->where('food_id', $food->id)
                            ->whereDate('scheduled_date', $date)
                            ->exists();
                            
                        if (!$exists) {
                            $foodsForToday[] = $food;
                        }
                    }
                }
                
                $this->info('Nourritures éligibles pour cette date: ' . count($foodsForToday));
                
                // Limiter le nombre de nourritures à ajouter selon les créneaux disponibles
                $foodsToSchedule = array_slice($foodsForToday, 0, $availableSlots);
                
                foreach ($foodsToSchedule as $food) {
                    DB::table('food_schedules')->insert([
                        'food_id' => $food->id,
                        'scheduled_date' => $date,
                        'quantity' => $food->quantity_per_rabbit * $rabbitCount,
                        'unit' => $food->unit,
                        'is_completed' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $schedulesCreated++;
                    $this->info('Emploi du temps créé pour ' . $food->name . ' le ' . $dateCarbon->format('d/m/Y'));
                }
            }

            $this->info($schedulesCreated . ' emploi(s) du temps alimentaire(s) généré(s) avec succès.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Erreur lors de la génération des emplois du temps : ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}