<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroceryCheckToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('grocery_check')->default(0)->after('on_demand_check');
            $table->text('groceryicon')->nullable()->after('grocery_check');
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
            $table->dropColumn(['grocery_check', 'groceryicon']);
        });
    }
}
