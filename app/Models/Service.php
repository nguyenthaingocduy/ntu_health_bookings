<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    // The service table uses auto-incrementing IDs, not UUIDs
    // use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'promotion',
        'category_id',
        'clinic_id',
        'image_url',
        'status',
        'is_health_checkup',
        'required_tests',
        'preparation_instructions'
    ];

    protected $casts = [
        'is_health_checkup' => 'boolean',
        'required_tests' => 'array'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedDurationAttribute()
    {
        // Default duration for health check-ups if not specified
        return '60 phút';
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'Miễn phí';
        }
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }
}

