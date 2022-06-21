<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableVendorsAddColumnWeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->tinyInteger('set_weight_price')->nullable();
            $table->string('estimation_base_weight')->nullable();
            $table->string('estimation_base_price')->nullable();
            $table->string('estimation_addition_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('set_weight_price');
            $table->dropColumn('estimation_base_weight');
            $table->dropColumn('estimation_base_price');
            $table->dropColumn('estimation_addition_price');
        });
    }
}
