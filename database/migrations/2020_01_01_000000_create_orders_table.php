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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('order_No', 222)->nullable();
            $table->string('external_ref')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('payment_method')->nullable();
            $table->double('amount')->nullable();
            $table->integer('shipping_id')->nullable();
            $table->integer('is_delivered')->nullable();
            $table->integer('is_paid')->nullable();
            $table->integer('dispatch_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
