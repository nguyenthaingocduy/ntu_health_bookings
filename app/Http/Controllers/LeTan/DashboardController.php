<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\ServiceConsultation;
use App\Models\Reminder;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang tổng quan của lễ tân
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy số lượng lịch hẹn hôm nay
        $todayAppointmentsCount = Appointment::whereDate('date_appointments', Carbon::today())->count();

        // Lấy số lượng khách hàng
        $customersCount = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->count();

        // Lấy số lượng thanh toán hôm nay
        $todayPaymentsCount = Payment::whereDate('created_at', Carbon::today())->count();

        // Lấy danh sách lịch hẹn sắp tới
        $upcomingAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->whereDate('date_appointments', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date_appointments')
            ->orderBy('time_appointments_id')
            ->limit(5)
            ->get();

        // Lấy thông báo gần đây
        $recentNotifications = Notification::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy số lượng tư vấn dịch vụ đang chờ
        try {
            $pendingConsultationsCount = ServiceConsultation::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingConsultationsCount = 0;
        }

        // Lấy số lượng nhắc nhở lịch hẹn đang chờ
        try {
            $pendingRemindersCount = Reminder::where('status', 'pending')
                ->where('reminder_date', '>=', Carbon::now())
                ->count();
        } catch (\Exception $e) {
            $pendingRemindersCount = 0;
        }

        // Lấy số lượng hóa đơn đã tạo trong tháng này
        $monthlyInvoicesCount = Invoice::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Lấy tổng doanh thu trong tháng này
        $monthlyRevenue = Payment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'completed')
            ->sum('amount');

        // Lấy top 5 dịch vụ được đặt nhiều nhất
        $topServices = DB::table('services')
            ->join('appointments', 'services.id', '=', 'appointments.service_id')
            ->select('services.id', 'services.name', DB::raw('count(appointments.id) as appointment_count'))
            ->where('appointments.status', '!=', 'cancelled')
            ->groupBy('services.id', 'services.name')
            ->orderBy('appointment_count', 'desc')
            ->limit(5)
            ->get();

        // Lấy danh sách tư vấn dịch vụ gần đây
        try {
            $recentConsultations = ServiceConsultation::with(['customer', 'service', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentConsultations = collect([]);
        }

        // Lấy danh sách nhắc nhở lịch hẹn sắp tới
        try {
            $upcomingReminders = Reminder::with(['appointment', 'appointment.customer', 'appointment.service'])
                ->where('status', 'pending')
                ->where('reminder_date', '>=', Carbon::now())
                ->orderBy('reminder_date')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $upcomingReminders = collect([]);
        }

        return view('le-tan.dashboard', compact(
            'todayAppointmentsCount',
            'customersCount',
            'todayPaymentsCount',
            'upcomingAppointments',
            'recentNotifications',
            'pendingConsultationsCount',
            'pendingRemindersCount',
            'monthlyInvoicesCount',
            'monthlyRevenue',
            'topServices',
            'recentConsultations',
            'upcomingReminders'
        ));
    }
}
