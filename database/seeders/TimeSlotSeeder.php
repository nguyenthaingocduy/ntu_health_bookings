<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define time slots for health check-ups (weekdays only)
        $timeSlots = [
            // Morning slots
            ['start_time' => '08:00', 'end_time' => '08:30'],
            ['start_time' => '08:30', 'end_time' => '09:00'],
            ['start_time' => '09:00', 'end_time' => '09:30'],
            ['start_time' => '09:30', 'end_time' => '10:00'],
            ['start_time' => '10:00', 'end_time' => '10:30'],
            ['start_time' => '10:30', 'end_time' => '11:00'],
            ['start_time' => '11:00', 'end_time' => '11:30'],
            
            // Afternoon slots
            ['start_time' => '13:30', 'end_time' => '14:00'],
            ['start_time' => '14:00', 'end_time' => '14:30'],
            ['start_time' => '14:30', 'end_time' => '15:00'],
            ['start_time' => '15:00', 'end_time' => '15:30'],
            ['start_time' => '15:30', 'end_time' => '16:00'],
            ['start_time' => '16:00', 'end_time' => '16:30'],
            ['start_time' => '16:30', 'end_time' => '17:00'],
        ];

        foreach ($timeSlots as $slot) {
            // Create slots for weekdays (Monday = 1, Friday = 5)
            for ($day = 1; $day <= 5; $day++) {
                TimeSlot::create([
                    'id' => Str::uuid(),
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'day_of_week' => $day,
                    'is_available' => true,
                    'max_appointments' => 3, // Allow multiple staff to book the same time slot
                ]);
            }
        }
    }
}
