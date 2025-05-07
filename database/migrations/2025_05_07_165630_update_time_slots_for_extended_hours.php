<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\TimeSlot;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa tất cả các khung giờ hiện tại
        DB::table('time_slots')->delete();

        // Tạo các khung giờ mới với khoảng thời gian 1 giờ
        $timeSlots = [];
        $days = [1, 2, 3, 4, 5, 6, 7]; // Thứ 2 đến Chủ nhật (1-7)
        $startHours = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]; // Giờ bắt đầu từ 8h đến 19h (kết thúc 20h)

        foreach ($days as $day) {
            foreach ($startHours as $hour) {
                $startTime = sprintf('%02d:00:00', $hour);
                $endTime = sprintf('%02d:00:00', $hour + 1);

                $timeSlots[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'day_of_week' => $day,
                    'is_available' => true,
                    'max_appointments' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('time_slots')->insert($timeSlots);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa tất cả các khung giờ hiện tại
        DB::table('time_slots')->delete();

        // Tạo lại các khung giờ với khoảng thời gian 1 giờ, chỉ từ thứ 2 đến thứ 6, từ 8h đến 17h
        $timeSlots = [];
        $days = [1, 2, 3, 4, 5]; // Thứ 2 đến thứ 6
        $startHours = [8, 9, 10, 11, 13, 14, 15, 16]; // Giờ bắt đầu từ 8h đến 16h, bỏ qua giờ nghỉ trưa (12h)

        foreach ($days as $day) {
            foreach ($startHours as $hour) {
                $startTime = sprintf('%02d:00:00', $hour);
                $endTime = sprintf('%02d:00:00', $hour + 1);

                $timeSlots[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'day_of_week' => $day,
                    'is_available' => true,
                    'max_appointments' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('time_slots')->insert($timeSlots);
    }
};
