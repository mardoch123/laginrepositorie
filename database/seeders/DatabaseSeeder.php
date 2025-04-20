<?php

namespace Database\Seeders;

use App\Models\Litter;
use App\Models\Rabbit;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            MedicationSeeder::class,
            TreatmentProtocolSeeder::class,
            FoodSeeder::class,
        ]);
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Créer quelques lapins
        $rabbit1 = Rabbit::create([
            'name' => 'Bugs',
            'identification_number' => 'R001',
            'gender' => 'male',
            'birth_date' => now()->subMonths(6),
            'breed' => 'Néo-Zélandais',
            'color' => 'Blanc',
            'notes' => 'Lapin reproducteur principal',
            'is_active' => true,
        ]);

        $rabbit2 = Rabbit::create([
            'name' => 'Lola',
            'identification_number' => 'R002',
            'gender' => 'female',
            'birth_date' => now()->subMonths(8),
            'breed' => 'Rex',
            'color' => 'Noir',
            'notes' => 'Lapine reproductrice',
            'is_active' => true,
        ]);

        $rabbit3 = Rabbit::create([
            'name' => 'Coco',
            'identification_number' => 'R003',
            'gender' => 'female',
            'birth_date' => now()->subMonths(7),
            'breed' => 'Californien',
            'color' => 'Blanc et noir',
            'notes' => 'Lapine reproductrice',
            'is_active' => true,
        ]);

        // Créer une portée
        $litter = Litter::create([
            'mother_id' => $rabbit2->id,
            'father_id' => $rabbit1->id,
            'breeding_date' => now()->subDays(30),
            'expected_birth_date' => now()->addDays(2),
            'expected_size' => 8,
            'status' => 'pregnant',
            'notes' => 'Première portée de Lola',
        ]);

        // Créer quelques rappels
        Reminder::create([
            'title' => 'Vérifier la naissance',
            'description' => 'Vérifier si Lola a mis bas',
            'due_date' => now()->addDays(2),
            'is_completed' => false,
            'priority' => 'high',
            'litter_id' => $litter->id,
        ]);

        Reminder::create([
            'title' => 'Vacciner Bugs',
            'description' => 'Vaccin annuel contre la myxomatose',
            'due_date' => now()->addDays(5),
            'is_completed' => false,
            'priority' => 'urgent',
            'rabbit_id' => $rabbit1->id,
        ]);

        Reminder::create([
            'title' => 'Commander des granulés',
            'description' => 'Stock de nourriture bas',
            'due_date' => now()->addDays(3),
            'is_completed' => false,
            'priority' => 'medium',
        ]);
    }
}
