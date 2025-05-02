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
}
