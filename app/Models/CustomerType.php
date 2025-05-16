<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerType extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'type_name',
        'discount_percentage',
        'priority_level',
        'min_spending',
        'description',
        'color_code',
        'is_active',
        'has_priority_booking',
        'has_personal_consultant',
        'has_birthday_gift',
        'has_free_service',
        'free_service_count',
        'has_extended_warranty',
        'extended_warranty_days',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'type_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'type_id');
    }

    /**
     * Get formatted discount percentage with % symbol
     */
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount_percentage, 2) . '%';
    }

    /**
     * Get formatted minimum spending with currency symbol
     */
    public function getFormattedMinSpendingAttribute()
    {
        return number_format($this->min_spending, 0, ',', '.') . ' VNĐ';
    }
}
