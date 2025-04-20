<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')->constrained()->onDelete('cascade');
            $table->text('symptoms');
            $table->date('observed_date');
            $table->text('additional_notes')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('appetite_level')->nullable();
            $table->string('activity_level')->nullable();
            $table->longText('ai_diagnosis')->nullable();
            $table->text('veterinarian_notes')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};