<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    /**
     * Kiểm tra tính khả dụng của nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkTechnicianAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'technician_id' => 'required|exists:users,id',
        ]);

        $date = Carbon::parse($request->date);
        $timeSlotId = $request->time_slot_id;
        $technicianId = $request->technician_id;

        // Kiểm tra xem nhân viên kỹ thuật đã có lịch hẹn khác trong cùng khung giờ chưa
        $existingAppointments = Appointment::where('date_appointments', $date->format('Y-m-d'))
            ->where('time_slot_id', $timeSlotId)
            ->where('employee_id', $technicianId)
            ->where('status', '!=', 'cancelled')
            ->count();

        return response()->json([
            'available' => $existingAppointments === 0,
            'date' => $date->format('Y-m-d'),
            'time_slot_id' => $timeSlotId,
            'technician_id' => $technicianId,
        ]);
    }
}
