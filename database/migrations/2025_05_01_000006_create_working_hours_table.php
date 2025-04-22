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
        Schema::create('working_hours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('day_of_week'); // 0 = Sunday, 6 = Saturday
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
        
        // Insert default working hours
        $days = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy',
        ];
        
        foreach ($days as $day => $name) {
            DB::table('working_hours')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'day_of_week' => $day,
                'open_time' => $day == 0 ? null : '08:00:00', // Closed on Sunday
                'close_time' => $day == 0 ? null : '17:00:00', // Closed on Sunday
                'is_closed' => $day == 0, // Closed on Sunday
                'note' => $day == 0 ? 'Nghỉ' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_hours');
    }
};
