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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->double('amount')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('external_ref', 222)->nullable();
            $table->string('type', 150)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('order_No')->nullable();
            $table->integer('prev_balance')->nullable();
            $table->integer('avail_balance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
