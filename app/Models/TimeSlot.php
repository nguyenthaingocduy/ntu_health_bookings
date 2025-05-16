<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TimeSlot extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'start_time',
        'end_time',
        'day_of_week',
        'is_available',
        'max_appointments',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFormattedTimeAttribute()
    {
        // Sử dụng helper function để định dạng khoảng thời gian
        return TimeHelper::formatTimeRange($this->start_time, $this->end_time);
    }

    public function isAvailableForDate($date)
    {
        // Check if this time slot is available for the given date
        if (!$this->is_available) {
            return false;
        }

        // Check if the day of week matches
        if ($this->day_of_week !== null && $this->day_of_week != $date->dayOfWeek) {
            return false;
        }

        // Check if we've reached the maximum number of appointments for this slot
        $appointmentsCount = $this->appointments()
            ->whereDate('date_appointments', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return $appointmentsCount < $this->max_appointments;
    }

    /**
     * Kiểm tra xem khung giờ có còn chỗ trống không
     *
     * @param string $date Ngày cần kiểm tra (Y-m-d)
     * @return bool
     */
    public function hasAvailableSlots($date)
    {
        // Đếm số lượng cuộc hẹn hiện tại trong khung giờ này
        $appointmentsCount = $this->appointments()
            ->whereDate('date_appointments', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Trả về true nếu số lượng cuộc hẹn nhỏ hơn số lượng tối đa
        return $appointmentsCount < $this->max_appointments;
    }

    /**
     * Lấy số lượng chỗ trống còn lại
     *
     * @param string $date Ngày cần kiểm tra (Y-m-d)
     * @return int
     */
    public function getAvailableSlotsCount($date)
    {
        // Đếm số lượng cuộc hẹn hiện tại trong khung giờ này
        $appointmentsCount = $this->appointments()
            ->whereDate('date_appointments', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Trả về số lượng chỗ trống còn lại
        return max(0, $this->max_appointments - $appointmentsCount);
    }


}
