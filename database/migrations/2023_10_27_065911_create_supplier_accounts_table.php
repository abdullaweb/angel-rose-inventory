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
        Schema::create('supplier_accounts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('purchase_id')->nullable();
            $table->tinyInteger('supplier_id')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->date('date')->nullable();
            $table->string('paid_status')->nullable();
            $table->string('paid_source')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('note')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_accounts');
    }
};
