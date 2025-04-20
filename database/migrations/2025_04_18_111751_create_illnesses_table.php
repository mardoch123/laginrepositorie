<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIllnessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('illnesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')->constrained()->onDelete('cascade');
            $table->string('type'); // coccidiosis, pasteurellosis, myxomatosis, etc.
            $table->string('severity')->default('mild'); // mild, moderate, severe
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->date('detection_date');
            $table->date('recovery_date')->nullable();
            $table->string('status')->default('active'); // active, recovered, fatal
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
        Schema::dropIfExists('illnesses');
    }
}