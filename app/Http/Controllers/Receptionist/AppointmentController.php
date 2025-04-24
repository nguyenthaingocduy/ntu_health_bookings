<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['customer', 'service', 'timeSlot', 'employee']);
        
        // Lọc theo ngày
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Tìm kiếm theo tên khách hàng hoặc số điện thoại
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('time_slot_id')
            ->paginate(10);
        
        return view('receptionist.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        $services = Service::orderBy('name')->get();
        $employees = Employee::whereHas('role', function ($query) {
            $query->where('name', 'Technician');
        })->orderBy('full_name')->get();
        $timeSlots = TimeSlot::orderBy('start_time')->get();
        
        return view('receptionist.appointments.create', compact('customers', 'services', 'employees', 'timeSlots'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|exists:time_slots,id',
            'employee_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Kiểm tra xem thời gian đã được đặt chưa
        $existingAppointment = Appointment::where('appointment_date', $request->appointment_date)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if ($existingAppointment) {
            return redirect()->back()->with('error', 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.');
        }
        
        // Tạo mã lịch hẹn
        $appointmentCode = 'APT-' . strtoupper(Str::random(6));
        
        // Tạo lịch hẹn mới
        $appointment = new Appointment();
        $appointment->id = Str::uuid();
        $appointment->appointment_code = $appointmentCode;
        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->time_slot_id = $request->time_slot_id;
        $appointment->employee_id = $request->employee_id;
        $appointment->notes = $request->notes;
        $appointment->status = 'confirmed';
        $appointment->created_by = Auth::id();
        $appointment->save();
        
        return redirect()->route('receptionist.appointments.index')->with('success', 'Lịch hẹn đã được tạo thành công.');
    }

    /**
     * Display the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'service', 'timeSlot', 'employee'])->findOrFail($id);
        
        return view('receptionist.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $customers = Customer::orderBy('full_name')->get();
        $services = Service::orderBy('name')->get();
        $employees = Employee::whereHas('role', function ($query) {
            $query->where('name', 'Technician');
        })->orderBy('full_name')->get();
        $timeSlots = TimeSlot::orderBy('start_time')->get();
        
        return view('receptionist.appointments.edit', compact('appointment', 'customers', 'services', 'employees', 'timeSlots'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'employee_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $appointment = Appointment::findOrFail($id);
        
        // Kiểm tra xem thời gian đã được đặt chưa (nếu thay đổi)
        if ($appointment->appointment_date != $request->appointment_date || 
            $appointment->time_slot_id != $request->time_slot_id || 
            $appointment->employee_id != $request->employee_id) {
            
            $existingAppointment = Appointment::where('appointment_date', $request->appointment_date)
                ->where('time_slot_id', $request->time_slot_id)
                ->where('employee_id', $request->employee_id)
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $id)
                ->first();
            
            if ($existingAppointment) {
                return redirect()->back()->with('error', 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.');
            }
        }
        
        // Cập nhật lịch hẹn
        $appointment->customer_id = $request->customer_id;
        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->time_slot_id = $request->time_slot_id;
        $appointment->employee_id = $request->employee_id;
        $appointment->status = $request->status;
        $appointment->notes = $request->notes;
        $appointment->updated_by = Auth::id();
        $appointment->save();
        
        return redirect()->route('receptionist.appointments.index')->with('success', 'Lịch hẹn đã được cập nhật thành công.');
    }

    /**
     * Cancel the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->updated_by = Auth::id();
        $appointment->save();
        
        return redirect()->route('receptionist.appointments.index')->with('success', 'Lịch hẹn đã được hủy thành công.');
    }
}
