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
        Schema::table('treatments', function (Blueprint $table) {
            $table->foreignId('breeding_id')->nullable()->after('rabbit_id')->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign(['breeding_id']);
            $table->dropColumn('breeding_id');
        });
    }
};
