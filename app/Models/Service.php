<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic;
use App\Models\Category;
use App\Models\Appointment;

class Service extends Model
{
    // The service table uses auto-incrementing IDs, not UUIDs
    // use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration',
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

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
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

    /**
     * Kiểm tra xem dịch vụ có khuyến mãi đang hoạt động không
     *
     * @return bool
     */
    public function hasActivePromotion()
    {
        if (empty($this->promotion)) {
            return false;
        }

        // Nếu promotion là một số, đó là phần trăm giảm giá
        if (is_numeric($this->promotion)) {
            return true;
        }

        // Nếu promotion là mã khuyến mãi, kiểm tra xem mã đó có hợp lệ không
        $promotion = \App\Models\Promotion::where('code', $this->promotion)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        return $promotion !== null;
    }

    /**
     * Lấy giá trị khuyến mãi (phần trăm hoặc số tiền cố định)
     *
     * @return string
     */
    public function getPromotionValueAttribute()
    {
        if (!$this->hasActivePromotion()) {
            return null;
        }

        // Nếu promotion là một số, đó là phần trăm giảm giá
        if (is_numeric($this->promotion)) {
            return $this->promotion . '%';
        }

        // Nếu promotion là mã khuyến mãi, lấy thông tin từ bảng promotions
        $promotion = \App\Models\Promotion::where('code', $this->promotion)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($promotion) {
            return $promotion->formatted_discount_value;
        }

        return null;
    }

    /**
     * Lấy giá sau khi áp dụng khuyến mãi
     *
     * @return float
     */
    public function getDiscountedPriceAttribute()
    {
        if (!$this->hasActivePromotion()) {
            return $this->price;
        }

        // Nếu promotion là một số, đó là phần trăm giảm giá
        if (is_numeric($this->promotion)) {
            $discount = $this->price * ($this->promotion / 100);
            return $this->price - $discount;
        }

        // Nếu promotion là mã khuyến mãi, tính toán giảm giá dựa trên loại khuyến mãi
        $promotion = \App\Models\Promotion::where('code', $this->promotion)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($promotion) {
            return $this->price - $promotion->calculateDiscount($this->price);
        }

        return $this->price;
    }

    /**
     * Lấy giá đã giảm được định dạng
     *
     * @return string
     */
    public function getFormattedDiscountedPriceAttribute()
    {
        $discountedPrice = $this->discounted_price;

        if ($discountedPrice == 0) {
            return 'Miễn phí';
        }

        return number_format($discountedPrice, 0, ',', '.') . ' VNĐ';
    }
}

