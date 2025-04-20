<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('litters', function (Blueprint $table) {
            if (!Schema::hasColumn('litters', 'breeding_id')) {
                $table->foreignId('breeding_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('litters', function (Blueprint $table) {
            $table->dropForeign(['breeding_id']);
            $table->dropColumn('breeding_id');
        });
    }
};