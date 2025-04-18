<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'birthday',
        'address',
        'phone',
        'gender',
        'role_id',
        'clinic_id',
        'status',
        'email',
        'avatar_url',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service');
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
