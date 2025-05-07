<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id',
        'reminder_date',
        'message',
        'reminder_type',
        'status',
        'created_by',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'reminder_date' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the appointment that owns the reminder.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the user that created the reminder.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
