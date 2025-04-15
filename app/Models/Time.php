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
    ];
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'time_appointments_id');
    }
}
