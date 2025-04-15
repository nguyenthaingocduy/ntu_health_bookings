<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Time;
use Illuminate\Http\Request;
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

        $appointment->update([
            'status' => $request->status,
            'date_appointments' => $request->date_appointments,
            'time_appointments_id' => $request->time_appointments_id,
            'employee_id' => $request->employee_id,
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Lịch hẹn đã được cập nhật thành công.');
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
        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Lịch hẹn đã được hủy thành công.');
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Lịch hẹn đã được hoàn thành thành công.');
    }
}
