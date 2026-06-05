<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'driver_share_otp')) {
                $table->string('driver_share_otp', 10)->nullable()->unique()->after('order_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'driver_share_otp')) {
                $table->dropUnique(['driver_share_otp']);
                $table->dropColumn('driver_share_otp');
            }
        });
    }
};
