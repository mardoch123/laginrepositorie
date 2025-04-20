<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Ajouter les nouveaux champs nécessaires
            $table->enum('frequency', ['daily', 'weekly', 'custom'])->nullable()->after('priority');
            $table->time('time')->nullable()->after('frequency');
            $table->json('days_of_week')->nullable()->after('time');
            $table->integer('interval_days')->nullable()->after('days_of_week');
            $table->timestamp('last_executed')->nullable()->after('interval_days');
            $table->boolean('active')->default(true)->after('last_executed');
        });
    }

    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Supprimer les champs ajoutés
            $table->dropColumn([
                'frequency',
                'time',
                'days_of_week',
                'interval_days',
                'last_executed',
                'active'
            ]);
        });
    }
};