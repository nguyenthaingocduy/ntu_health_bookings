<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'service', 'employee', 'timeAppointment'])
            ->orderBy('date_appointments', 'desc')
            ->paginate(10);

        // Calculate statistics
        $statistics = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'statistics'));
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

        return view('admin.appointments.create', compact('services', 'times', 'service', 'customers'));
    }

    public function show($id)
    {
        $appointment = Appointment::with(['user', 'service', 'employee', 'timeAppointment'])
            ->findOrFail($id);

        // Get customer history (other appointments by the same user)
        $customerHistory = Appointment::where('customer_id', $appointment->customer_id)
            ->where('id', '!=', $appointment->id)
            ->with('service')
            ->orderBy('date_appointments', 'desc')
            ->get();

        return view('admin.appointments.show', compact('appointment', 'customerHistory'));
    }

    public function edit($id)
    {
        $appointment = Appointment::with(['user', 'service', 'employee', 'timeAppointment'])
            ->findOrFail($id);
        $employees = Employee::all();
        $times = Time::orderBy('started_time')->get();

        return view('admin.appointments.edit', compact('appointment', 'employees', 'times'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed',
            'date_appointments' => 'required|date',
            'time_appointments_id' => 'required|exists:times,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Kiểm tra xem nhân viên có lịch trùng không
        $existingAppointment = Appointment::where('employee_id', $request->employee_id)
            ->where('date_appointments', $request->date_appointments)
            ->where('time_appointments_id', $request->time_appointments_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'Nhân viên đã có lịch hẹn khác trong thời gian này.');
        }

        // Bắt đầu transaction
        \Illuminate\Support\Facades\DB::beginTransaction();

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

            $appointment->update([
                'status' => $request->status,
                'date_appointments' => $request->date_appointments,
                'time_appointments_id' => $request->time_appointments_id,
                'employee_id' => $request->employee_id,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Lịch hẹn đã được xóa thành công.');
    }

    public function confirm($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Lịch hẹn đã được xác nhận thành công.');
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Kiểm tra trạng thái hiện tại của lịch hẹn
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }

        // Bắt đầu transaction
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Giảm số lượng đặt chỗ cho khung giờ này
            $timeSlot = Time::findOrFail($appointment->time_appointments_id);
            if ($timeSlot->booked_count > 0) {
                $timeSlot->decrement('booked_count');
            }

            $appointment->update(['status' => 'cancelled']);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()->with('success', 'Lịch hẹn đã được hủy thành công.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi hủy lịch hẹn: ' . $e->getMessage());
        }
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Lịch hẹn đã được hoàn thành thành công.');
    }
}
