<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
        // Check if this time slot is active
        if (!$this->is_active) {
            return false;
        }

        // Check if we've reached the maximum number of appointments for this slot
        // Since max_appointments doesn't exist, we'll use a default value of 10
        $appointmentsCount = $this->appointments()
            ->whereDate('date_appointments', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return $appointmentsCount < 10; // Default max appointments
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

        // Trả về true nếu số lượng cuộc hẹn nhỏ hơn số lượng tối đa (mặc định là 10)
        return $appointmentsCount < 10;
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

        // Trả về số lượng chỗ trống còn lại (mặc định tối đa là 10)
        return max(0, 10 - $appointmentsCount);
    }


}
