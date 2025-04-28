<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Promotion extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_purchase',
        'maximum_discount',
        'start_date',
        'end_date',
        'is_active',
        'usage_limit',
        'usage_count',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Mối quan hệ nhiều-nhiều với bảng services
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_promotion')
                    ->withTimestamps();
    }

    public function getFormattedDiscountValueAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return "GIẢM {$this->discount_value}%";
        }

        return "GIẢM " . number_format($this->discount_value, 0, ',', '.') . 'đ';
    }

    public function getFormattedMinimumPurchaseAttribute()
    {
        return number_format($this->minimum_purchase, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedMaximumDiscountAttribute()
    {
        if (!$this->maximum_discount) {
            return 'Không giới hạn';
        }

        return number_format($this->maximum_discount, 0, ',', '.') . ' VNĐ';
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Không kích hoạt</span>';
        }

        $now = now()->startOfDay();
        $startDate = $this->start_date->startOfDay();
        $endDate = $this->end_date->endOfDay();

        if ($now->lt($startDate)) {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sắp diễn ra</span>';
        }

        if ($now->gt($endDate)) {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Đã kết thúc</span>';
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Đã hết lượt</span>';
        }

        return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Đang diễn ra</span>';
    }

    public function getIsValidAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->is_valid) {
            return 0;
        }

        if ($amount < $this->minimum_purchase) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
        } else {
            $discount = $this->discount_value;
        }

        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return $discount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now())
                     ->where(function ($query) {
                         $query->whereNull('usage_limit')
                               ->orWhereRaw('usage_count < usage_limit');
                     });
    }


}
