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
                $table->unsignedBigInteger('appointment_id');
                $table->dateTime('reminder_date');
                $table->text('message');
                $table->enum('reminder_type', ['email', 'sms', 'both'])->default('email');
                $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
                $table->unsignedBigInteger('created_by');
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
