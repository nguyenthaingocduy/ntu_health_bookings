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
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())
            ->where('employee_id', Auth::id())
            ->count();

        // Lấy số lượng buổi chăm sóc đã hoàn thành
        $completedSessionsCount = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        // Lấy số lượng ghi chú chuyên môn
        $professionalNotesCount = ProfessionalNote::where('created_by', Auth::id())->count();

        // Lấy danh sách lịch hẹn hôm nay
        $todayAppointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->whereDate('appointment_date', Carbon::today())
            ->where('employee_id', Auth::id())
            ->orderBy('time_slot_id')
            ->get();

        // Lấy ghi chú gần đây
        $recentNotes = ProfessionalNote::with('customer')
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('nvkt.dashboard', compact(
            'todayAppointmentsCount',
            'completedSessionsCount',
            'professionalNotesCount',
            'todayAppointments',
            'recentNotes'
        ));
    }
}
