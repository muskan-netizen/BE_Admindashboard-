<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLuxuryOptionIdToMobileBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobile_banners', function (Blueprint $table) {
            $table->integer('luxury_option_id')->default(1)->after('link')->comment('Luxury option ID for banner filtering');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobile_banners', function (Blueprint $table) {
            $table->dropColumn('luxury_option_id');
        });
    }
}
