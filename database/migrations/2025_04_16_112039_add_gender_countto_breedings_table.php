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
        Schema::table('breedings', function (Blueprint $table) {
            $table->integer('number_of_males')->nullable()->after('number_of_kits');
            $table->integer('number_of_females')->nullable()->after('number_of_males');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->dropColumn(['number_of_males', 'number_of_females']);
        });
    }
};