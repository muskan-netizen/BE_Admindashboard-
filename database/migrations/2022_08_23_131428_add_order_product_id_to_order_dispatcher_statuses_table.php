<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderProductIdToOrderDispatcherStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_order_dispatcher_statuses', function (Blueprint $table) {
            $table->string('order_vendor_product_id')->default(0)->nullable()->comment('for single product dispatch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_order_dispatcher_statuses', function (Blueprint $table) {
            //
        });
    }
}
