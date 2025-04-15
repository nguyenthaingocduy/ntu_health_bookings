<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'role_id',
        'type_id',
        'province_id',
        'district_id',
        'ward_id',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'type_id');
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function hasRole($roleName)
    {
        if (!$this->role) {
            return false;
        }
        return strtolower($this->role->name) === strtolower($roleName);
    }
}
