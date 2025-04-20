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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('rabbits')->onDelete('cascade');
            $table->foreignId('father_id')->constrained('rabbits')->onDelete('cascade');
            $table->date('mating_date');
            $table->date('expected_birth_date')->nullable();
            $table->date('actual_birth_date')->nullable();
            $table->integer('number_of_kits')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breedings');
    }
};