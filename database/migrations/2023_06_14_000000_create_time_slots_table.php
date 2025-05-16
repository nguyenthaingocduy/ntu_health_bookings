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
        // Kiểm tra xem bảng đã tồn tại chưa
        if (!Schema::hasTable('time_slots')) {
            Schema::create('time_slots', function (Blueprint $table) {
                $table->id();
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Thêm dữ liệu mẫu
            $timeSlots = [
                ['start_time' => '08:00:00', 'end_time' => '09:00:00'],
                ['start_time' => '09:00:00', 'end_time' => '10:00:00'],
                ['start_time' => '10:00:00', 'end_time' => '11:00:00'],
                ['start_time' => '11:00:00', 'end_time' => '12:00:00'],
                ['start_time' => '13:00:00', 'end_time' => '14:00:00'],
                ['start_time' => '14:00:00', 'end_time' => '15:00:00'],
                ['start_time' => '15:00:00', 'end_time' => '16:00:00'],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00'],
                ['start_time' => '17:00:00', 'end_time' => '18:00:00'],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00'],
                ['start_time' => '19:00:00', 'end_time' => '20:00:00'],
            ];

            foreach ($timeSlots as $timeSlot) {
                DB::table('time_slots')->insert([
                    'start_time' => $timeSlot['start_time'],
                    'end_time' => $timeSlot['end_time'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
