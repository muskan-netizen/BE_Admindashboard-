<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_subscriptions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_id');
            $table->integer('billing_price_id');
            $table->string('billing_plan_title', 200)->nullable();
            $table->string('billing_timeframe_title', 200)->nullable();
            $table->integer('billing_price');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('next_due_date')->nullable();
            $table->integer('billing_payment_id');
            $table->timestamps();
            $table->tinyInteger('status')->comment('(0= >Pending,1=>active, 2=>in active)');
            $table->string('slug', 150)->nullable();
            $table->tinyInteger('is_paid')->nullable()->default(0)->comment('(0=>\'Unpaid\', 1=>\'Paid\')');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_subscriptions');
    }
}
