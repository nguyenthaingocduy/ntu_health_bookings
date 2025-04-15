<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Time;
use Illuminate\Support\Str;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có
        Time::truncate();

        // Thêm dữ liệu mới
        $times = [
            ['id' => Str::uuid(), 'started_time' => '08:00:00'],
            ['id' => Str::uuid(), 'started_time' => '09:00:00'],
            ['id' => Str::uuid(), 'started_time' => '10:00:00'],
            ['id' => Str::uuid(), 'started_time' => '11:00:00'],
            ['id' => Str::uuid(), 'started_time' => '13:00:00'],
            ['id' => Str::uuid(), 'started_time' => '14:00:00'],
            ['id' => Str::uuid(), 'started_time' => '15:00:00'],
            ['id' => Str::uuid(), 'started_time' => '16:00:00'],
            ['id' => Str::uuid(), 'started_time' => '17:00:00'],
            ['id' => Str::uuid(), 'started_time' => '18:00:00'],
            ['id' => Str::uuid(), 'started_time' => '19:00:00'],
        ];
        
        foreach ($times as $time) {
            try {
                $timeModel = Time::create($time);
                $this->command->info("Đã thêm mốc thời gian: {$timeModel->started_time}");
            } catch (\Exception $e) {
                $this->command->error("Lỗi khi thêm mốc thời gian {$time['started_time']}: {$e->getMessage()}");
            }
        }
    }
} 