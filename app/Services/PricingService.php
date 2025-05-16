<?php

namespace App\Services;

use App\Models\CustomerType;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PricingService
{
    /**
     * Tính giá dịch vụ sau khi áp dụng tất cả các loại giảm giá
     * 
     * @param Service $service Dịch vụ cần tính giá
     * @param string|null $promotionCode Mã khuyến mãi (nếu có)
     * @param User|null $user Người dùng (nếu không cung cấp, sẽ lấy người dùng hiện tại)
     * @return array Mảng chứa thông tin về giá và giảm giá
     */
    public function calculateFinalPrice(Service $service, ?string $promotionCode = null, ?User $user = null)
    {
        // Nếu không cung cấp user, lấy user hiện tại
        if (!$user && Auth::check()) {
            $user = Auth::user();
        }
        
        // Giá gốc
        $originalPrice = $service->price;
        $finalPrice = $originalPrice;
        
        // Khởi tạo các biến theo dõi giảm giá
        $discounts = [
            'service_promotion' => [
                'applied' => false,
                'amount' => 0,
                'percentage' => 0,
                'name' => 'Khuyến mãi dịch vụ'
            ],
            'promotion_code' => [
                'applied' => false,
                'amount' => 0,
                'percentage' => 0,
                'name' => 'Mã khuyến mãi'
            ],
            'customer_type' => [
                'applied' => false,
                'amount' => 0,
                'percentage' => 0,
                'name' => 'Loại khách hàng'
            ]
        ];
        
        // 1. Áp dụng khuyến mãi dịch vụ (nếu có)
        if ($service->hasActivePromotion()) {
            $servicePromotion = $service->promotions()
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
                
            if ($servicePromotion) {
                $discounts['service_promotion']['applied'] = true;
                
                if ($servicePromotion->discount_type == 'percentage') {
                    $discounts['service_promotion']['percentage'] = $servicePromotion->discount_value;
                    $discounts['service_promotion']['amount'] = $originalPrice * ($servicePromotion->discount_value / 100);
                } else {
                    $discounts['service_promotion']['amount'] = $servicePromotion->discount_value;
                    $discounts['service_promotion']['percentage'] = round(($servicePromotion->discount_value / $originalPrice) * 100, 2);
                }
                
                $finalPrice -= $discounts['service_promotion']['amount'];
            }
        }
        
        // 2. Áp dụng mã khuyến mãi (nếu có)
        if ($promotionCode) {
            $promotion = Promotion::where('code', strtoupper($promotionCode))
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
                
            if ($promotion) {
                $discounts['promotion_code']['applied'] = true;
                
                if ($promotion->discount_type == 'percentage') {
                    $discounts['promotion_code']['percentage'] = $promotion->discount_value;
                    $discounts['promotion_code']['amount'] = $originalPrice * ($promotion->discount_value / 100);
                } else {
                    $discounts['promotion_code']['amount'] = $promotion->discount_value;
                    $discounts['promotion_code']['percentage'] = round(($promotion->discount_value / $originalPrice) * 100, 2);
                }
                
                $finalPrice -= $discounts['promotion_code']['amount'];
            }
        }
        
        // 3. Áp dụng giảm giá theo loại khách hàng (nếu có)
        if ($user && $user->type_id) {
            $customerType = CustomerType::find($user->type_id);
            
            if ($customerType && $customerType->is_active && $customerType->discount_percentage > 0) {
                $discounts['customer_type']['applied'] = true;
                $discounts['customer_type']['percentage'] = $customerType->discount_percentage;
                $discounts['customer_type']['amount'] = $originalPrice * ($customerType->discount_percentage / 100);
                $discounts['customer_type']['name'] = "Khách hàng {$customerType->type_name}";
                
                $finalPrice -= $discounts['customer_type']['amount'];
            }
        }
        
        // Đảm bảo giá không âm
        $finalPrice = max(0, $finalPrice);
        
        // Tính tổng giảm giá
        $totalDiscountAmount = $originalPrice - $finalPrice;
        $totalDiscountPercentage = $originalPrice > 0 ? round(($totalDiscountAmount / $originalPrice) * 100, 2) : 0;
        
        return [
            'original_price' => $originalPrice,
            'final_price' => $finalPrice,
            'total_discount_amount' => $totalDiscountAmount,
            'total_discount_percentage' => $totalDiscountPercentage,
            'discounts' => $discounts
        ];
    }
}
