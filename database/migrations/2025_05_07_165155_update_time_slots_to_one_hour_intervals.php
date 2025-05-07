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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không thể khôi phục chính xác các khung giờ cũ, nhưng có thể tạo lại các khung giờ 30 phút
        DB::table('time_slots')->delete();

        // Tạo các khung giờ với khoảng thời gian 30 phút (mặc định)
        $timeSlots = [];
        $days = [1, 2, 3, 4, 5]; // Thứ 2 đến thứ 6
        $startHours = [8, 9, 10, 11, 13, 14, 15, 16]; // Giờ bắt đầu từ 8h đến 16h, bỏ qua giờ nghỉ trưa (12h)

        foreach ($days as $day) {
            foreach ($startHours as $hour) {
                // Khung giờ đầu tiên của giờ (XX:00 - XX:30)
                $timeSlots[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'start_time' => sprintf('%02d:00:00', $hour),
                    'end_time' => sprintf('%02d:30:00', $hour),
                    'day_of_week' => $day,
                    'is_available' => true,
                    'max_appointments' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Khung giờ thứ hai của giờ (XX:30 - XX+1:00)
                $timeSlots[] = [
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'start_time' => sprintf('%02d:30:00', $hour),
                    'end_time' => sprintf('%02d:00:00', $hour + 1),
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
