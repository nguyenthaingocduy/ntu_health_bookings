<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Time;
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

        // Lấy danh sách nhân viên kỹ thuật
        $technicians = User::whereHas('role', function($query) {
            $query->where('name', 'Technician');
        })->get();

        // Lấy ngày hiện tại và xác định ngày trong tuần (1-7)
        $today = now();
        $dayOfWeek = $today->dayOfWeek == 0 ? 7 : $today->dayOfWeek; // Chuyển đổi 0 (Chủ nhật) thành 7

        // Lấy các khung giờ có sẵn
        $timeSlots = \App\Models\Time::orderBy('started_time')
            ->get();

        return view('le-tan.appointments.create', compact('customers', 'services', 'timeSlots', 'technicians'));
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
            'time_appointments_id' => 'required|exists:times,id',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        // Kiểm tra xem khung giờ đã đầy chưa
        $appointmentDate = Carbon::parse($request->appointment_date);
        $existingAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
            ->where('time_appointments_id', $request->time_appointments_id)
            ->where('status', '!=', 'cancelled')
            ->count();

        $timeAppointment = \App\Models\Time::find($request->time_appointments_id);
        if ($timeAppointment && $existingAppointments >= $timeAppointment->capacity) {
            return back()->with('error', 'Khung giờ này đã đầy. Vui lòng chọn khung giờ khác.');
        }

        // Nếu phân công nhân viên kỹ thuật, kiểm tra xem nhân viên đã có lịch hẹn khác trong cùng khung giờ chưa
        if ($request->filled('employee_id')) {
            $existingTechnicianAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
                ->where('time_appointments_id', $request->time_appointments_id)
                ->where('employee_id', $request->employee_id)
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($existingTechnicianAppointments > 0) {
                return back()->with('error', 'Nhân viên kỹ thuật đã có lịch hẹn khác trong cùng khung giờ này. Vui lòng chọn nhân viên khác.');
            }
        }

        $appointment = new Appointment();
        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->date_appointments = $appointmentDate;
        $appointment->date_register = now(); // Thêm giá trị cho trường date_register
        $appointment->time_appointments_id = $request->time_appointments_id;
        $appointment->status = 'pending';
        $appointment->notes = $request->notes;
        $appointment->created_by = Auth::id();

        // Phân công nhân viên kỹ thuật nếu được chọn
        if ($request->filled('employee_id')) {
            $appointment->employee_id = $request->employee_id;
        } else {
            // Nếu không có nhân viên kỹ thuật được chọn, gán giá trị null
            $appointment->employee_id = null;
        }

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

        // Lấy danh sách nhân viên kỹ thuật
        $technicians = User::whereHas('role', function($query) {
            $query->where('name', 'Technician');
        })->get();

        // Debug date_appointments
        \Illuminate\Support\Facades\Log::info('Debug date_appointments', [
            'appointment_id' => $appointment->id,
            'date_appointments' => $appointment->date_appointments,
            'date_appointments_type' => gettype($appointment->date_appointments),
            'date_appointments_class' => $appointment->date_appointments ? get_class($appointment->date_appointments) : 'null'
        ]);

        // Xử lý ngày của lịch hẹn
        try {
            if ($appointment->date_appointments instanceof \DateTime) {
                $appointmentDate = $appointment->date_appointments;
            } else {
                $appointmentDate = \Carbon\Carbon::parse($appointment->date_appointments);
            }

            // Lấy tất cả các khung giờ có sẵn
            $timeSlots = \App\Models\Time::orderBy('started_time')
                ->get();

            // Thêm ngày đã định dạng vào dữ liệu
            $appointment->formatted_date = $appointmentDate->format('Y-m-d');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error parsing date: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'date_appointments' => $appointment->date_appointments
            ]);

            // Sử dụng ngày hiện tại nếu có lỗi
            $appointmentDate = now();
            // Lấy tất cả các khung giờ có sẵn
            $timeSlots = \App\Models\Time::orderBy('started_time')
                ->get();
            $appointment->formatted_date = $appointmentDate->format('Y-m-d');
        }

        return view('le-tan.appointments.edit', compact('appointment', 'customers', 'services', 'timeSlots', 'technicians'));
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
            'time_appointments_id' => 'required|exists:times,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Nếu thay đổi ngày hoặc khung giờ, kiểm tra xem khung giờ đã đầy chưa
        if ($appointment->date_appointments->format('Y-m-d') != $request->appointment_date ||
            $appointment->time_appointments_id != $request->time_appointments_id) {

            $appointmentDate = Carbon::parse($request->appointment_date);
            $existingAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
                ->where('time_appointments_id', $request->time_appointments_id)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $id)
                ->count();

            $timeAppointment = \App\Models\Time::find($request->time_appointments_id);
            if ($timeAppointment && $existingAppointments >= $timeAppointment->capacity) {
                return back()->with('error', 'Khung giờ này đã đầy. Vui lòng chọn khung giờ khác.');
            }
        }

        // Nếu phân công nhân viên kỹ thuật, kiểm tra xem nhân viên đã có lịch hẹn khác trong cùng khung giờ chưa
        if ($request->filled('employee_id') &&
            ($appointment->employee_id != $request->employee_id ||
             $appointment->date_appointments->format('Y-m-d') != $request->appointment_date ||
             $appointment->time_appointments_id != $request->time_appointments_id)) {

            $appointmentDate = Carbon::parse($request->appointment_date);
            $existingTechnicianAppointments = Appointment::where('date_appointments', $appointmentDate->format('Y-m-d'))
                ->where('time_appointments_id', $request->time_appointments_id)
                ->where('employee_id', $request->employee_id)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $id)
                ->count();

            if ($existingTechnicianAppointments > 0) {
                return back()->with('error', 'Nhân viên kỹ thuật đã có lịch hẹn khác trong cùng khung giờ này. Vui lòng chọn nhân viên khác.');
            }
        }

        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->date_appointments = Carbon::parse($request->appointment_date);
        $appointment->time_appointments_id = $request->time_appointments_id;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;
        $appointment->updated_by = Auth::id();

        // Cập nhật nhân viên kỹ thuật nếu được chọn
        if ($request->filled('employee_id')) {
            $appointment->employee_id = $request->employee_id;
        }

        $appointment->save();

        return redirect()->route('le-tan.appointments.index')
            ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
    }

    /**
     * Hiển thị form phân công nhân viên kỹ thuật
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignStaff($id)
    {
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment', 'timeSlot'])
            ->findOrFail($id);

        // Lấy danh sách nhân viên kỹ thuật
        $technicians = \App\Models\User::whereHas('role', function($query) {
            $query->where('name', 'Technician');
        })->get();

        // Kiểm tra nhân viên nào có lịch trống vào thời điểm này
        $availableTechnicians = [];
        foreach ($technicians as $technician) {
            // Kiểm tra xem nhân viên đã có lịch hẹn khác trong cùng thời gian chưa
            $hasConflict = Appointment::where('employee_id', $technician->id)
                ->where('date_appointments', $appointment->date_appointments)
                ->where('time_appointments_id', $appointment->time_appointments_id)
                ->where('id', '!=', $appointment->id)
                ->whereIn('status', ['confirmed', 'in_progress'])
                ->exists();

            if (!$hasConflict) {
                $availableTechnicians[] = $technician;
            }
        }

        return view('le-tan.appointments.assign-staff', compact('appointment', 'availableTechnicians'));
    }

    /**
     * Xác nhận lịch hẹn và phân công nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        try {
            // Cập nhật trạng thái và nhân viên phục vụ
            $appointment->status = 'confirmed';
            $appointment->employee_id = $request->employee_id;
            $appointment->updated_by = Auth::id();
            $appointment->save();

            // Tạo lịch làm việc cho nhân viên kỹ thuật
            if ($appointment->timeAppointment) {
                \App\Models\WorkSchedule::updateOrCreate(
                    [
                        'user_id' => $request->employee_id,
                        'date' => $appointment->date_appointments instanceof \Carbon\Carbon
                            ? $appointment->date_appointments->format('Y-m-d')
                            : (is_string($appointment->date_appointments)
                                ? \Carbon\Carbon::parse($appointment->date_appointments)->format('Y-m-d')
                                : date('Y-m-d')),
                    ],
                    [
                        'status' => 'scheduled',
                        'notes' => 'Tự động phân công từ lịch hẹn #' . $appointment->id,
                    ]
                );
            }

            return redirect()->route('le-tan.appointments.index')
                ->with('success', 'Lịch hẹn đã được xác nhận và phân công nhân viên thành công.');
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

    /**
     * Hoàn thành lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            // Cập nhật trạng thái lịch hẹn thành hoàn thành
            $appointment->status = 'completed';
            $appointment->updated_by = Auth::id();
            $appointment->save();

            return redirect()->route('le-tan.appointments.index')
                ->with('success', 'Lịch hẹn đã được hoàn thành thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi hoàn thành lịch hẹn: ' . $e->getMessage());
        }
    }
}
