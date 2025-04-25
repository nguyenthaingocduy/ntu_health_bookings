<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
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
        
        $appointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->orderBy('appointment_date')
            ->orderBy('time_slot_id')
            ->get();
            
        $groupedAppointments = $appointments->groupBy(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        });
        
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'date' => $currentDay,
                'appointments' => $groupedAppointments->get($currentDay->format('Y-m-d'), collect()),
            ];
        }
        
        return view('nvkt.schedule.index', compact('days', 'date', 'startOfWeek', 'endOfWeek'));
    }
}
