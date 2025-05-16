<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_slot_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'user_id' => 'string',
        'time_slot_id' => 'string',
    ];

    /**
     * Lấy người dùng liên quan đến lịch làm việc
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy khung giờ liên quan đến lịch làm việc
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
