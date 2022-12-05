<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfluencerAttributesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influencer_attributes_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('influ_attr_id')->unsigned();
            $table->bigInteger('influ_cat_id')->unsigned();
            $table->timestamps();

            $table->foreign('influ_attr_id')->references('id')->on('influencer_attributes')->onDelete('cascade');
            $table->foreign('influ_cat_id')->references('id')->on('influencer_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('influencer_attributes_categories');
    }
}
