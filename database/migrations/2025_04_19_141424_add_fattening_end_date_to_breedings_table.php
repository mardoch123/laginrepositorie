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
            $table->date('fattening_end_date')->nullable()->after('weaning_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->dropColumn('fattening_end_date');
        });
    }
};