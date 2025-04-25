<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('appointment_date', 'desc')
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
        $customers = Customer::all();
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
        $existingAppointments = Appointment::where('appointment_date', $appointmentDate->format('Y-m-d'))
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
        $appointment->appointment_date = $appointmentDate;
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
        $customers = Customer::all();
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
        if ($appointment->appointment_date->format('Y-m-d') != $request->appointment_date || 
            $appointment->time_slot_id != $request->time_slot_id) {
            
            $appointmentDate = Carbon::parse($request->appointment_date);
            $existingAppointments = Appointment::where('appointment_date', $appointmentDate->format('Y-m-d'))
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
        $appointment->appointment_date = Carbon::parse($request->appointment_date);
        $appointment->time_slot_id = $request->time_slot_id;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;
        $appointment->updated_by = Auth::id();
        $appointment->save();

        return redirect()->route('le-tan.appointments.index')
            ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
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
        $appointment->status = 'cancelled';
        $appointment->updated_by = Auth::id();
        $appointment->save();

        return redirect()->route('le-tan.appointments.index')
            ->with('success', 'Lịch hẹn đã được hủy thành công.');
    }
}
