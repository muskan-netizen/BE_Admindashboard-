<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingPaymentTransationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payment_transations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('billing_subscription_id')->index('FK_billing_payment_transations_1');
            $table->string('payment_method', 50)->nullable();
            $table->string('receipt', 200)->nullable();
            $table->decimal('paid_amount', 12)->nullable();
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_payment_transations');
    }
}
