<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfluencerAttributesOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influencer_attributes_options', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->bigInteger('influencer_attributes_id')->unsigned()->nullable();
            $table->string('attribute_value', 10)->nullable();
            $table->smallInteger('position')->default(1);
            $table->timestamps();

            $table->index('position');
            $table->foreign('influencer_attributes_id')->references('id')->on('influencer_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('influencer_attributes_options');
    }
}
