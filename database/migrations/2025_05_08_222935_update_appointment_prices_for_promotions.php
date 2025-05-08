<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lấy tất cả các lịch hẹn
        $appointments = \App\Models\Appointment::with(['service'])
            ->whereNotNull('service_id')
            ->get();

        foreach ($appointments as $appointment) {
            // Bỏ qua nếu không có dịch vụ
            if (!$appointment->service) {
                continue;
            }

            // Tính lại giá sau khuyến mãi
            $originalPrice = $appointment->service->price;
            $finalPrice = $originalPrice;
            $discountAmount = 0;
            $directDiscountPercent = 0;

            // Nếu có mã khuyến mãi, tính giá với mã khuyến mãi
            if ($appointment->promotion_code) {
                $finalPrice = $appointment->service->calculatePriceWithPromotion($appointment->promotion_code);

                // Lấy thông tin khuyến mãi
                $promotion = \App\Models\Promotion::where('code', $appointment->promotion_code)
                    ->first();

                if ($promotion) {
                    // Tính phần trăm giảm giá
                    $directDiscountPercent = $promotion->discount_type == 'percentage'
                        ? $promotion->discount_value
                        : round(($promotion->discount_value / $originalPrice) * 100);
                }

                // Tính số tiền giảm
                $discountAmount = $originalPrice - $finalPrice;
            } else {
                // Nếu không có mã khuyến mãi, kiểm tra xem dịch vụ có khuyến mãi không
                if ($appointment->service->hasActivePromotion()) {
                    $finalPrice = $appointment->service->getDiscountedPriceAttribute();

                    // Lấy khuyến mãi đầu tiên của dịch vụ
                    $servicePromotion = $appointment->service->promotions()
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if ($servicePromotion && $servicePromotion->discount_type == 'percentage') {
                        $directDiscountPercent = $servicePromotion->discount_value;
                    } else {
                        // Tính phần trăm giảm giá từ dịch vụ
                        $directDiscountPercent = round(($originalPrice - $finalPrice) / $originalPrice * 100);
                    }

                    // Tính số tiền giảm
                    $discountAmount = $originalPrice - $finalPrice;
                }
            }

            // Cập nhật giá sau khuyến mãi vào cơ sở dữ liệu
            if ($finalPrice != $appointment->final_price ||
                $discountAmount != $appointment->discount_amount ||
                $directDiscountPercent != $appointment->direct_discount_percent) {
                try {
                    $appointment->final_price = $finalPrice;
                    $appointment->discount_amount = $discountAmount;
                    $appointment->direct_discount_percent = $directDiscountPercent;
                    $appointment->save();

                    // Log để debug
                    \Illuminate\Support\Facades\Log::info('Đã cập nhật giá sau khuyến mãi trong migration', [
                        'appointment_id' => $appointment->id,
                        'original_price' => $originalPrice,
                        'final_price' => $finalPrice,
                        'promotion_code' => $appointment->promotion_code,
                        'direct_discount_percent' => $directDiscountPercent,
                        'discount_amount' => $discountAmount,
                        'calculated_discount_percent' => $originalPrice > 0 ? round(($originalPrice - $finalPrice) / $originalPrice * 100, 2) : 0
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Không thể cập nhật giá sau khuyến mãi: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
