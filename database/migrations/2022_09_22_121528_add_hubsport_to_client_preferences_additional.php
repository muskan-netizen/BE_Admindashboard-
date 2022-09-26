<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHubsportToClientPreferencesAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences_additional', function (Blueprint $table) {
            $table->tinyInteger('is_hubspot_enable')->nullable()->default(0)->comment('0-No, 1-Yes');
            $table->string('hubspot_access_token')->nullable();
            $table->dateTime('hubspot_last_update')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences_additional', function (Blueprint $table) {
           
        });
    }
}
