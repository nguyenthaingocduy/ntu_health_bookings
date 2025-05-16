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
        // Kiểm tra xem bảng đã tồn tại chưa
        if (!Schema::hasTable('work_schedules')) {
            Schema::create('work_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('date');
                $table->unsignedBigInteger('time_slot_id');
                $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
                $table->text('notes')->nullable();
                $table->timestamps();

                // Đảm bảo mỗi nhân viên chỉ có một lịch làm việc cho mỗi ngày và khung giờ
                $table->unique(['user_id', 'date', 'time_slot_id']);

                // Thêm khóa ngoại
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('time_slot_id')->references('id')->on('time_slots');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
