<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateProductAddons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_product_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimate_product_id')->references('id')->on('estimate_products')->onDelete('cascade');
            $table->foreignId('estimate_addon_id')->references('id')->on('estimate_addon_sets')->onDelete('cascade');
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
        Schema::dropIfExists('estimate_product_addons');
    }
}
