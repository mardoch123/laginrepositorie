<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table temporaire existe déjà et la supprimer si c'est le cas
        $tableExists = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='treatments_new'");
        if (!empty($tableExists)) {
            DB::statement('DROP TABLE treatments_new');
        }

        // Pour SQLite, nous devons supprimer et recréer la contrainte
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Créer une nouvelle table avec la structure correcte
        DB::statement('CREATE TABLE treatments_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            rabbit_id INTEGER NOT NULL,
            medication_id INTEGER NOT NULL,
            scheduled_at DATETIME NOT NULL,
            completed_at DATETIME NULL,
            status VARCHAR(255) NOT NULL CHECK(status IN ("pending", "completed", "cancelled", "skipped")),
            notes TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (rabbit_id) REFERENCES rabbits(id) ON DELETE CASCADE,
            FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE
        )');
        
        // Copier les données de l'ancienne table vers la nouvelle
        DB::statement('INSERT INTO treatments_new SELECT id, rabbit_id, medication_id, scheduled_at, completed_at, status, notes, created_at, updated_at FROM treatments');
        
        // Supprimer l'ancienne table
        DB::statement('DROP TABLE treatments');
        
        // Renommer la nouvelle table avec le nom original
        DB::statement('ALTER TABLE treatments_new RENAME TO treatments');
        
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // C'est une migration de correction, pas besoin de méthode down
    }
};