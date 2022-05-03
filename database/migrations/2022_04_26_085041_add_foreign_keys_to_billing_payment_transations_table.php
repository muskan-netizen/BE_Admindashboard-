<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBillingPaymentTransationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_payment_transations', function (Blueprint $table) {
            $table->foreign(['billing_subscription_id'], 'FK_billing_payment_transations_1')->references(['id'])->on('billing_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_payment_transations', function (Blueprint $table) {
            $table->dropForeign('FK_billing_payment_transations_1');
        });
    }
}
