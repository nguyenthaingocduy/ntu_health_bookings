<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();

        // Lấy số lượng khách hàng
        $customersCount = Customer::count();

        // Lấy số lượng thanh toán hôm nay
        $todayPaymentsCount = Payment::whereDate('created_at', Carbon::today())->count();

        // Lấy danh sách lịch hẹn sắp tới
        $upcomingAppointments = Appointment::with(['customer', 'service', 'timeSlot'])
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->orderBy('time_slot_id')
            ->limit(5)
            ->get();

        // Lấy thông báo gần đây
        $recentNotifications = Notification::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('le-tan.dashboard', compact(
            'todayAppointmentsCount',
            'customersCount',
            'todayPaymentsCount',
            'upcomingAppointments',
            'recentNotifications'
        ));
    }
}
