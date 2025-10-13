t<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEcommerceCheckToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('ecommerce_check')->default(0)->after('grocery_check')->comment('0-No, 1-Yes');
            $table->text('ecommerceicon')->nullable()->after('ecommerce_check');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn(['ecommerce_check', 'ecommerceicon']);
        });
    }
}
