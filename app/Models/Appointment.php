<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Time;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'service_id',
        'date_register',
        'date_appointments',
        'time_appointments_id',
        'employee_id',
        'status',
        'notes',
        'doctor_id',
        'time_slot_id',
        'check_in_time',
        'check_out_time',
        'is_completed',
        'cancellation_reason',
        'promotion_code',
        'final_price',
        'discount_amount',
        'direct_discount_percent',
        'updated_by'
    ];

    protected $casts = [
        'date_register' => 'datetime',
        'date_appointments' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    public function timeAppointment()
    {
        return $this->belongsTo(Time::class, 'time_appointments_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function healthRecord()
    {
        return $this->hasOne(HealthRecord::class);
    }

    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
            'no-show' => 'Không đến'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    public function getCodeAttribute()
    {
        return 'APT-' . substr($this->id, 0, 8);
    }

    /**
     * Lấy giá dịch vụ sau khi áp dụng tất cả các khuyến mãi
     *
     * @return float
     */
    public function getFinalPriceAttribute()
    {
        // Luôn tính toán lại giá nếu có mã khuyến mãi hoặc giá chưa được tính
        // Thêm điều kiện kiểm tra xem có mã khuyến mãi từ query string không
        $promotionCodeFromQuery = request()->query('promotion_code');
        $recalculate = !empty($this->promotion_code) ||
                      !empty($promotionCodeFromQuery) ||
                      !isset($this->attributes['final_price']) ||
                      $this->attributes['final_price'] <= 0;

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Kiểm tra tính toán lại giá', [
            'appointment_id' => $this->id,
            'promotion_code' => $this->promotion_code,
            'promotion_code_from_query' => $promotionCodeFromQuery,
            'final_price_in_db' => $this->attributes['final_price'] ?? 0,
            'recalculate' => $recalculate
        ]);

        // Nếu có mã khuyến mãi từ query string, cập nhật vào appointment
        if (!empty($promotionCodeFromQuery) && $promotionCodeFromQuery != $this->promotion_code) {
            \Illuminate\Support\Facades\Log::info('Cập nhật mã khuyến mãi mới từ query string trong model', [
                'appointment_id' => $this->id,
                'old_promotion_code' => $this->promotion_code,
                'new_promotion_code' => $promotionCodeFromQuery
            ]);

            $this->promotion_code = $promotionCodeFromQuery;
            $this->save();
            $recalculate = true;
        }

        // Nếu đã có giá trong cơ sở dữ liệu và không cần tính lại, sử dụng giá đó
        if (!$recalculate && isset($this->attributes['final_price']) && $this->attributes['final_price'] > 0) {
            // Log để debug
            \Illuminate\Support\Facades\Log::info('Sử dụng giá từ cơ sở dữ liệu', [
                'appointment_id' => $this->id,
                'final_price' => $this->attributes['final_price']
            ]);

            return $this->attributes['final_price'];
        }

        if (!$this->service) {
            return 0;
        }

        // Đảm bảo mã khuyến mãi được xử lý đúng cách
        $promotionCode = !empty($this->promotion_code) ? strtoupper($this->promotion_code) : null;

        // Sử dụng PricingService để tính giá
        $pricingService = new \App\Services\PricingService();
        $priceDetails = $pricingService->calculateFinalPrice($this->service, $promotionCode);

        // Lấy các giá trị từ kết quả tính toán
        $originalPrice = $priceDetails['original_price'];
        $finalPrice = $priceDetails['final_price'];
        $discountAmount = $priceDetails['total_discount_amount'];
        $directDiscountPercent = $priceDetails['total_discount_percentage'];

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Tính giá sau khuyến mãi', [
            'appointment_id' => $this->id,
            'service_id' => $this->service_id,
            'service_price' => $this->service->price,
            'promotion_code' => $promotionCode,
            'original_price' => $originalPrice,
            'final_price' => $finalPrice,
            'discount_amount' => $discountAmount,
            'direct_discount_percent' => $directDiscountPercent,
            'from_database' => false
        ]);

        // Cập nhật giá vào cơ sở dữ liệu
        try {
            $this->update([
                'final_price' => $finalPrice,
                'discount_amount' => $discountAmount,
                'direct_discount_percent' => $directDiscountPercent
            ]);

            // Cập nhật lại giá trị trong attributes để đảm bảo giá trị mới được sử dụng
            $this->attributes['final_price'] = $finalPrice;
            $this->attributes['discount_amount'] = $discountAmount;
            $this->attributes['direct_discount_percent'] = $directDiscountPercent;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Không thể cập nhật giá sau khuyến mãi: ' . $e->getMessage());
        }

        return $finalPrice;
    }

    /**
     * Lấy giá dịch vụ sau khi áp dụng tất cả các khuyến mãi (đã định dạng)
     *
     * @return string
     */
    public function getFormattedFinalPriceAttribute()
    {
        $finalPrice = $this->final_price;

        if ($finalPrice == 0) {
            return 'Miễn phí';
        }

        return number_format($finalPrice, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Lấy thông tin khuyến mãi áp dụng cho đơn hàng
     *
     * @return string|null
     */
    public function getAppliedPromotionAttribute()
    {
        if (!$this->service) {
            return null;
        }

        // Đảm bảo mã khuyến mãi được xử lý đúng cách
        $promotionCode = !empty($this->promotion_code) ? strtoupper($this->promotion_code) : null;

        // Tính phần trăm giảm giá
        $originalPrice = $this->service->price;
        $finalPrice = $this->final_price;

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Tính phần trăm giảm giá', [
            'appointment_id' => $this->id,
            'original_price' => $originalPrice,
            'final_price' => $finalPrice,
            'promotion_code' => $promotionCode
        ]);

        if ($originalPrice > 0 && $finalPrice < $originalPrice) {
            $discountPercent = round(($originalPrice - $finalPrice) / $originalPrice * 100);
            return $discountPercent . '%';
        }

        // Nếu không tính được phần trăm, sử dụng giá trị từ service
        $promotionValue = $this->service->getPromotionValueAttribute($promotionCode);

        // Nếu vẫn không có giá trị khuyến mãi nhưng có mã khuyến mãi
        if (empty($promotionValue) && !empty($promotionCode)) {
            // Tìm thông tin khuyến mãi từ cơ sở dữ liệu
            $promotion = \App\Models\Promotion::where('code', $promotionCode)
                ->first();

            if ($promotion) {
                return $promotion->formatted_discount_value;
            }
        }

        return $promotionValue ?: 'Khuyến mãi';
    }

    /**
     * Get the professional notes for the appointment.
     */
    public function professionalNotes()
    {
        return $this->hasMany(ProfessionalNote::class);
    }

    /**
     * Get the payment for the appointment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the invoice for the appointment.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the appointment date attribute.
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function getAppointmentDateAttribute()
    {
        if ($this->date_appointments) {
            // Nếu đã là Carbon instance, trả về luôn
            if ($this->date_appointments instanceof \Illuminate\Support\Carbon) {
                return $this->date_appointments;
            }

            // Nếu là string, chuyển đổi thành Carbon
            try {
                return \Illuminate\Support\Carbon::parse($this->date_appointments);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi chuyển đổi date_appointments: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Get the appointment code attribute.
     *
     * @return string
     */
    public function getAppointmentCodeAttribute()
    {
        return $this->attributes['appointment_code'] ?? ('APT-' . substr($this->id, 0, 8));
    }
}

