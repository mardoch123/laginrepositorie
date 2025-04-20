<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('breeding_weight_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('breeding_id')->constrained()->onDelete('cascade');
        $table->integer('total_weight');
        $table->integer('average_weight');
        $table->date('recorded_at');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeding_weight_records');
    }
};
