<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $appointments = Appointment::with(['service', 'doctor', 'timeSlot'])
            ->where('user_id', $staff->id)
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        // Get the count of upcoming appointments
        $upcomingAppointmentsCount = Appointment::where('user_id', $staff->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->count();

        // Get the count of completed appointments
        $completedAppointmentsCount = Appointment::where('user_id', $staff->id)
            ->where('status', 'completed')
            ->count();

        // Get available health check-up services
        $healthCheckupServices = Service::where('status', 'active')
            ->where('is_health_checkup', true)
            ->get();

        // Get regular services
        $services = Service::where('status', 'active')
            ->where('is_health_checkup', false)
            ->take(5)
            ->get();

        // Get the latest health record
        $latestHealthRecord = HealthRecord::where('user_id', $staff->id)
            ->orderBy('check_date', 'desc')
            ->first();

        // Calculate days since last health check
        $daysSinceLastCheck = null;
        $nextCheckDue = false;

        if ($staff->last_health_check) {
            $daysSinceLastCheck = Carbon::parse($staff->last_health_check)->diffInDays(now());
            $nextCheckDue = $daysSinceLastCheck > 365; // More than a year since last check
        } else {
            $nextCheckDue = true; // No record of health check
        }

        return view('staff.dashboard', compact(
            'staff',
            'appointments',
            'upcomingAppointmentsCount',
            'completedAppointmentsCount',
            'services',
            'healthCheckupServices',
            'latestHealthRecord',
            'daysSinceLastCheck',
            'nextCheckDue'
        ));
    }
}
