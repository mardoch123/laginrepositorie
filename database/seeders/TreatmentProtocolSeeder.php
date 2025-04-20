<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use App\Models\Rabbit;
use App\Models\Treatment;
use Carbon\Carbon;

class TreatmentProtocolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Protocoles de traitement standard pour les lapins
        $protocols = [
            [
                'name' => 'Protocole d\'accueil',
                'description' => 'Traitement à administrer à l\'arrivée d\'un nouveau lapin',
                'treatments' => [
                    ['medication' => 'Ivermectine', 'days_after' => 1],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Albendazole', 'days_after' => 7],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole pré-reproduction',
                'description' => 'Traitement à administrer avant l\'accouplement',
                'treatments' => [
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole post-partum',
                'description' => 'Traitement à administrer après la mise bas',
                'treatments' => [
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 14],
                ]
            ],
            [
                'name' => 'Protocole sevrage',
                'description' => 'Traitement à administrer aux lapereaux au sevrage',
                'treatments' => [
                    ['medication' => 'Sulfadimidine', 'days_after' => 1, 'duration' => 3],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 4],
                    ['medication' => 'Multivitamines', 'days_after' => 4, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole préventif trimestriel',
                'description' => 'Traitement préventif à administrer tous les 3 mois',
                'treatments' => [
                    ['medication' => 'Ivermectine', 'days_after' => 1],
                    ['medication' => 'Albendazole', 'days_after' => 7],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                ]
            ],
        ];

        // Fonction pour appliquer un protocole à un lapin
        $this->command->info('Création des protocoles de traitement standard...');
    }

    /**
     * Applique un protocole de traitement à un lapin spécifique
     *
     * @param int $rabbitId ID du lapin
     * @param string $protocolName Nom du protocole
     * @param Carbon $startDate Date de début du protocole
     * @return void
     */
    public function applyProtocolToRabbit($rabbitId, $protocolName, Carbon $startDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now();
        }

        $rabbit = Rabbit::find($rabbitId);
        if (!$rabbit) {
            return;
        }

        // Trouver les protocoles correspondants
        $protocols = $this->getProtocols();
        $protocol = collect($protocols)->firstWhere('name', $protocolName);
        
        if (!$protocol) {
            return;
        }

        // Créer les traitements selon le protocole
        foreach ($protocol['treatments'] as $treatmentData) {
            $medication = Medication::where('name', $treatmentData['medication'])->first();
            
            if (!$medication) {
                continue;
            }

            $scheduledDate = $startDate->copy()->addDays($treatmentData['days_after'] - 1);
            
            // Créer le traitement principal
            Treatment::create([
                'rabbit_id' => $rabbitId,
                'medication_id' => $medication->id,
                'scheduled_at' => $scheduledDate,
                'status' => 'pending',
                'notes' => "Protocole: {$protocol['name']} - {$protocol['description']}",
            ]);
            
            // Si le traitement a une durée, créer les traitements suivants
            if (isset($treatmentData['duration']) && $treatmentData['duration'] > 1) {
                $frequency = $medication->frequency;
                $interval = 1; // par défaut, quotidien
                
                if ($frequency === 'weekly') {
                    $interval = 7;
                } elseif ($frequency === 'monthly') {
                    $interval = 30;
                }
                
                for ($i = 1; $i < $treatmentData['duration']; $i++) {
                    Treatment::create([
                        'rabbit_id' => $rabbitId,
                        'medication_id' => $medication->id,
                        'scheduled_at' => $scheduledDate->copy()->addDays($i * $interval),
                        'status' => 'pending',
                        'notes' => "Protocole: {$protocol['name']} - {$protocol['description']} (Jour " . ($i + 1) . ")",
                    ]);
                }
            }
        }
    }

    /**
     * Retourne la liste des protocoles de traitement
     *
     * @return array
     */
    public function getProtocols()
    {
        return [
            [
                'name' => 'Protocole d\'accueil',
                'description' => 'Traitement à administrer à l\'arrivée d\'un nouveau lapin',
                'treatments' => [
                    ['medication' => 'Ivermectine', 'days_after' => 1],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Albendazole', 'days_after' => 7],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole pré-reproduction',
                'description' => 'Traitement à administrer avant l\'accouplement',
                'treatments' => [
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole post-partum',
                'description' => 'Traitement à administrer après la mise bas',
                'treatments' => [
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                    ['medication' => 'Multivitamines', 'days_after' => 1, 'duration' => 14],
                ]
            ],
            [
                'name' => 'Protocole sevrage',
                'description' => 'Traitement à administrer aux lapereaux au sevrage',
                'treatments' => [
                    ['medication' => 'Sulfadimidine', 'days_after' => 1, 'duration' => 3],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 4],
                    ['medication' => 'Multivitamines', 'days_after' => 4, 'duration' => 7],
                ]
            ],
            [
                'name' => 'Protocole préventif trimestriel',
                'description' => 'Traitement préventif à administrer tous les 3 mois',
                'treatments' => [
                    ['medication' => 'Ivermectine', 'days_after' => 1],
                    ['medication' => 'Albendazole', 'days_after' => 7],
                    ['medication' => 'Vitamine AD3E', 'days_after' => 1],
                ]
            ],
        ];
    }
}