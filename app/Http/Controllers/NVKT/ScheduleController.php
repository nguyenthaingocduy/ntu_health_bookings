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

        // Lấy các khung giờ duy nhất, sắp xếp theo thời gian bắt đầu
        $timeSlots = TimeSlot::select('start_time', 'end_time')
            ->distinct()
            ->orderBy('start_time')
            ->get()
            ->unique(function ($item) {
                // Sử dụng substr thay vì format vì start_time là string, không phải Carbon
                return substr($item->start_time, 0, 5);
            });

        $appointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startOfWeek, $endOfWeek])
            ->orderBy('date_appointments')
            ->orderBy('time_appointments_id')
            ->get();

        $schedule = [];

        foreach ($timeSlots as $index => $timeSlot) {
            $scheduleKey = substr($timeSlot->start_time, 0, 5); // Sử dụng substr thay vì format
            $schedule[$scheduleKey] = [
                'time_slot' => $timeSlot,
                'days' => []
            ];

            for ($day = 0; $day < 7; $day++) {
                $currentDate = $startOfWeek->copy()->addDays($day);
                $currentDayOfWeek = $currentDate->dayOfWeek == 0 ? 7 : $currentDate->dayOfWeek; // Chuyển đổi 0 (Chủ nhật) thành 7

                // Tìm khung giờ tương ứng cho ngày hiện tại
                // Không sử dụng day_of_week vì cột này không tồn tại
                $dayTimeSlot = TimeSlot::whereTime('start_time', $timeSlot->start_time)
                    ->first();

                $dayTimeSlotId = $dayTimeSlot ? $dayTimeSlot->id : null;

                $dayAppointments = $appointments->filter(function ($appointment) use ($currentDate, $dayTimeSlotId) {
                    return $appointment->date_appointments->isSameDay($currentDate) &&
                           $appointment->time_appointments_id == $dayTimeSlotId;
                });

                $schedule[$scheduleKey]['days'][$day] = [
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
        $appointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', Auth::id())
            ->where('status', 'confirmed')
            ->orderBy('date_appointments')
            ->orderBy('time_appointments_id')
            ->paginate(10);

        return view('nvkt.schedule.assigned', compact('appointments'));
    }
}
