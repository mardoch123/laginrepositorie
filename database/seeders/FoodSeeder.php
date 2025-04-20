<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foods = [
            [
                'name' => 'Granulés',
                'description' => 'Aliment complet pour lapins sous forme de granulés',
                'frequency' => 'daily',
                'quantity_per_rabbit' => 100,
                'unit' => 'g',
                'is_active' => true,
                'notes' => 'À distribuer matin et soir',
            ],
            [
                'name' => 'Feuilles de Moringa',
                'description' => 'Feuilles riches en protéines et minéraux',
                'frequency' => 'alternate_days',
                'quantity_per_rabbit' => 50,
                'unit' => 'g',
                'is_active' => true,
                'notes' => 'Excellente source de vitamines et minéraux',
            ],
            [
                'name' => 'Feuilles de Patate Douce',
                'description' => 'Feuilles vertes riches en nutriments',
                'frequency' => 'weekly',
                'quantity_per_rabbit' => 60,
                'unit' => 'g',
                'is_active' => true,
                'notes' => 'Bien laver avant distribution',
            ],
            [
                'name' => 'Feuilles de Maïs',
                'description' => 'Feuilles de maïs fraîches',
                'frequency' => 'weekdays',
                'quantity_per_rabbit' => 40,
                'unit' => 'g',
                'is_active' => true,
                'notes' => 'Distribuer de préférence le matin',
            ],
            [
                'name' => 'Feuilles de Laitue',
                'description' => 'Feuilles de laitue fraîches',
                'frequency' => 'weekends',
                'quantity_per_rabbit' => 30,
                'unit' => 'g',
                'is_active' => true,
                'notes' => 'À donner en petite quantité pour éviter les diarrhées',
            ],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}