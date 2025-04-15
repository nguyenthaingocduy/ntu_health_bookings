<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the staff's appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);
            
        return view('staff.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @param int|null $serviceId
     * @return \Illuminate\View\View
     */
    public function create($serviceId = null)
    {
        $service = null;
        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
        }
        
        // Get only health check-up related services
        $services = Service::where('status', 'active')
            ->whereHas('category', function($query) {
                $query->where('name', 'like', '%health%');
            })
            ->get();
            
        $times = Time::orderBy('started_time')->get();
        
        return view('staff.appointments.create', compact('services', 'times', 'service'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if the time slot is already booked
        $existingAppointment = Appointment::where('date_appointments', $request->date_appointments)
            ->where('time_appointments_id', $request->time_appointments_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();
            
        if ($existingAppointment) {
            return back()->withInput()->with('error', 'Thời gian này đã có người đặt. Vui lòng chọn thời gian khác.');
        }

        try {
            // Find an available employee for the appointment
            $service = Service::findOrFail($request->service_id);
            
            // Get an employee who works at the clinic that offers this service
            $employee = \App\Models\Employee::whereHas('clinic', function($query) use ($service) {
                $query->where('id', $service->clinic_id);
            })->first();
            
            // If no employee is found, use the first user ID
            $employeeId = $employee ? $employee->id : \App\Models\User::first()->id;
            
            // Create UUID for id
            $uuid = Str::uuid()->toString();
            
            $appointment = Appointment::create([
                'id' => $uuid,
                'customer_id' => Auth::id(),
                'service_id' => $request->service_id,
                'date_register' => now(),
                'status' => 'pending',
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'employee_id' => $employeeId,
                'notes' => $request->notes,
            ]);
            
            // Send email notification (if needed)
            // ...
            
            return redirect()->route('staff.appointments.index')
                ->with('success', 'Đặt lịch khám sức khỏe thành công. Vui lòng chờ xác nhận từ nhân viên.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);
            
        return view('staff.appointments.show', compact('appointment'));
    }

    /**
     * Cancel the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $appointment = Appointment::where('customer_id', Auth::id())->findOrFail($id);
        
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }
        
        $appointment->status = 'cancelled';
        $appointment->save();
        
        return redirect()->route('staff.appointments.index')
            ->with('success', 'Lịch hẹn đã được hủy thành công.');
    }
}
