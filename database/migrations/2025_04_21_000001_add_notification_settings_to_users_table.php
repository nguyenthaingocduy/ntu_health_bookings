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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications_enabled')->default(true)->after('status');
            $table->boolean('notify_appointment_confirmation')->default(true)->after('email_notifications_enabled');
            $table->boolean('notify_appointment_reminder')->default(true)->after('notify_appointment_confirmation');
            $table->boolean('notify_appointment_cancellation')->default(true)->after('notify_appointment_reminder');
            $table->boolean('notify_promotions')->default(true)->after('notify_appointment_cancellation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications_enabled',
                'notify_appointment_confirmation',
                'notify_appointment_reminder',
                'notify_appointment_cancellation',
                'notify_promotions'
            ]);
        });
    }
};
