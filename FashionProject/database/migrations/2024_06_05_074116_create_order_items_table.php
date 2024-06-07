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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderID');
            $table->unsignedBigInteger('variantID');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            // Thiết lập khóa ngoại
            $table->foreign('orderID')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
