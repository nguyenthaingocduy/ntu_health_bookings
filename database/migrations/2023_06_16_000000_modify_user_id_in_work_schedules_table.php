<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra xem bảng work_schedules có tồn tại không
        if (Schema::hasTable('work_schedules')) {
            // Xóa khóa ngoại nếu có
            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không có khóa ngoại
            }

            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->dropForeign(['time_slot_id']);
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không có khóa ngoại
            }

            // Thay đổi kiểu dữ liệu
            DB::statement('ALTER TABLE work_schedules MODIFY user_id VARCHAR(36)');
            DB::statement('ALTER TABLE work_schedules MODIFY time_slot_id VARCHAR(36)');

            // Thêm lại khóa ngoại
            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users');
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không thể thêm khóa ngoại
            }

            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->foreign('time_slot_id')->references('id')->on('time_slots');
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không thể thêm khóa ngoại
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kiểm tra xem bảng work_schedules có tồn tại không
        if (Schema::hasTable('work_schedules')) {
            // Xóa khóa ngoại nếu có
            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không có khóa ngoại
            }

            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->dropForeign(['time_slot_id']);
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không có khóa ngoại
            }

            // Thay đổi kiểu dữ liệu trở lại
            DB::statement('ALTER TABLE work_schedules MODIFY user_id BIGINT UNSIGNED');
            DB::statement('ALTER TABLE work_schedules MODIFY time_slot_id BIGINT UNSIGNED');

            // Thêm lại khóa ngoại
            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users');
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không thể thêm khóa ngoại
            }

            try {
                Schema::table('work_schedules', function (Blueprint $table) {
                    $table->foreign('time_slot_id')->references('id')->on('time_slots');
                });
            } catch (\Exception $e) {
                // Bỏ qua lỗi nếu không thể thêm khóa ngoại
            }
        }
    }
};
