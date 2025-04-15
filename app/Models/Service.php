<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image_url',
        'is_active',
        'is_health_checkup',
        'category_id',
        'required_tests',
        'preparation_instructions'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
        return $this->duration . ' phút';
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'Miễn phí';
        }
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }
}

