<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clinic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description',
        'image_url',
    ];
    
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
