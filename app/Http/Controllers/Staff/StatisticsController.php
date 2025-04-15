<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display the statistics dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the selected period
        $period = $request->input('period', 'this_month');
        
        // Define date ranges based on the selected period
        $dateRange = $this->getDateRange($period);
        $previousDateRange = $this->getPreviousDateRange($period);
        
        // Get statistics for the current period
        $stats = $this->getStats($dateRange['start'], $dateRange['end']);
        
        // Get statistics for the previous period for comparison
        $previousStats = $this->getStats($previousDateRange['start'], $previousDateRange['end']);
        
        // Calculate percentage changes
        $stats['appointment_change'] = $this->calculatePercentageChange(
            $previousStats['total_appointments'], 
            $stats['total_appointments']
        );
        
        $stats['customer_change'] = $this->calculatePercentageChange(
            $previousStats['total_customers'], 
            $stats['total_customers']
        );
        
        $stats['revenue_change'] = $this->calculatePercentageChange(
            $previousStats['total_revenue'], 
            $stats['total_revenue']
        );
        
        $stats['avg_change'] = $this->calculatePercentageChange(
            $previousStats['avg_revenue'], 
            $stats['avg_revenue']
        );
        
        // Get data for revenue chart
        $revenueChart = $this->getRevenueChartData($period, $dateRange);
        
        // Get data for status chart
        $statusChart = $this->getStatusChartData($dateRange['start'], $dateRange['end']);
        
        // Get top services
        $topServices = $this->getTopServices($dateRange['start'], $dateRange['end']);
        
        // Get top customers
        $topCustomers = $this->getTopCustomers($dateRange['start'], $dateRange['end']);
        
        // Format period name for display
        $periodName = $this->formatPeriodName($period);
        
        return view('staff.statistics', compact(
            'stats', 
            'period', 
            'periodName', 
            'revenueChart', 
            'statusChart', 
            'topServices', 
            'topCustomers'
        ));
    }
    
    /**
     * Get date range for the selected period.
     *
     * @param  string  $period
     * @return array
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                ];
            case 'yesterday':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay(),
                ];
            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                ];
            case 'last_week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek(),
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
            case 'last_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth(),
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear(),
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
        }
    }
    
    /**
     * Get date range for the previous period.
     *
     * @param  string  $period
     * @return array
     */
    private function getPreviousDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay(),
                ];
            case 'yesterday':
                return [
                    'start' => $now->copy()->subDays(2)->startOfDay(),
                    'end' => $now->copy()->subDays(2)->endOfDay(),
                ];
            case 'this_week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek(),
                ];
            case 'last_week':
                return [
                    'start' => $now->copy()->subWeeks(2)->startOfWeek(),
                    'end' => $now->copy()->subWeeks(2)->endOfWeek(),
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth(),
                ];
            case 'last_month':
                return [
                    'start' => $now->copy()->subMonths(2)->startOfMonth(),
                    'end' => $now->copy()->subMonths(2)->endOfMonth(),
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear(),
                ];
            default:
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth(),
                ];
        }
    }
    
    /**
     * Get statistics for the given date range.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getStats($startDate, $endDate)
    {
        // Get appointments for the staff member in the date range
        $appointments = Appointment::where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->get();
            
        // Calculate total appointments
        $totalAppointments = $appointments->count();
        
        // Calculate total unique customers
        $totalCustomers = $appointments->pluck('customer_id')->unique()->count();
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($appointments as $appointment) {
            if ($appointment->service) {
                $totalRevenue += $appointment->service->price;
            }
        }
        
        // Calculate average revenue per appointment
        $avgRevenue = $totalAppointments > 0 ? $totalRevenue / $totalAppointments : 0;
        
        return [
            'total_appointments' => $totalAppointments,
            'total_customers' => $totalCustomers,
            'total_revenue' => $totalRevenue,
            'avg_revenue' => $avgRevenue,
        ];
    }
    
    /**
     * Calculate percentage change between two values.
     *
     * @param  float  $oldValue
     * @param  float  $newValue
     * @return float
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }
    
    /**
     * Get data for the revenue chart.
     *
     * @param  string  $period
     * @param  array  $dateRange
     * @return array
     */
    private function getRevenueChartData($period, $dateRange)
    {
        $labels = [];
        $data = [];
        
        switch ($period) {
            case 'today':
            case 'yesterday':
                // Hourly data for a single day
                $start = Carbon::parse($dateRange['start']);
                $end = Carbon::parse($dateRange['end']);
                
                for ($hour = 8; $hour <= 20; $hour++) {
                    $hourStart = $start->copy()->setHour($hour)->setMinute(0)->setSecond(0);
                    $hourEnd = $start->copy()->setHour($hour)->setMinute(59)->setSecond(59);
                    
                    $labels[] = $hour . ':00';
                    
                    $revenue = Appointment::where('employee_id', Auth::id())
                        ->whereBetween('date_appointments', [$hourStart, $hourEnd])
                        ->whereIn('status', ['completed', 'confirmed'])
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                        
                    $data[] = $revenue;
                }
                break;
                
            case 'this_week':
            case 'last_week':
                // Daily data for a week
                $start = Carbon::parse($dateRange['start']);
                
                for ($day = 0; $day < 7; $day++) {
                    $dayDate = $start->copy()->addDays($day);
                    $labels[] = $dayDate->format('D');
                    
                    $revenue = Appointment::where('employee_id', Auth::id())
                        ->whereDate('date_appointments', $dayDate)
                        ->whereIn('status', ['completed', 'confirmed'])
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                        
                    $data[] = $revenue;
                }
                break;
                
            case 'this_month':
            case 'last_month':
                // Weekly data for a month
                $start = Carbon::parse($dateRange['start']);
                $end = Carbon::parse($dateRange['end']);
                $weeks = ceil($start->diffInDays($end) / 7);
                
                for ($week = 0; $week < $weeks; $week++) {
                    $weekStart = $start->copy()->addDays($week * 7);
                    $weekEnd = $weekStart->copy()->addDays(6)->min($end);
                    
                    $labels[] = 'Tuần ' . ($week + 1);
                    
                    $revenue = Appointment::where('employee_id', Auth::id())
                        ->whereBetween('date_appointments', [$weekStart, $weekEnd])
                        ->whereIn('status', ['completed', 'confirmed'])
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                        
                    $data[] = $revenue;
                }
                break;
                
            case 'this_year':
                // Monthly data for a year
                $start = Carbon::parse($dateRange['start']);
                
                for ($month = 0; $month < 12; $month++) {
                    $monthDate = $start->copy()->addMonths($month);
                    $labels[] = $monthDate->format('M');
                    
                    $revenue = Appointment::where('employee_id', Auth::id())
                        ->whereYear('date_appointments', $monthDate->year)
                        ->whereMonth('date_appointments', $monthDate->month)
                        ->whereIn('status', ['completed', 'confirmed'])
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                        
                    $data[] = $revenue;
                }
                break;
                
            default:
                // Daily data for this month
                $start = Carbon::parse($dateRange['start']);
                $daysInMonth = $start->daysInMonth;
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dayDate = $start->copy()->setDay($day);
                    $labels[] = $day;
                    
                    $revenue = Appointment::where('employee_id', Auth::id())
                        ->whereDate('date_appointments', $dayDate)
                        ->whereIn('status', ['completed', 'confirmed'])
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price');
                        
                    $data[] = $revenue;
                }
                break;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * Get data for the status chart.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getStatusChartData($startDate, $endDate)
    {
        $pending = Appointment::where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();
            
        $confirmed = Appointment::where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->where('status', 'confirmed')
            ->count();
            
        $completed = Appointment::where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();
            
        $cancelled = Appointment::where('employee_id', Auth::id())
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->where('status', 'cancelled')
            ->count();
            
        return [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'completed' => $completed,
            'cancelled' => $cancelled,
        ];
    }
    
    /**
     * Get top services by revenue and appointment count.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Support\Collection
     */
    private function getTopServices($startDate, $endDate)
    {
        return Service::select('services.id', 'services.name', 'services.price')
            ->selectRaw('COUNT(appointments.id) as count')
            ->selectRaw('SUM(services.price) as total_revenue')
            ->join('appointments', 'services.id', '=', 'appointments.service_id')
            ->where('appointments.employee_id', Auth::id())
            ->whereBetween('appointments.date_appointments', [$startDate, $endDate])
            ->whereIn('appointments.status', ['completed', 'confirmed'])
            ->groupBy('services.id', 'services.name', 'services.price')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }
    
    /**
     * Get top customers by appointment count and spending.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Support\Collection
     */
    private function getTopCustomers($startDate, $endDate)
    {
        return User::select(
                'users.id', 
                'users.first_name', 
                'users.last_name', 
                'users.phone'
            )
            ->selectRaw('COUNT(appointments.id) as appointment_count')
            ->selectRaw('SUM(services.price) as total_spent')
            ->selectRaw('MAX(appointments.date_appointments) as last_appointment')
            ->join('appointments', 'users.id', '=', 'appointments.customer_id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.employee_id', Auth::id())
            ->whereBetween('appointments.date_appointments', [$startDate, $endDate])
            ->whereIn('appointments.status', ['completed', 'confirmed'])
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.phone')
            ->orderByDesc('appointment_count')
            ->limit(5)
            ->get();
    }
    
    /**
     * Format the period name for display.
     *
     * @param  string  $period
     * @return string
     */
    private function formatPeriodName($period)
    {
        switch ($period) {
            case 'today':
                return 'Hôm nay';
            case 'yesterday':
                return 'Hôm qua';
            case 'this_week':
                return 'Tuần này';
            case 'last_week':
                return 'Tuần trước';
            case 'this_month':
                return 'Tháng này';
            case 'last_month':
                return 'Tháng trước';
            case 'this_year':
                return 'Năm nay';
            default:
                return 'Tháng này';
        }
    }
}
