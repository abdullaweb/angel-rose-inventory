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
        Schema::create('product_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjustment_id')->constrained('product_adjustments')->onDelete('cascade');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->enum('type', ['increase', 'decrease']);
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_adjustment_details');
    }
};
