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
            $table->foreignId('breeding_id')->nullable()->constrained('breedings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rabbits', function (Blueprint $table) {
            $table->dropForeign(['breeding_id']);
            $table->dropColumn('breeding_id');
        });
    }
};