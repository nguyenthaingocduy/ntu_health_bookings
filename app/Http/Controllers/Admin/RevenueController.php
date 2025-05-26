<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        // Lấy khoảng thời gian từ request hoặc mặc định
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Tổng quan doanh thu
        $revenueOverview = $this->getRevenueOverview();

        // Doanh thu theo khoảng thời gian được chọn
        $periodRevenue = $this->getPeriodRevenue($startDate, $endDate);

        // Biểu đồ doanh thu 30 ngày gần đây
        $dailyRevenue = $this->getDailyRevenue(30);

        // Doanh thu theo dịch vụ
        $serviceRevenue = $this->getServiceRevenue($startDate, $endDate);

        // Doanh thu theo nhân viên
        $employeeRevenue = $this->getEmployeeRevenue($startDate, $endDate);

        // Biểu đồ doanh thu 12 tháng gần đây
        $monthlyRevenue = $this->getMonthlyRevenue(12);

        return view('admin.revenue.index', compact(
            'revenueOverview',
            'periodRevenue',
            'dailyRevenue',
            'serviceRevenue',
            'employeeRevenue',
            'monthlyRevenue',
            'startDate',
            'endDate'
        ));
    }

    private function getRevenueOverview()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        return [
            'today' => $this->getRevenueByPeriod($today, $today->copy()->endOfDay()),
            'this_week' => $this->getRevenueByPeriod($thisWeek, $thisWeek->copy()->endOfWeek()),
            'this_month' => $this->getRevenueByPeriod($thisMonth, $thisMonth->copy()->endOfMonth()),
            'this_year' => $this->getRevenueByPeriod($thisYear, $thisYear->copy()->endOfYear()),
        ];
    }

    private function getRevenueByPeriod($startDate, $endDate)
    {
        try {
            // Doanh thu từ appointments đã hoàn thành
            $appointmentRevenue = Appointment::where('status', 'completed')
                ->whereBetween('date_appointments', [$startDate, $endDate])
                ->whereNotNull('final_price')
                ->sum('final_price');

            // Doanh thu từ invoices đã thanh toán
            $invoiceRevenue = Invoice::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total');

            return $appointmentRevenue + $invoiceRevenue;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error calculating revenue: ' . $e->getMessage());
            return 0;
        }
    }

    private function getPeriodRevenue($startDate, $endDate)
    {
        $appointmentRevenue = Appointment::where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->sum('final_price');

        $invoiceRevenue = Invoice::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $appointmentCount = Appointment::where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->count();

        return [
            'total_revenue' => $appointmentRevenue + $invoiceRevenue,
            'appointment_revenue' => $appointmentRevenue,
            'invoice_revenue' => $invoiceRevenue,
            'appointment_count' => $appointmentCount,
            'average_per_appointment' => $appointmentCount > 0 ? ($appointmentRevenue / $appointmentCount) : 0
        ];
    }

    private function getDailyRevenue($days = 30)
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays($days - 1);

        $dailyData = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $revenue = $this->getRevenueByPeriod($dayStart, $dayEnd);

            $dailyData[] = [
                'date' => $date->format('Y-m-d'),
                'date_formatted' => $date->format('d/m'),
                'revenue' => $revenue
            ];
        }

        return $dailyData;
    }

    private function getServiceRevenue($startDate, $endDate)
    {
        return Appointment::with('service')
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->select('service_id', DB::raw('SUM(final_price) as total_revenue'), DB::raw('COUNT(*) as appointment_count'))
            ->groupBy('service_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    private function getEmployeeRevenue($startDate, $endDate)
    {
        return Appointment::with('employee')
            ->where('status', 'completed')
            ->whereNotNull('employee_id')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->select('employee_id', DB::raw('SUM(final_price) as total_revenue'), DB::raw('COUNT(*) as appointment_count'))
            ->groupBy('employee_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    private function getMonthlyRevenue($months = 12)
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = $endDate->copy()->subMonths($months - 1)->startOfMonth();

        $monthlyData = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $revenue = $this->getRevenueByPeriod($monthStart, $monthEnd);

            $monthlyData[] = [
                'month' => $date->format('Y-m'),
                'month_formatted' => $date->format('m/Y'),
                'revenue' => $revenue
            ];
        }

        return $monthlyData;
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Lấy dữ liệu chi tiết cho export
        $appointments = Appointment::with(['service', 'employee', 'customer'])
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->orderBy('date_appointments', 'desc')
            ->get();

        $invoices = Invoice::with(['user', 'appointment'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Tạo CSV export
        $filename = 'bao-cao-doanh-thu-' . $startDate->format('d-m-Y') . '-den-' . $endDate->format('d-m-Y') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($appointments, $invoices) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'Loại',
                'Ngày',
                'Khách hàng',
                'Dịch vụ/Mô tả',
                'Nhân viên',
                'Số tiền (VNĐ)'
            ]);

            // Appointments data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    'Lịch hẹn',
                    $appointment->date_appointments->format('d/m/Y'),
                    $appointment->customer ? $appointment->customer->first_name . ' ' . $appointment->customer->last_name : 'N/A',
                    $appointment->service ? $appointment->service->name : 'N/A',
                    $appointment->employee ? $appointment->employee->first_name . ' ' . $appointment->employee->last_name : 'N/A',
                    number_format($appointment->final_price, 0, ',', '.')
                ]);
            }

            // Invoices data
            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    'Hóa đơn',
                    $invoice->created_at->format('d/m/Y'),
                    $invoice->user ? $invoice->user->first_name . ' ' . $invoice->user->last_name : 'N/A',
                    'Hóa đơn #' . $invoice->invoice_number,
                    'N/A',
                    number_format($invoice->total, 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
