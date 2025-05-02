<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Hiển thị lịch làm việc của nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        $timeSlots = TimeSlot::orderBy('start_time')->get();

        $appointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startOfWeek, $endOfWeek])
            ->orderBy('date_appointments')
            ->orderBy('time_slot_id')
            ->get();

        $schedule = [];

        foreach ($timeSlots as $timeSlot) {
            $schedule[$timeSlot->id] = [
                'time_slot' => $timeSlot,
                'days' => []
            ];

            for ($day = 0; $day < 7; $day++) {
                $currentDate = $startOfWeek->copy()->addDays($day);
                $dayAppointments = $appointments->filter(function ($appointment) use ($currentDate, $timeSlot) {
                    return $appointment->date_appointments->isSameDay($currentDate) &&
                           $appointment->time_slot_id == $timeSlot->id;
                });

                $schedule[$timeSlot->id]['days'][$day] = [
                    'date' => $currentDate,
                    'appointments' => $dayAppointments
                ];
            }
        }

        return view('nvkt.schedule.index', compact('schedule', 'startOfWeek', 'endOfWeek', 'timeSlots'));
    }

    /**
     * Hiển thị danh sách lịch hẹn được phân công cho nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignedAppointments(Request $request)
    {
        $appointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->where('status', 'confirmed')
            ->orderBy('date_appointments')
            ->orderBy('time_slot_id')
            ->paginate(10);

        return view('nvkt.schedule.assigned', compact('appointments'));
    }
}
