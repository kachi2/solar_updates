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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('name')->nullable();
            $table->double('price')->nullable();
            $table->double('sale_price')->nullable();
            $table->string('admin_id')->nullable();
            $table->string('image')->nullable();
            $table->text('gallery')->nullable();
            $table->integer('status')->nullable();
            $table->double('percentage')->nullable();
            $table->text('description')->nullable();
            $table->double('design_fee')->nullable();
            $table->integer('views')->nullable();
            $table->integer('order_count')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedBigInteger('sub_category_id')->index('products_sub_category_id_foreign');
            $table->text('specification')->nullable();
            $table->string('exchange_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
