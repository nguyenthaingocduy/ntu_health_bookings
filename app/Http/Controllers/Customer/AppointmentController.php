<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);
            
        return view('customer.appointments.index', compact('appointments'));
    }

    public function create($serviceId = null)
    {
        $service = null;
        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
        }
        
        $services = Service::where('status', 'active')
            ->get();
            
        $times = Time::orderBy('started_time')->get();
        
        return view('customer.appointments.create', compact('services', 'times', 'service'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Kiểm tra xem thời gian đã có ai đặt chưa
        $existingAppointment = Appointment::where('date_appointments', $request->date_appointments)
            ->where('time_appointments_id', $request->time_appointments_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();
            
        if ($existingAppointment) {
            return back()->withInput()->with('error', 'Thời gian này đã có người đặt. Vui lòng chọn thời gian khác.');
        }

        try {
            // Tìm một nhân viên bất kỳ để gán tạm thời
            $service = Service::findOrFail($request->service_id);
            
            // Tìm một employee có role là nhân viên
            $employee = \App\Models\User::whereHas('role', function($query) {
                $query->where('name', 'employee');
            })->first();
            
            // Nếu không tìm thấy employee nào, lấy user ID đầu tiên
            $employeeId = $employee ? $employee->id : \App\Models\User::first()->id;
            
            // Tạo UUID cho id
            $uuid = Str::uuid()->toString();
            
            $appointment = Appointment::create([
                'id' => $uuid,
                'customer_id' => Auth::id(),
                'service_id' => $request->service_id,
                'date_register' => now(),
                'status' => 'pending',
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'employee_id' => $employeeId, // Gán nhân viên tạm thời
                'notes' => $request->notes,
            ]);

            // Gửi email xác nhận đặt lịch
            try {
                Log::info('Bắt đầu gửi email xác nhận đặt lịch cho: ' . Auth::user()->email);
                $appointmentWithRelations = Appointment::with(['service', 'customer', 'timeAppointment'])
                    ->findOrFail($appointment->id);
                Mail::to(Auth::user()->email)
                    ->send(new \App\Mail\AppointmentConfirmationMail($appointmentWithRelations));
                Log::info('Đã gửi email xác nhận đặt lịch thành công cho: ' . Auth::user()->email);
            } catch (\Exception $e) {
                Log::error('Không thể gửi email xác nhận đặt lịch: ' . $e->getMessage());
            }

            return redirect()->route('customer.appointments.index')
                ->with('success', 'Đặt lịch thành công. Vui lòng chờ xác nhận từ nhân viên.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $appointment = Appointment::with(['service', 'employee', 'customer', 'timeAppointment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);
            
        return view('customer.appointments.show', compact('appointment'));
    }

    public function cancel($id)
    {
        $appointment = Appointment::where('customer_id', Auth::id())
            ->findOrFail($id);
            
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn này.');
        }
        
        $appointment->update(['status' => 'cancelled']);
        
        return redirect()->route('customer.appointments.index')
            ->with('success', 'Hủy lịch hẹn thành công.');
    }
}
