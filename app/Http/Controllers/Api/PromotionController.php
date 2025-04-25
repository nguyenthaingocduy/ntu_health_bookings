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
        $promotions = Promotion::active()->get();
        
        return response()->json([
            'success' => true,
            'promotions' => $promotions,
            'count' => $promotions->count()
        ]);
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
