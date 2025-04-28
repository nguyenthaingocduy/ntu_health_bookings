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

    /**
     * Mối quan hệ nhiều-nhiều với bảng promotions
     */
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'service_promotion')
                    ->withTimestamps();
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
        // Kiểm tra xem có khuyến mãi đang hoạt động không
        $activePromotions = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        return $activePromotions > 0;
    }

    /**
     * Lấy giá trị khuyến mãi (phần trăm hoặc số tiền cố định)
     *
     * @param string|null $promotionCode Mã khuyến mãi bổ sung (nếu có)
     * @return string
     */
    public function getPromotionValueAttribute($promotionCode = null)
    {
        $discounts = [];

        // Lấy tất cả khuyến mãi đang hoạt động của dịch vụ
        $activePromotions = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        foreach ($activePromotions as $promotion) {
            $discounts[] = $promotion->formatted_discount_value;
        }

        // Lấy giảm giá từ mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode)) {
            // Chuyển mã khuyến mãi sang chữ hoa để đảm bảo tìm kiếm chính xác
            $promotionCode = strtoupper($promotionCode);

            $additionalPromotion = \App\Models\Promotion::where('code', $promotionCode)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($additionalPromotion) {
                $discounts[] = $additionalPromotion->formatted_discount_value;
            }
        }

        if (empty($discounts)) {
            return null;
        }

        // Nếu chỉ có một loại giảm giá, trả về giá trị đó
        if (count($discounts) === 1) {
            return $discounts[0];
        }

        // Nếu có nhiều loại giảm giá, trả về tổng hợp
        return implode(' + ', $discounts);
    }

    /**
     * Lấy tên dịch vụ kèm khuyến mãi nếu có
     *
     * @return string
     */
    public function getNameWithPromotionAttribute()
    {
        if ($this->hasActivePromotion()) {
            return $this->name . ' ' . $this->promotion_value;
        }

        return $this->name;
    }

    /**
     * Lấy giá sau khi áp dụng khuyến mãi
     *
     * @param string|null $promotionCode Mã khuyến mãi bổ sung (nếu có)
     * @return float
     */
    public function getDiscountedPriceAttribute($promotionCode = null)
    {
        $price = $this->price;
        $totalDiscount = 0;
        $discountedPrice = $price;
        $debug = [];

        // Lấy tất cả khuyến mãi đang hoạt động của dịch vụ
        $activePromotions = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        $debug['active_promotions_count'] = $activePromotions->count();
        $debug['active_promotions'] = $activePromotions->pluck('title')->toArray();

        // Áp dụng từng khuyến mãi
        foreach ($activePromotions as $promotion) {
            $promotionDiscount = $promotion->calculateDiscount($discountedPrice);
            $totalDiscount += $promotionDiscount;
            $discountedPrice -= $promotionDiscount;

            $debug['promotion_' . $promotion->id] = [
                'title' => $promotion->title,
                'discount_type' => $promotion->discount_type,
                'discount_value' => $promotion->discount_value,
                'calculated_discount' => $promotionDiscount
            ];
        }

        // Áp dụng mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode)) {
            // Chuyển mã khuyến mãi sang chữ hoa để đảm bảo tìm kiếm chính xác
            $promotionCode = strtoupper($promotionCode);
            $debug['promotion_code'] = $promotionCode;

            $additionalPromotion = \App\Models\Promotion::where('code', $promotionCode)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($additionalPromotion) {
                // Tính giảm giá trên giá đã giảm
                $additionalDiscount = $additionalPromotion->calculateDiscount($discountedPrice);
                $totalDiscount += $additionalDiscount;

                $debug['additional_promotion'] = [
                    'id' => $additionalPromotion->id,
                    'title' => $additionalPromotion->title,
                    'discount_type' => $additionalPromotion->discount_type,
                    'discount_value' => $additionalPromotion->discount_value,
                    'calculated_discount' => $additionalDiscount
                ];
            } else {
                $debug['additional_promotion_found'] = false;
            }
        }

        // Đảm bảo tổng giảm giá không vượt quá giá gốc
        if ($totalDiscount > $price) {
            $totalDiscount = $price;
        }

        $finalPrice = $price - $totalDiscount;

        $debug['original_price'] = $price;
        $debug['total_discount'] = $totalDiscount;
        $debug['final_price'] = $finalPrice;

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Chi tiết tính giá khuyến mãi', $debug);

        return $finalPrice;
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

    /**
     * Tính giá sau khi áp dụng mã khuyến mãi bổ sung
     *
     * @param string $promotionCode Mã khuyến mãi bổ sung
     * @return float
     */
    public function calculatePriceWithPromotion($promotionCode)
    {
        // Log để debug
        \Illuminate\Support\Facades\Log::info('Tính giá với mã khuyến mãi', [
            'service_id' => $this->id,
            'service_name' => $this->name,
            'original_price' => $this->price,
            'promotion_code' => $promotionCode
        ]);

        // Sử dụng phương thức getDiscountedPriceAttribute với mã khuyến mãi bổ sung
        $discountedPrice = $this->getDiscountedPriceAttribute($promotionCode);

        // Log kết quả
        \Illuminate\Support\Facades\Log::info('Kết quả tính giá', [
            'service_id' => $this->id,
            'discounted_price' => $discountedPrice
        ]);

        return $discountedPrice;
    }

    /**
     * Lấy giá đã giảm được định dạng sau khi áp dụng mã khuyến mãi bổ sung
     *
     * @param string $promotionCode Mã khuyến mãi bổ sung
     * @return string
     */
    public function getFormattedPriceWithPromotion($promotionCode)
    {
        $discountedPrice = $this->calculatePriceWithPromotion($promotionCode);

        if ($discountedPrice == 0) {
            return 'Miễn phí';
        }

        return number_format($discountedPrice, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Lấy thông tin chi tiết về khuyến mãi
     *
     * @param string|null $promotionCode Mã khuyến mãi bổ sung (nếu có)
     * @return array|null
     */
    public function getPromotionDetailsAttribute($promotionCode = null)
    {
        $details = [];

        // Lấy tất cả khuyến mãi đang hoạt động của dịch vụ
        $activePromotions = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Thêm thông tin chi tiết cho từng khuyến mãi
        foreach ($activePromotions as $index => $promotion) {
            $details['service_promotion_' . $index] = [
                'discount_value' => $promotion->formatted_discount_value,
                'start_date' => $promotion->start_date->format('d/m/Y'),
                'end_date' => $promotion->end_date->format('d/m/Y'),
                'is_direct' => false,
                'title' => $promotion->title
            ];
        }

        // Lấy thông tin từ mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode)) {
            // Chuyển mã khuyến mãi sang chữ hoa để đảm bảo tìm kiếm chính xác
            $promotionCode = strtoupper($promotionCode);

            $additionalPromotion = \App\Models\Promotion::where('code', $promotionCode)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($additionalPromotion) {
                $details['additional_promotion'] = [
                    'discount_value' => $additionalPromotion->formatted_discount_value,
                    'start_date' => $additionalPromotion->start_date->format('d/m/Y'),
                    'end_date' => $additionalPromotion->end_date->format('d/m/Y'),
                    'is_direct' => false,
                    'title' => $additionalPromotion->title
                ];
            }
        }

        if (empty($details)) {
            return null;
        }

        // Nếu chỉ có một loại giảm giá, trả về thông tin chi tiết của loại đó
        if (count($details) === 1) {
            return reset($details);
        }

        // Nếu có nhiều loại giảm giá, trả về tất cả thông tin
        return $details;
    }
}

