<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Lấy danh sách khuyến mãi đang hoạt động
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivePromotions()
    {
        try {
            // Get all active promotions with associated services using the scope
            $promotions = Promotion::active()
                ->with('services')
                ->orderBy('created_at', 'desc') // Get newest promotions first
                ->get();

            // Log for debugging
            \Illuminate\Support\Facades\Log::info('Active promotions found: ' . $promotions->count());

            $formattedPromotions = $promotions->map(function($promotion) {
                try {
                    // Get all services for this promotion
                    $services = $promotion->services;

                    // Format discount value for display
                    $formattedDiscountValue = '';
                    if ($promotion->discount_type === 'percentage') {
                        $formattedDiscountValue = $promotion->discount_value . '%';
                    } else if ($promotion->discount_type === 'fixed') {
                        $formattedDiscountValue = number_format($promotion->discount_value, 0, ',', '.') . 'đ';
                    }

                    // Format the main promotion data
                    $result = [
                        'id' => $promotion->id,
                        'title' => $promotion->title ?? 'Khuyến mãi', // Ensure title is included with fallback
                        'name' => $promotion->title ?? 'Khuyến mãi', // Keep name for backward compatibility
                        'description' => $promotion->description ?? 'Ưu đãi đặc biệt',
                        'discount_type' => $promotion->discount_type,
                        'discount_value' => $promotion->discount_value,
                        'formatted_discount_value' => $formattedDiscountValue,
                        'start_date' => $promotion->start_date ? $promotion->start_date->format('d/m/Y') : null,
                        'end_date' => $promotion->end_date ? $promotion->end_date->format('d/m/Y') : null,
                        'code' => $promotion->code,
                        'usage_limit' => $promotion->usage_limit,
                        'usage_count' => $promotion->usage_count,
                        'minimum_purchase' => $promotion->minimum_purchase ?? 0,
                    ];

                    // If promotion has services, include the first service for display
                    if ($services && $services->isNotEmpty()) {
                        $service = $services->first();

                        if ($service) {
                            $result['service'] = [
                                'id' => $service->id,
                                'name' => $service->name,
                                'price' => $service->price,
                                'image_url' => $service->image_url,
                                'description' => $service->description,
                                'category_id' => $service->category_id,
                                'duration' => $service->duration
                            ];

                            // Calculate discounted price
                            if ($promotion->discount_type === 'percentage') {
                                $discountedPrice = $service->price - ($service->price * $promotion->discount_value / 100);
                            } else {
                                $discountedPrice = $service->price - $promotion->discount_value;
                            }

                            $result['service']['discounted_price'] = max(0, $discountedPrice);
                        }
                    }

                    return $result;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error formatting promotion: ' . $e->getMessage(), [
                        'promotion_id' => $promotion->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return null;
                }
            })->filter(); // Remove any null values from failed formatting

            return response()->json([
                'success' => true,
                'promotions' => $formattedPromotions
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in active-promotions API: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin khuyến mãi. Vui lòng làm mới trang để thử lại.',
                'promotions' => []
            ]);
        }
    }

    /**
     * Kiểm tra mã khuyến mãi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
        ]);

        $code = strtoupper($request->code);
        $amount = $request->amount;

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại.',
            ]);
        }

        if (!$promotion->is_valid) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.',
            ]);
        }

        if ($amount < $promotion->minimum_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Giá trị đơn hàng không đủ để áp dụng mã khuyến mãi này. Tối thiểu: ' . number_format($promotion->minimum_purchase, 0, ',', '.') . ' VNĐ',
            ]);
        }

        $discount = $promotion->calculateDiscount($amount);

        return response()->json([
            'success' => true,
            'message' => 'Mã khuyến mãi hợp lệ.',
            'data' => [
                'promotion' => $promotion,
                'discount' => $discount,
                'formatted_discount' => number_format($discount, 0, ',', '.') . ' VNĐ',
            ],
        ]);
    }

    /**
     * Kiểm tra mã khuyến mãi cho dịch vụ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPromotion(Request $request)
    {
        try {
            // Validate request
            $code = strtoupper($request->query('code', ''));
            $serviceId = $request->query('service_id', '');

            if (empty($code) || empty($serviceId)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Thiếu mã khuyến mãi hoặc ID dịch vụ'
                ]);
            }

            // Log để debug
            \Illuminate\Support\Facades\Log::info('Kiểm tra mã khuyến mãi', [
                'code' => $code,
                'service_id' => $serviceId
            ]);

            // Tìm mã khuyến mãi
            $promotion = Promotion::where('code', $code)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$promotion) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn'
                ]);
            }

            // Kiểm tra giới hạn sử dụng
            if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Mã khuyến mãi đã hết lượt sử dụng'
                ]);
            }

            // Lấy thông tin dịch vụ
            $service = \App\Models\Service::find($serviceId);
            if (!$service) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Không tìm thấy dịch vụ'
                ]);
            }

            // Kiểm tra giá tối thiểu
            if ($service->price < $promotion->minimum_purchase) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Giá dịch vụ không đủ để áp dụng mã khuyến mãi này. Tối thiểu: ' . number_format($promotion->minimum_purchase, 0, ',', '.') . 'đ'
                ]);
            }

            // Tính giá sau khuyến mãi
            $discountAmount = 0;
            $finalPrice = $service->price;

            if ($promotion->discount_type === 'percentage') {
                $discountAmount = $service->price * ($promotion->discount_value / 100);
                if ($promotion->maximum_discount && $discountAmount > $promotion->maximum_discount) {
                    $discountAmount = $promotion->maximum_discount;
                }
            } else {
                $discountAmount = $promotion->discount_value;
            }

            $finalPrice = max(0, $service->price - $discountAmount);

            // Định dạng giá
            $formattedFinalPrice = number_format($finalPrice, 0, ',', '.') . 'đ';
            $formattedDiscountAmount = number_format($discountAmount, 0, ',', '.') . 'đ';
            $discountPercentage = round(($discountAmount / $service->price) * 100, 1);

            return response()->json([
                'valid' => true,
                'message' => "Giảm {$discountPercentage}% ({$formattedDiscountAmount})",
                'promotion' => [
                    'id' => $promotion->id,
                    'code' => $promotion->code,
                    'title' => $promotion->title,
                    'discount_type' => $promotion->discount_type,
                    'discount_value' => $promotion->discount_value,
                    'formatted_discount' => $promotion->formatted_discount_value
                ],
                'original_price' => $service->price,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'discounted_price' => $formattedFinalPrice,
                'discount_percentage' => $discountPercentage
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi khi kiểm tra mã khuyến mãi: ' . $e->getMessage(), [
                'code' => $request->query('code', ''),
                'service_id' => $request->query('service_id', ''),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'Đã xảy ra lỗi khi kiểm tra mã khuyến mãi'
            ]);
        }
    }
}
