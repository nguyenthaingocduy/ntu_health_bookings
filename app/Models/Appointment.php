<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'service_id',
        'appointment_date',
        'status',
        'notes',
        'doctor_id',
        'time_slot_id',
        'check_in_time',
        'check_out_time',
        'is_completed',
        'cancellation_reason'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function healthRecord()
    {
        return $this->hasOne(HealthRecord::class);
    }

    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
            'no-show' => 'Không đến'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}

