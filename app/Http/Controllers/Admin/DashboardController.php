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

        $totalCustomers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->count();
        $totalEmployees = User::whereHas('role', function($query) {
            $query->where('name', 'Staff');
        })->count();
        $totalServices = Service::count();
        $totalAppointments = Appointment::count();

        $recentAppointments = Appointment::with(['user', 'service', 'employee'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();

        $data = [
            'totalCustomers' => $totalCustomers,
            'totalEmployees' => $totalEmployees,
            'totalServices' => $totalServices,
            'totalAppointments' => $totalAppointments,
            'recentAppointments' => $recentAppointments,
            'pendingAppointments' => $pendingAppointments,
            'completedAppointments' => $completedAppointments,
        ];

        return view('admin.dashboard', $data);
    }
}
