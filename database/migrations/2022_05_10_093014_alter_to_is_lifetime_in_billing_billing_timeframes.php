<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterToIsLifetimeInBillingBillingTimeframes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('is_lifetime_in_billing_billing_timeframes', function (Blueprint $table) {
            $table->renameColumn('is_timelimit', 'is_lifetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('is_lifetime_in_billing_billing_timeframes', function (Blueprint $table) {
            //
        });
    }
}
