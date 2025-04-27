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
        // Đảm bảo promotion không phải là null, chuỗi rỗng hoặc 0
        if (empty($this->promotion) && $this->promotion !== '0' && $this->promotion !== 0) {
            return false;
        }

        // Nếu promotion là một số, đó là phần trăm giảm giá
        if (is_numeric($this->promotion) && $this->promotion > 0) {
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
     * @param string|null $promotionCode Mã khuyến mãi bổ sung (nếu có)
     * @return string
     */
    public function getPromotionValueAttribute($promotionCode = null)
    {
        if (!$this->hasActivePromotion() && empty($promotionCode)) {
            return null;
        }

        $discounts = [];

        // Lấy giảm giá trực tiếp của dịch vụ (nếu có)
        if (is_numeric($this->promotion) && $this->promotion > 0) {
            $discounts[] = $this->promotion . '%';
        }

        // Lấy giảm giá từ mã khuyến mãi của dịch vụ (nếu có)
        if (!is_numeric($this->promotion) && !empty($this->promotion)) {
            $servicePromotion = \App\Models\Promotion::where('code', $this->promotion)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($servicePromotion) {
                $discounts[] = $servicePromotion->formatted_discount_value;
            }
        }

        // Lấy giảm giá từ mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode) && $promotionCode !== $this->promotion) {
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

        // Áp dụng giảm giá trực tiếp của dịch vụ (nếu có)
        if (is_numeric($this->promotion) && $this->promotion > 0) {
            $directDiscount = $price * ($this->promotion / 100);
            $totalDiscount += $directDiscount;
        }

        // Áp dụng mã khuyến mãi của dịch vụ (nếu có)
        if (!is_numeric($this->promotion) && !empty($this->promotion)) {
            $servicePromotion = \App\Models\Promotion::where('code', $this->promotion)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($servicePromotion) {
                $promotionDiscount = $servicePromotion->calculateDiscount($price);
                $totalDiscount += $promotionDiscount;
            }
        }

        // Áp dụng mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode) && $promotionCode !== $this->promotion) {
            $additionalPromotion = \App\Models\Promotion::where('code', $promotionCode)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($additionalPromotion) {
                // Tính giảm giá trên giá đã giảm
                $discountedPrice = $price - $totalDiscount;
                $additionalDiscount = $additionalPromotion->calculateDiscount($discountedPrice);
                $totalDiscount += $additionalDiscount;
            }
        }

        // Đảm bảo tổng giảm giá không vượt quá giá gốc
        if ($totalDiscount > $price) {
            $totalDiscount = $price;
        }

        return $price - $totalDiscount;
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
        // Sử dụng phương thức getDiscountedPriceAttribute với mã khuyến mãi bổ sung
        return $this->getDiscountedPriceAttribute($promotionCode);
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
        if (!$this->hasActivePromotion() && empty($promotionCode)) {
            return null;
        }

        $details = [];

        // Lấy thông tin giảm giá trực tiếp của dịch vụ (nếu có)
        if (is_numeric($this->promotion) && $this->promotion > 0) {
            $details['direct'] = [
                'discount_value' => $this->promotion . '%',
                'start_date' => null,
                'end_date' => null,
                'is_direct' => true,
                'title' => 'Giảm giá trực tiếp'
            ];
        }

        // Lấy thông tin từ mã khuyến mãi của dịch vụ (nếu có)
        if (!is_numeric($this->promotion) && !empty($this->promotion)) {
            $servicePromotion = \App\Models\Promotion::where('code', $this->promotion)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($servicePromotion) {
                $details['service_promotion'] = [
                    'discount_value' => $servicePromotion->formatted_discount_value,
                    'start_date' => $servicePromotion->start_date->format('d/m/Y'),
                    'end_date' => $servicePromotion->end_date->format('d/m/Y'),
                    'is_direct' => false,
                    'title' => $servicePromotion->title
                ];
            }
        }

        // Lấy thông tin từ mã khuyến mãi bổ sung (nếu có)
        if (!empty($promotionCode) && $promotionCode !== $this->promotion) {
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

