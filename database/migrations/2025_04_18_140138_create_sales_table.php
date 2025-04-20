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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('breeding_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sale_type')->default('individual'); // individual, group, breeding
            $table->integer('quantity')->default(1);
            $table->decimal('weight_kg', 8, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('sale_date');
            $table->string('customer_name')->nullable();
            $table->string('customer_contact')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};