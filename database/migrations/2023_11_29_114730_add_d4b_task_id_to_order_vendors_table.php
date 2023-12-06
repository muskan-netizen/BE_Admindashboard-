<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddD4bTaskIdToOrderVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->uuid('d4b_task_id')->nullable(); // Make the 'd4b_task_id' column nullable
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->dropColumn('d4b_task_id');
        });
    }
}