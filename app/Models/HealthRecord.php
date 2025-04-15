<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class HealthRecord extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'check_date',
        'height',
        'weight',
        'blood_pressure',
        'heart_rate',
        'blood_type',
        'allergies',
        'medical_history',
        'diagnosis',
        'recommendations',
        'next_check_date',
        'doctor_notes',
    ];

    protected $casts = [
        'check_date' => 'datetime',
        'next_check_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
