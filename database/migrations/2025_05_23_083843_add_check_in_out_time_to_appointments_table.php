<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable()->after('status');
            $table->timestamp('check_out_time')->nullable()->after('check_in_time');
            $table->boolean('is_completed')->default(false)->after('check_out_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['check_in_time', 'check_out_time', 'is_completed']);
        });
    }
};
