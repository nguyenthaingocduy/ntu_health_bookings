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
        // Xóa bảng reminders cũ
        Schema::dropIfExists('reminders');

        // Tạo lại bảng reminders với cấu trúc đúng
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id', 255);
            $table->dateTime('reminder_date');
            $table->text('message');
            $table->enum('reminder_type', ['email', 'sms', 'both'])->default('email');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa bảng reminders mới
        Schema::dropIfExists('reminders');

        // Tạo lại bảng reminders với cấu trúc cũ
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
        });
    }
};
