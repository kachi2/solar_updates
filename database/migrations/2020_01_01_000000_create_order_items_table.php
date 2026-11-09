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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->double('order_No')->nullable();
            $table->string('product_name', 200)->nullable();
            $table->double('price')->nullable();
            $table->integer('qty')->nullable();
            $table->double('payable')->nullable();
            $table->string('image')->nullable();
            $table->text('design_image')->nullable();
            $table->double('design_fee')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
