<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->tinyInteger('status', false, true)->default(1)->comment(<<<EOL
                0 -> inactive
                1 -> active
            EOL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
