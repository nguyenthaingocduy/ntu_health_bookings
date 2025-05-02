<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TimeSlot extends Model
{
    use HasUuids;

    protected $fillable = [
        'start_time',
        'end_time',
        'day_of_week',
        'is_available',
        'max_appointments',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
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
}
