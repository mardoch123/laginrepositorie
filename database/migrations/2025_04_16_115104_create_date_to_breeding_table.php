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
            $table->date('weaning_date')->nullable()->after('actual_birth_date');
            $table->date('fattening_start_date')->nullable()->after('weaning_date');
            $table->date('expected_fattening_end_date')->nullable()->after('fattening_start_date');
            $table->boolean('weaning_confirmed')->default(false)->after('number_of_females');
            $table->boolean('fattening_confirmed')->default(false)->after('weaning_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->dropColumn([
                'weaning_date',
                'fattening_start_date',
                'expected_fattening_end_date',
                'weaning_confirmed',
                'fattening_confirmed',
            ]);
        });
    }
};