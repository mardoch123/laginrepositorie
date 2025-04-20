<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rabbits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identification_number')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('breed');
            $table->string('color')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rabbits');
    }
};