<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferencesNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('client_preferences_new', function (Blueprint $table) {
                $table->id();
                $table->string('client_code', 10)->unique()->nullable();
                $table->string('gtag_id')->nullable();
                $table->string('fpixel_id')->nullable();
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
        Schema::dropIfExists('client_preferences_new');
    }
}
