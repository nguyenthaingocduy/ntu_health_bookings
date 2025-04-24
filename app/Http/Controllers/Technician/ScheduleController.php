<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display the technician's schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy số lượng lịch hẹn hôm nay
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())
            ->where('employee_id', Auth::id())
            ->count();
        
        // Lấy số lượng lịch hẹn trong tuần
        $weekAppointmentsCount = Appointment::whereBetween('appointment_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->where('employee_id', Auth::id())
            ->count();
        
        // Lấy số lượng lịch hẹn đã hoàn thành
        $completedAppointmentsCount = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->count();
        
        // Lấy số lượng lịch hẹn sắp tới
        $upcomingAppointmentsCount = Appointment::where('employee_id', Auth::id())
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->count();
        
        // Lấy tất cả lịch hẹn cho lịch
        $appointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->where('employee_id', Auth::id())
            ->whereDate('appointment_date', '>=', Carbon::now()->subMonths(1))
            ->whereDate('appointment_date', '<=', Carbon::now()->addMonths(3))
            ->get();
        
        // Định dạng dữ liệu cho FullCalendar
        $events = [];
        foreach ($appointments as $appointment) {
            // Xác định màu sắc dựa trên trạng thái
            $color = '#3788d8'; // Mặc định: xanh dương (đã xác nhận)
            
            if ($appointment->status == 'pending') {
                $color = '#ffc107'; // Vàng: chờ xác nhận
            } elseif ($appointment->status == 'completed') {
                $color = '#28a745'; // Xanh lá: hoàn thành
            } elseif ($appointment->status == 'cancelled') {
                $color = '#dc3545'; // Đỏ: đã hủy
            }
            
            // Định dạng trạng thái hiển thị
            $statusText = 'Đã xác nhận';
            if ($appointment->status == 'pending') {
                $statusText = 'Chờ xác nhận';
            } elseif ($appointment->status == 'completed') {
                $statusText = 'Hoàn thành';
            } elseif ($appointment->status == 'cancelled') {
                $statusText = 'Đã hủy';
            }
            
            $events[] = [
                'id' => $appointment->id,
                'title' => $appointment->customer->full_name . ' - ' . $appointment->service->name,
                'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->timeSlot->start_time,
                'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->timeSlot->end_time,
                'color' => $color,
                'customer_name' => $appointment->customer->full_name,
                'service_name' => $appointment->service->name,
                'formatted_date' => $appointment->appointment_date->format('d/m/Y'),
                'time_slot' => $appointment->timeSlot->start_time . ' - ' . $appointment->timeSlot->end_time,
                'status' => $appointment->status,
                'status_text' => $statusText,
                'notes' => $appointment->notes,
            ];
        }
        
        return view('technician.schedule.index', compact(
            'todayAppointmentsCount',
            'weekAppointmentsCount',
            'completedAppointmentsCount',
            'upcomingAppointmentsCount',
            'events'
        ));
    }
}
