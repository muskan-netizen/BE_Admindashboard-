<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('total')->nullable();
            $table->string('discount')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 Pending 1 Accepted');
            $table->timestamps();

            $table->foreign('prescription_id')->references('id')->on('bid_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids');
    }
}
