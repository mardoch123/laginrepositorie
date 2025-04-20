<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rabbits', function (Blueprint $table) {
            if (!Schema::hasColumn('rabbits', 'litter_id')) {
                $table->foreignId('litter_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rabbits', function (Blueprint $table) {
            if (Schema::hasColumn('rabbits', 'litter_id')) {
                $table->dropForeign(['litter_id']);
                $table->dropColumn('litter_id');
            }
        });
    }
};