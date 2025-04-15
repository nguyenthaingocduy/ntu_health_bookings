<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the staff dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated staff member
        $staff = Auth::user();
        
        // Get the staff's appointments
        $appointments = Appointment::with(['service', 'employee', 'timeAppointment'])
            ->where('customer_id', $staff->id)
            ->orderBy('date_appointments', 'desc')
            ->take(5)
            ->get();
        
        // Get the count of upcoming appointments
        $upcomingAppointmentsCount = Appointment::where('customer_id', $staff->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('date_appointments', '>=', now()->format('Y-m-d'))
            ->count();
        
        // Get the count of completed appointments
        $completedAppointmentsCount = Appointment::where('customer_id', $staff->id)
            ->where('status', 'completed')
            ->count();
        
        // Get available health check-up services
        $services = Service::where('status', 'active')
            ->take(5)
            ->get();
        
        return view('staff.dashboard', compact(
            'staff',
            'appointments',
            'upcomingAppointmentsCount',
            'completedAppointmentsCount',
            'services'
        ));
    }
}
