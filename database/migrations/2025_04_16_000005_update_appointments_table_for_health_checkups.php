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
            // Add new columns for health check-up appointments
            $table->uuid('doctor_id')->nullable()->after('notes');
            $table->uuid('time_slot_id')->nullable()->after('doctor_id');
            $table->dateTime('check_in_time')->nullable()->after('time_slot_id');
            $table->dateTime('check_out_time')->nullable()->after('check_in_time');
            $table->boolean('is_completed')->default(false)->after('check_out_time');
            $table->string('cancellation_reason')->nullable()->after('is_completed');
            
            // Add foreign keys
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('time_slot_id')->references('id')->on('time_slots')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['time_slot_id']);
            
            // Drop columns
            $table->dropColumn([
                'doctor_id',
                'time_slot_id',
                'check_in_time',
                'check_out_time',
                'is_completed',
                'cancellation_reason'
            ]);
        });
    }
};
