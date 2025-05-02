<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProfessionalNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang tổng quan của nhân viên kỹ thuật
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy số lượng lịch hẹn hôm nay
        $todayAppointmentsCount = Appointment::whereDate('date_appointments', Carbon::today())
            ->where('employee_id', Auth::id())
            ->count();

        // Lấy số lượng buổi chăm sóc đã hoàn thành
        $completedSessionsCount = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        // Lấy số lượng ghi chú chuyên môn
        $professionalNotesCount = ProfessionalNote::where('created_by', Auth::id())->count();

        // Lấy danh sách lịch hẹn hôm nay
        $todayAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->whereDate('date_appointments', Carbon::today())
            ->where('employee_id', Auth::id())
            ->orderBy('time_appointments_id')
            ->get();

        // Lấy danh sách lịch hẹn sắp tới (trong 7 ngày tới)
        $upcomingAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->whereDate('date_appointments', '>', Carbon::today())
            ->whereDate('date_appointments', '<=', Carbon::today()->addDays(7))
            ->where('employee_id', Auth::id())
            ->orderBy('date_appointments')
            ->orderBy('time_appointments_id')
            ->get();

        // Lấy danh sách lịch hẹn cần chú ý (đang chờ xử lý hoặc đang tiến hành)
        $pendingAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where('employee_id', Auth::id())
            ->orderBy('date_appointments')
            ->orderBy('time_appointments_id')
            ->limit(5)
            ->get();

        // Lấy ghi chú gần đây
        $recentNotes = ProfessionalNote::with('customer')
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy danh sách khách hàng thường xuyên (có nhiều lịch hẹn nhất)
        $regularCustomers = Appointment::with('customer')
            ->where('employee_id', Auth::id())
            ->select('customer_id')
            ->selectRaw('COUNT(*) as appointment_count')
            ->groupBy('customer_id')
            ->orderByDesc('appointment_count')
            ->limit(5)
            ->get();

        return view('nvkt.dashboard', compact(
            'todayAppointmentsCount',
            'completedSessionsCount',
            'professionalNotesCount',
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'recentNotes',
            'regularCustomers'
        ));
    }
}
