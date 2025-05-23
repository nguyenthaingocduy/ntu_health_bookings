<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Báo cáo doanh thu
     */
    public function revenue(Request $request)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasPermission('reports.view') && !auth()->user()->hasDirectPermission('reports', 'view')) {
            abort(403, 'Bạn không có quyền xem báo cáo');
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Tổng doanh thu
        $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        // Doanh thu theo ngày
        $dailyRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Doanh thu theo dịch vụ
        $serviceRevenue = Payment::join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->selectRaw('services.name, SUM(payments.amount) as total')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->get();

        return view('le-tan.reports.revenue', compact(
            'totalRevenue',
            'dailyRevenue', 
            'serviceRevenue',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Báo cáo lịch hẹn
     */
    public function appointments(Request $request)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasPermission('reports.view') && !auth()->user()->hasDirectPermission('reports', 'view')) {
            abort(403, 'Bạn không có quyền xem báo cáo');
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Tổng số lịch hẹn
        $totalAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();

        // Lịch hẹn theo trạng thái
        $appointmentsByStatus = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Lịch hẹn theo ngày
        $dailyAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Lịch hẹn theo dịch vụ
        $appointmentsByService = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->selectRaw('services.name, COUNT(*) as count')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('count')
            ->get();

        return view('le-tan.reports.appointments', compact(
            'totalAppointments',
            'appointmentsByStatus',
            'dailyAppointments',
            'appointmentsByService',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Báo cáo dịch vụ
     */
    public function services(Request $request)
    {
        // Kiểm tra quyền
        if (!auth()->user()->hasPermission('reports.view') && !auth()->user()->hasDirectPermission('reports', 'view')) {
            abort(403, 'Bạn không có quyền xem báo cáo');
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Dịch vụ phổ biến nhất
        $popularServices = Service::join('appointments', 'services.id', '=', 'appointments.service_id')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->selectRaw('services.name, services.price, COUNT(appointments.id) as booking_count, SUM(services.price) as total_revenue')
            ->groupBy('services.id', 'services.name', 'services.price')
            ->orderByDesc('booking_count')
            ->get();

        // Tổng số dịch vụ được đặt
        $totalServiceBookings = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();

        // Dịch vụ theo tháng
        $monthlyServices = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(appointments.created_at) as month, YEAR(appointments.created_at) as year, services.name, COUNT(*) as count')
            ->groupBy('year', 'month', 'services.id', 'services.name')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('le-tan.reports.services', compact(
            'popularServices',
            'totalServiceBookings',
            'monthlyServices',
            'startDate',
            'endDate'
        ));
    }
}
