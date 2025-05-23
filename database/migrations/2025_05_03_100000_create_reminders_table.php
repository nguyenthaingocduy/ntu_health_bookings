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
        // Kiểm tra xem bảng reminders đã tồn tại chưa
        if (!Schema::hasTable('reminders')) {
            Schema::create('reminders', function (Blueprint $table) {
                $table->id();
                $table->string('appointment_id'); // Changed to string to match appointments.id (UUID)
                $table->dateTime('reminder_date');
                $table->text('message');
                $table->enum('reminder_type', ['email', 'sms', 'both'])->default('email');
                $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
                $table->string('created_by'); // Changed to string to match users.id (UUID)
                $table->dateTime('sent_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
