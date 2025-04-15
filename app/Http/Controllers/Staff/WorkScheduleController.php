<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkScheduleController extends Controller
{
    /**
     * Display the staff's work schedule.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all appointments assigned to the staff member
        $appointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', Auth::id())
            ->get();

        // Format appointments for FullCalendar
        $events = [];

        foreach ($appointments as $appointment) {
            $date = Carbon::parse($appointment->date_appointments)->format('Y-m-d');
            $time = $appointment->timeAppointment ? $appointment->timeAppointment->started_time : '08:00:00';

            // Determine color based on status
            $color = '#FCD34D'; // Default yellow for pending

            if ($appointment->status === 'confirmed') {
                $color = '#60A5FA'; // Blue
            } elseif ($appointment->status === 'completed') {
                $color = '#34D399'; // Green
            } elseif ($appointment->status === 'cancelled') {
                $color = '#F87171'; // Red
            }

            $events[] = [
                'id' => $appointment->id,
                'title' => $appointment->customer->first_name . ' ' . $appointment->customer->last_name,
                'start' => $date . 'T' . $time,
                'color' => $color,
                'extendedProps' => [
                    'customerName' => $appointment->customer->first_name . ' ' . $appointment->customer->last_name,
                    'serviceName' => $appointment->service->name,
                    'formattedDate' => Carbon::parse($appointment->date_appointments)->format('d/m/Y'),
                    'formattedTime' => $appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A',
                    'status' => $appointment->status,
                    'notes' => $appointment->notes,
                    'viewUrl' => route('staff.appointments.show', $appointment->id)
                ]
            ];
        }

        // Get today's appointments for quick view
        $todayAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', Auth::id())
            ->whereDate('date_appointments', Carbon::today())
            ->orderBy('date_appointments')
            ->get();

        return view('staff.work_schedule_new', compact('events', 'todayAppointments'));
    }

    /**
     * Mark an appointment as completed.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeAppointment($id)
    {
        $appointment = Appointment::where('employee_id', Auth::id())->findOrFail($id);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Chỉ có thể hoàn thành lịch hẹn đang chờ xác nhận hoặc đã xác nhận.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        return redirect()->route('staff.work-schedule')
            ->with('success', 'Lịch hẹn đã được đánh dấu là hoàn thành.');
    }
}
