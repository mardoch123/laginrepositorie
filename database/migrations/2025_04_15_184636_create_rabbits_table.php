<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rabbits', function (Blueprint $table) {
            if (!Schema::hasColumn('rabbits', 'status')) {
                $table->string('status')->default('alive')->after('is_active');
            }
            
            if (!Schema::hasColumn('rabbits', 'cage_id')) {
                $table->foreignId('cage_id')->nullable()->after('is_active')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rabbits', function (Blueprint $table) {
            if (Schema::hasColumn('rabbits', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('rabbits', 'cage_id')) {
                $table->dropForeign(['cage_id']);
                $table->dropColumn('cage_id');
            }
        });
    }
};