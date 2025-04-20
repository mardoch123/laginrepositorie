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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identification_number')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('breed')->nullable();
            $table->string('color')->nullable();
            $table->enum('status', ['alive', 'deceased', 'sold'])->default('alive');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};