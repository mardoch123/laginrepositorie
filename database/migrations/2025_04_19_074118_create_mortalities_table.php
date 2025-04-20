<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mortalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('breeding_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('illness_id')->nullable()->constrained()->nullOnDelete();
            $table->date('death_date');
            $table->string('death_cause');
            $table->integer('kit_count')->nullable();
            $table->enum('kit_sex', ['unknown', 'male', 'female', 'mixed'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mortalities');
    }
};