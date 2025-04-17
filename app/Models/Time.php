<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'started_time',
        'capacity',
        'booked_count',
    ];

    /**
     * Check if the time slot is full
     *
     * @return bool
     */
    public function isFull()
    {
        return $this->booked_count >= $this->capacity;
    }

    /**
     * Get the number of available slots
     *
     * @return int
     */
    public function getAvailableSlotsAttribute()
    {
        return max(0, $this->capacity - $this->booked_count);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'time_appointments_id');
    }
}
