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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('colorID');
            $table->unsignedBigInteger('sizeID');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->string('type');
            $table->timestamps();

            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('colorID')->references('id')->on('product_colors')->onDelete('cascade');
            $table->foreign('sizeID')->references('id')->on('product_sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
