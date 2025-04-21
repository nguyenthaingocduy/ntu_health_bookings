<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use App\Models\User;
use App\Models\Employee;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        // Get all appointments (not just the staff's own appointments)
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment', 'customer'])
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        // Get appointment statistics
        $statistics = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('staff.appointments.index_new', compact('appointments', 'statistics'));
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

        // Get all active services
        $services = Service::where('status', 'active')->get();

        // Get all time slots
        $times = Time::orderBy('started_time')->get();

        // Get all customers (users with customer role)
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();

        return view('staff.appointments.create_new', compact('services', 'times', 'service', 'customers'));
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
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,confirmed',
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
            // Find the service
            $service = Service::findOrFail($request->service_id);

            // Find the customer
            $customer = User::findOrFail($request->customer_id);

            // Get an employee who works at the clinic that offers this service
            $employee = Employee::whereHas('clinic', function($query) use ($service) {
                $query->where('id', $service->clinic_id);
            })->first();

            // If no employee is found, assign the current staff member
            $employeeId = $employee ? $employee->id : Auth::id();

            // Create UUID for id
            $uuid = Str::uuid()->toString();

            // Create the appointment
            $appointment = Appointment::create([
                'id' => $uuid,
                'customer_id' => $request->customer_id,
                'service_id' => $request->service_id,
                'date_register' => now(),
                'status' => $request->status,
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'employee_id' => $employeeId,
                'notes' => $request->notes,
            ]);

            // Send notification to the customer if enabled
            if ($customer->email_notifications_enabled) {
                try {
                    $customer->notify(new AppointmentNotification($appointment, $request->status === 'confirmed' ? 'confirmed' : 'created'));
                } catch (\Exception $e) {
                    // Log notification error but continue
                    \Illuminate\Support\Facades\Log::error('Failed to send appointment notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('staff.appointments.index')
                ->with('success', 'Lịch hẹn đã được tạo thành công cho khách hàng ' . $customer->first_name . ' ' . $customer->last_name);
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
            ->findOrFail($id);

        return view('staff.appointments.show_new', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment'])
            ->findOrFail($id);

        // Get all active services
        $services = Service::where('status', 'active')->get();

        // Get all time slots
        $times = Time::orderBy('started_time')->get();

        // Get all customers
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();

        return view('staff.appointments.edit_new', compact('appointment', 'services', 'times', 'customers'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date_appointments' => 'required|date|after_or_equal:today',
            'time_appointments_id' => 'required|exists:times,id',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Check if the time slot is already booked by another appointment
        if ($request->date_appointments != $appointment->date_appointments ||
            $request->time_appointments_id != $appointment->time_appointments_id) {

            $existingAppointment = Appointment::where('id', '!=', $id)
                ->where('date_appointments', $request->date_appointments)
                ->where('time_appointments_id', $request->time_appointments_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingAppointment) {
                return back()->withInput()->with('error', 'Thời gian này đã có người đặt. Vui lòng chọn thời gian khác.');
            }
        }

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Kiểm tra nếu trạng thái được thay đổi thành 'cancelled'
            $originalStatus = $appointment->status;
            $newStatus = $request->status;

            // Nếu trạng thái được thay đổi thành 'cancelled', giảm số lượng đặt chỗ
            if ($newStatus === 'cancelled' && in_array($originalStatus, ['pending', 'confirmed'])) {
                $timeSlot = Time::findOrFail($appointment->time_appointments_id);
                if ($timeSlot->booked_count > 0) {
                    $timeSlot->decrement('booked_count');
                }
            }

            // Update the appointment
            $appointment->update([
                'service_id' => $request->service_id,
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            DB::commit();

            // Send notification if status changed
            if ($appointment->customer->email_notifications_enabled) {
                if ($newStatus !== $originalStatus) {
                    try {
                        $appointment->customer->notify(new AppointmentNotification($appointment, $newStatus));
                    } catch (\Exception $e) {
                        // Log notification error but continue
                        \Illuminate\Support\Facades\Log::error('Failed to send appointment notification: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('staff.appointments.show', $appointment->id)
                ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified appointment.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $id)
    {
        $appointment = Appointment::with('customer')->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Giảm số lượng đặt chỗ cho khung giờ này
            $timeSlot = Time::findOrFail($appointment->time_appointments_id);
            if ($timeSlot->booked_count > 0) {
                $timeSlot->decrement('booked_count');
            }

            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $request->cancellation_reason;
            $appointment->save();

            DB::commit();

            // Send cancellation notification to the customer
            if ($appointment->customer && $appointment->customer->email_notifications_enabled &&
                $appointment->customer->notify_appointment_cancellation) {
                try {
                    $appointment->customer->notify(new AppointmentNotification($appointment, 'cancelled'));
                } catch (\Exception $e) {
                    // Log notification error but continue
                    \Illuminate\Support\Facades\Log::error('Failed to send cancellation notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('staff.appointments.index')
                ->with('success', 'Lịch hẹn đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }
    }
}
