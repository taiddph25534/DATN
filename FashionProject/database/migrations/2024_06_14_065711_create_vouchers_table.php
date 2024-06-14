<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Thực hiện các migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('discount_type'); // percentage or fixed
            $table->decimal('discount_value', 8, 2);
            $table->date('expiry_date');
            $table->decimal('min_purchase_amount', 8, 2)->nullable();
            $table->integer('point_required');
            $table->integer('max_usage')->nullable();
            $table->integer('used_count')->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('created_count')->default(0);
            $table->integer('remaining_count')->default(0);
            $table->string('distribution_channels')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Hoàn tác các migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
