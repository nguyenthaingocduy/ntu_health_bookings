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
        'promotion_code'
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
        if (!$this->service) {
            return 0;
        }

        // Đảm bảo mã khuyến mãi được xử lý đúng cách
        $promotionCode = !empty($this->promotion_code) ? strtoupper($this->promotion_code) : null;

        // Tính giá sau khuyến mãi
        $finalPrice = $this->service->calculatePriceWithPromotion($promotionCode);

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Tính giá sau khuyến mãi', [
            'appointment_id' => $this->id,
            'service_id' => $this->service_id,
            'service_price' => $this->service->price,
            'promotion_code' => $promotionCode,
            'final_price' => $finalPrice
        ]);

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

        if ($originalPrice > 0 && $finalPrice < $originalPrice) {
            $discountPercent = round(($originalPrice - $finalPrice) / $originalPrice * 100);
            return $discountPercent . '%';
        }

        // Nếu không tính được phần trăm, sử dụng giá trị từ service
        $promotionValue = $this->service->getPromotionValueAttribute($promotionCode);
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
}

