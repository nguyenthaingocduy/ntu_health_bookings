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
