<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use Carbon\Carbon;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Liste des médicaments courants pour les lapins disponibles au Bénin
        $medications = [
            [
                'name' => 'Ivermectine',
                'dosage' => '0.2-0.4 mg/kg de poids corporel',
                'frequency' => 'monthly',
                'notes' => 'Antiparasitaire efficace contre les parasites internes et externes. Disponible dans les pharmacies vétérinaires à Cotonou et Porto-Novo.',
            ],
            [
                'name' => 'Albendazole',
                'dosage' => '20 mg/kg de poids corporel',
                'frequency' => 'monthly',
                'notes' => 'Vermifuge pour le traitement des parasites intestinaux. Disponible sous forme de comprimés ou de suspension.',
            ],
            [
                'name' => 'Oxytétracycline',
                'dosage' => '10-20 mg/kg de poids corporel',
                'frequency' => 'daily',
                'notes' => 'Antibiotique à large spectre pour traiter diverses infections bactériennes. Administrer pendant 5-7 jours consécutifs.',
            ],
            [
                'name' => 'Enrofloxacine',
                'dosage' => '5-10 mg/kg de poids corporel',
                'frequency' => 'daily',
                'notes' => 'Antibiotique pour les infections respiratoires et digestives. Traitement de 5 jours.',
            ],
            [
                'name' => 'Sulfadimidine',
                'dosage' => '100 mg/kg le premier jour, puis 50 mg/kg',
                'frequency' => 'daily',
                'notes' => 'Pour la prévention et le traitement de la coccidiose. Traitement de 3-5 jours.',
            ],
            [
                'name' => 'Amprolium',
                'dosage' => '20 mg/kg de poids corporel',
                'frequency' => 'daily',
                'notes' => 'Anticoccidien, à administrer dans l\'eau de boisson pendant 5 jours.',
            ],
            [
                'name' => 'Vitamine AD3E',
                'dosage' => '0.5-1 ml par lapin',
                'frequency' => 'monthly',
                'notes' => 'Supplément vitaminique pour renforcer l\'immunité et favoriser la croissance.',
            ],
            [
                'name' => 'Multivitamines',
                'dosage' => '1 ml pour 2 litres d\'eau',
                'frequency' => 'weekly',
                'notes' => 'Complément alimentaire pour améliorer la santé générale et la reproduction.',
            ],
            [
                'name' => 'Sel de réhydratation',
                'dosage' => '1 sachet pour 1 litre d\'eau',
                'frequency' => 'daily',
                'notes' => 'Pour traiter la déshydratation en cas de diarrhée. Administrer pendant 2-3 jours.',
            ],
            [
                'name' => 'Huile de neem',
                'dosage' => 'Application locale',
                'frequency' => 'weekly',
                'notes' => 'Remède naturel contre les parasites externes comme les puces et les tiques. Disponible localement.',
            ],
        ];

        foreach ($medications as $med) {
            Medication::create([
                'name' => $med['name'],
                'dosage' => $med['dosage'],
                'frequency' => $med['frequency'],
                'start_date' => Carbon::now(),
                'end_date' => null,
                'notes' => $med['notes'],
            ]);
        }
    }
}