<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Traits\HasRoleCheck;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use HasRoleCheck;

    public function index()
    {
        if ($redirect = $this->checkRole(1)) {
            return $redirect;
        }

        $totalCustomers = Customer::count();
        $totalEmployees = Employee::count();
        $totalServices = Service::count();
        $totalAppointments = Appointment::count();
        
        $recentAppointments = Appointment::with(['customer', 'service', 'employee'])
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
