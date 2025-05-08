<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        return view('le-tan.appointments.index', compact('appointments'));
    }

    /**
     * Hiển thị form tạo lịch hẹn mới
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();
        $services = Service::where('status', 'active')->get();
        $timeSlots = TimeSlot::all();

        return view('le-tan.appointments.create', compact('customers', 'services', 'timeSlots'));
    }

    /**
     * Lưu lịch hẹn mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|exists:time_slots,id',
        ]);

        // Kiểm tra xem khung giờ đã đầy chưa
        $appointmentDate = Carbon::parse($request->appointment_date);
        $existingAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
            ->where('time_slot_id', $request->time_slot_id)
            ->where('status', '!=', 'cancelled')
            ->count();

        $timeSlot = TimeSlot::find($request->time_slot_id);
        if ($existingAppointments >= $timeSlot->capacity) {
            return back()->with('error', 'Khung giờ này đã đầy. Vui lòng chọn khung giờ khác.');
        }

        $appointment = new Appointment();
        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->date_appointments = $appointmentDate;
        $appointment->time_slot_id = $request->time_slot_id;
        $appointment->status = 'pending';
        $appointment->notes = $request->notes;
        $appointment->created_by = Auth::id();
        $appointment->save();

        return redirect()->route('le-tan.appointments.index')
            ->with('success', 'Lịch hẹn đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'service', 'timeSlot', 'employee'])
            ->findOrFail($id);

        // Đảm bảo giá sau khuyến mãi được tính đúng
        if ($appointment->service) {
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
                    \Illuminate\Support\Facades\Log::info('Đã cập nhật giá sau khuyến mãi trong show() của lễ tân', [
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

        return view('le-tan.appointments.show', compact('appointment'));
    }

    /**
     * Hiển thị form chỉnh sửa lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();
        $services = Service::where('status', 'active')->get();
        $timeSlots = TimeSlot::all();

        return view('le-tan.appointments.edit', compact('appointment', 'customers', 'services', 'timeSlots'));
    }

    /**
     * Cập nhật lịch hẹn
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Nếu thay đổi ngày hoặc khung giờ, kiểm tra xem khung giờ đã đầy chưa
        if ($appointment->date_appointments->format('Y-m-d') != $request->appointment_date ||
            $appointment->time_slot_id != $request->time_slot_id) {

            $appointmentDate = Carbon::parse($request->appointment_date);
            $existingAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
                ->where('time_slot_id', $request->time_slot_id)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $id)
                ->count();

            $timeSlot = TimeSlot::find($request->time_slot_id);
            if ($existingAppointments >= $timeSlot->capacity) {
                return back()->with('error', 'Khung giờ này đã đầy. Vui lòng chọn khung giờ khác.');
            }
        }

        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->date_appointments = Carbon::parse($request->appointment_date);
        $appointment->time_slot_id = $request->time_slot_id;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;
        $appointment->updated_by = Auth::id();
        $appointment->save();

        return redirect()->route('le-tan.appointments.index')
            ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
    }

    /**
     * Xác nhận lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            $appointment->status = 'confirmed';
            $appointment->updated_by = Auth::id();
            $appointment->save();

            return redirect()->route('le-tan.appointments.index')
                ->with('success', 'Lịch hẹn đã được xác nhận thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xác nhận lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Hủy lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Giảm số lượng sử dụng mã khuyến mãi nếu có
            if (!empty($appointment->promotion_code)) {
                $promotion = \App\Models\Promotion::where('code', strtoupper($appointment->promotion_code))
                    ->first();

                if ($promotion && $promotion->usage_count > 0) {
                    // Giảm số lượng sử dụng đi 1
                    $promotion->decrement('usage_count');

                    // Log để debug
                    \Illuminate\Support\Facades\Log::info('Đã giảm số lượng sử dụng mã khuyến mãi khi lễ tân hủy lịch', [
                        'promotion_id' => $promotion->id,
                        'promotion_code' => $promotion->code,
                        'old_usage_count' => $promotion->usage_count + 1,
                        'new_usage_count' => $promotion->usage_count,
                        'appointment_id' => $appointment->id
                    ]);
                }
            }

            // Cập nhật trạng thái lịch hẹn
            $appointment->status = 'cancelled';
            $appointment->updated_by = Auth::id();
            $appointment->cancellation_reason = 'Hủy bởi lễ tân';
            $appointment->save();

            DB::commit();

            return redirect()->route('le-tan.appointments.index')
                ->with('success', 'Lịch hẹn đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }
    }
}
