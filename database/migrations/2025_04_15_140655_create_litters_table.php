<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('litters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('rabbits');
            $table->foreignId('father_id')->nullable()->constrained('rabbits');
            $table->date('breeding_date');
            $table->date('expected_birth_date')->nullable();
            $table->date('actual_birth_date')->nullable();
            $table->integer('expected_size')->nullable();
            $table->integer('born_alive')->nullable();
            $table->integer('born_dead')->nullable();
            $table->integer('current_count')->nullable();
            $table->date('weaning_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'breeding', 'pregnant', 'born', 'weaned', 'completed'])->default('planned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('litters');
    }
};