<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table 'food' existe (au singulier)
        if (Schema::hasTable('food') && !Schema::hasTable('foods')) {
            // Renommer la table 'food' en 'foods'
            Schema::rename('food', 'foods');
        }
        
        // Si la table 'foods' n'existe pas, la créer
        if (!Schema::hasTable('foods')) {
            Schema::create('foods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('frequency');
                $table->decimal('quantity_per_rabbit', 8, 2);
                $table->string('unit');
                $table->boolean('is_active')->default(true);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
        
        // Si la table 'food_schedules' n'existe pas, la créer
        if (!Schema::hasTable('food_schedules')) {
            Schema::create('food_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('food_id')->constrained('foods');
                $table->integer('day_of_week')->nullable();
                $table->dateTime('scheduled_date');
                $table->decimal('quantity', 8, 2);
                $table->string('unit');
                $table->boolean('is_completed')->default(false);
                $table->dateTime('completed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Ne rien faire en cas de rollback
    }
};