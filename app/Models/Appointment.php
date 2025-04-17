<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Time;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'service_id',
        'date_register',
        'date_appointments',
        'time_appointments_id',
        'employee_id',
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
        'date_register' => 'datetime',
        'date_appointments' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
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
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    public function timeAppointment()
    {
        return $this->belongsTo(Time::class, 'time_appointments_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
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

    public function getCodeAttribute()
    {
        return 'APT-' . substr($this->id, 0, 8);
    }
}

