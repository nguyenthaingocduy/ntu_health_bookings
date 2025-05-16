<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WorkingHour extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
        'note',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
    ];

    /**
     * Get the day name
     *
     * @return string
     */
    public function getDayNameAttribute()
    {
        $days = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy',
        ];

        return $days[$this->day_of_week] ?? 'Không xác định';
    }

    /**
     * Get the formatted open time
     *
     * @return string
     */
    public function getFormattedOpenTimeAttribute()
    {
        if ($this->is_closed || !$this->open_time) {
            return 'Đóng cửa';
        }

        // Sử dụng helper function để định dạng thời gian
        return TimeHelper::formatTime($this->open_time);
    }

    /**
     * Get the formatted close time
     *
     * @return string
     */
    public function getFormattedCloseTimeAttribute()
    {
        if ($this->is_closed || !$this->close_time) {
            return 'Đóng cửa';
        }

        // Sử dụng helper function để định dạng thời gian
        return TimeHelper::formatTime($this->close_time);
    }

    /**
     * Get the working hours display
     *
     * @return string
     */
    public function getWorkingHoursDisplayAttribute()
    {
        if ($this->is_closed) {
            return 'Đóng cửa';
        }

        // Sử dụng helper function để định dạng khoảng thời gian
        return TimeHelper::formatTimeRange($this->open_time, $this->close_time);
    }

    /**
     * Check if the salon is open on a specific day and time
     *
     * @param \Carbon\Carbon $dateTime
     * @return bool
     */
    public static function isOpen($dateTime)
    {
        $dayOfWeek = $dateTime->dayOfWeek;
        $time = $dateTime->format('H:i:s');

        $workingHour = self::where('day_of_week', $dayOfWeek)->first();

        if (!$workingHour || $workingHour->is_closed) {
            return false;
        }

        return $time >= $workingHour->open_time && $time <= $workingHour->close_time;
    }

    /**
     * Get all working hours ordered by day of week
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllOrdered()
    {
        return self::orderBy('day_of_week')->get();
    }
}
