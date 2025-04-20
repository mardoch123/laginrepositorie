<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identification_number')->unique()->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('breed')->nullable();
            $table->string('cage')->nullable();
            $table->enum('status', ['alive', 'dead', 'sold', 'given'])->default('alive');
            $table->text('notes')->nullable();
            $table->string('color')->nullable();
            $table->float('weight')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapins');
    }
};