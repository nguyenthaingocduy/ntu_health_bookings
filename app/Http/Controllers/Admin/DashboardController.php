<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Traits\HasRoleCheck;

class DashboardController extends Controller
{
    use HasRoleCheck;

    public function index()
    {
        if ($redirect = $this->checkRole('admin')) {
            return $redirect;
        }

        // Đếm số lượng khách hàng
        $totalCustomers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->count();

        // Đếm số lượng nhân viên từ bảng Employee
        $totalEmployees = \App\Models\Employee::count();

        // Đếm tổng số lượng dịch vụ
        $totalServices = Service::count();

        // Đếm tổng số lịch hẹn
        $totalAppointments = Appointment::count();

        // Thêm thống kê lịch hẹn theo nhân viên
        $staffAppointments = Appointment::select('employee_id')
            ->whereNotNull('employee_id')
            ->groupBy('employee_id')
            ->selectRaw('employee_id, count(*) as total')
            ->with(['employee' => function($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Staff Appointments', [
            'count' => $staffAppointments->count(),
            'data' => $staffAppointments->toArray()
        ]);

        // Thêm thống kê khách hàng có nhiều lịch hẹn nhất
        $topCustomers = Appointment::select('customer_id')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, count(*) as total')
            ->with(['user' => function($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Log để debug
        \Illuminate\Support\Facades\Log::info('Top Customers', [
            'count' => $topCustomers->count(),
            'data' => $topCustomers->toArray()
        ]);

        $recentAppointments = Appointment::with(['user', 'service', 'employee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $confirmedAppointments = Appointment::where('status', 'confirmed')->count();
        $canceledAppointments = Appointment::where('status', 'cancelled')->count();

        $data = [
            'totalCustomers' => $totalCustomers,
            'totalEmployees' => $totalEmployees,
            'totalServices' => $totalServices,
            'totalAppointments' => $totalAppointments,
            'recentAppointments' => $recentAppointments,
            'pendingAppointments' => $pendingAppointments,
            'completedAppointments' => $completedAppointments,
            'confirmedAppointments' => $confirmedAppointments,
            'canceledAppointments' => $canceledAppointments,
            'staffAppointments' => $staffAppointments,
            'topCustomers' => $topCustomers,
        ];

        return view('admin.dashboard', $data);
    }
}
