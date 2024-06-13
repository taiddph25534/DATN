<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToVouchersTable extends Migration
{
    /**
     * Thực hiện các migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'type')) {
                $table->string('type')->nullable(false);
            }
            if (!Schema::hasColumn('vouchers', 'minPurchaseAmount')) {
                $table->decimal('minPurchaseAmount', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'maxUsage')) {
                $table->integer('maxUsage')->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'usedCount')) {
                $table->integer('usedCount')->default(0);
            }
            if (!Schema::hasColumn('vouchers', 'applicableProducts')) {
                $table->string('applicableProducts')->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'distribution_channels')) {
                $table->string('distribution_channels')->nullable();
            }
            if (!Schema::hasColumn('vouchers', 'created_count')) {
                $table->integer('created_count')->default(0);
            }
            if (!Schema::hasColumn('vouchers', 'remaining_count')) {
                $table->integer('remaining_count')->default(0);
            }
        });
    }

    /**
     * Hoàn tác các migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('vouchers', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('vouchers', 'minPurchaseAmount')) {
                $table->dropColumn('minPurchaseAmount');
            }
            if (Schema::hasColumn('vouchers', 'maxUsage')) {
                $table->dropColumn('maxUsage');
            }
            if (Schema::hasColumn('vouchers', 'usedCount')) {
                $table->dropColumn('usedCount');
            }
            if (Schema::hasColumn('vouchers', 'applicableProducts')) {
                $table->dropColumn('applicableProducts');
            }
            if (Schema::hasColumn('vouchers', 'distribution_channels')) {
                $table->dropColumn('distribution_channels');
            }
            if (Schema::hasColumn('vouchers', 'created_count')) {
                $table->dropColumn('created_count');
            }
            if (Schema::hasColumn('vouchers', 'remaining_count')) {
                $table->dropColumn('remaining_count');
            }
        });
    }
}
