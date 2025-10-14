<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vendor_slots', 'grocery')) {
            Schema::table('vendor_slots', function (Blueprint $table) {
                $table->tinyInteger('grocery')->default(0)->comment('0 = no, 1 = yes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vendor_slots', 'grocery')) {
            Schema::table('vendor_slots', function (Blueprint $table) {
                $table->dropColumn('grocery');
            });
        }
    }
};


