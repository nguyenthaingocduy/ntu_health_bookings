<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
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

        // Get the staff's appointments (as a customer)
        $appointments = Appointment::with(['service', 'timeAppointment', 'customer'])
            ->where('customer_id', $staff->id)
            ->orderBy('date_appointments', 'desc')
            ->take(5)
            ->get();

        // Get the count of upcoming appointments (as a customer)
        $upcomingAppointmentsCount = Appointment::where('customer_id', $staff->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('date_appointments', '>=', now()->format('Y-m-d'))
            ->count();

        // Get the count of completed appointments (as a customer)
        $completedAppointmentsCount = Appointment::where('customer_id', $staff->id)
            ->where('status', 'completed')
            ->count();

        // Get available services
        $services = Service::where('status', 'active')
            ->take(5)
            ->get();

        // For staff members who provide services
        // Get today's appointments assigned to this staff
        $todayAppointmentsCount = Appointment::where('employee_id', $staff->id)
            ->whereDate('date_appointments', Carbon::today())
            ->count();

        // Get monthly revenue for this staff
        $monthlyRevenue = Appointment::where('employee_id', $staff->id)
            ->whereMonth('date_appointments', Carbon::now()->month)
            ->whereYear('date_appointments', Carbon::now()->year)
            ->whereIn('appointments.status', ['completed', 'confirmed'])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        // Get recent appointments assigned to this staff
        $recentAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', $staff->id)
            ->orderBy('date_appointments', 'desc')
            ->take(5)
            ->get();

        // Get today's appointments for the staff
        $todayAppointments = Appointment::with(['customer', 'service', 'timeAppointment'])
            ->where('employee_id', $staff->id)
            ->whereDate('date_appointments', Carbon::today())
            ->orderBy('date_appointments')
            ->get();

        // Get popular services
        $popularServices = Service::select('services.id', 'services.name', 'services.price', 'services.description', 'services.image_url')
            ->selectRaw('COUNT(appointments.id) as appointments_count')
            ->leftJoin('appointments', 'services.id', '=', 'appointments.service_id')
            ->where('appointments.employee_id', $staff->id)
            ->whereIn('appointments.status', ['completed', 'confirmed'])
            ->groupBy('services.id', 'services.name', 'services.price', 'services.description', 'services.image_url')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        $maxAppointments = $popularServices->max('appointments_count') ?: 1;

        // Get revenue data for chart
        $revenueData = $this->getRevenueChartData();

        return view('staff.dashboard', compact(
            'staff',
            'appointments',
            'upcomingAppointmentsCount',
            'completedAppointmentsCount',
            'services',
            'todayAppointmentsCount',
            'monthlyRevenue',
            'recentAppointments',
            'todayAppointments',
            'popularServices',
            'maxAppointments',
            'revenueData'
        ));
    }

    /**
     * Get revenue data for the chart.
     *
     * @return array
     */
    private function getRevenueChartData()
    {
        $labels = [];
        $data = [];

        // Get data for the last 6 months
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $currentDate = $startDate->copy()->addMonths($i);
            $labels[] = $currentDate->format('M Y');

            $revenue = Appointment::where('employee_id', Auth::id())
                ->whereYear('date_appointments', $currentDate->year)
                ->whereMonth('date_appointments', $currentDate->month)
                ->whereIn('appointments.status', ['completed', 'confirmed'])
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');

            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
