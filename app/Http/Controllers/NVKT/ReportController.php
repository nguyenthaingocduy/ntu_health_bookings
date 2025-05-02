<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Hiển thị báo cáo thống kê
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Số lượng khách hàng đã phục vụ
        $customersServed = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count('customer_id');

        // Thống kê dịch vụ đã thực hiện
        $serviceStats = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->select('service_id', DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->with('service')
            ->get();

        // Thống kê theo ngày
        $dailyStats = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->select(DB::raw('DATE(date_appointments) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Đánh giá kết quả dịch vụ
        $serviceRatings = [
            'excellent' => Appointment::where('employee_id', Auth::id())
                ->where('status', 'completed')
                ->whereBetween('date_appointments', [$startDate, $endDate])
                ->where('rating', 5)
                ->count(),
            'good' => Appointment::where('employee_id', Auth::id())
                ->where('status', 'completed')
                ->whereBetween('date_appointments', [$startDate, $endDate])
                ->whereBetween('rating', [4, 4.9])
                ->count(),
            'average' => Appointment::where('employee_id', Auth::id())
                ->where('status', 'completed')
                ->whereBetween('date_appointments', [$startDate, $endDate])
                ->whereBetween('rating', [3, 3.9])
                ->count(),
            'poor' => Appointment::where('employee_id', Auth::id())
                ->where('status', 'completed')
                ->whereBetween('date_appointments', [$startDate, $endDate])
                ->where('rating', '<', 3)
                ->count(),
        ];

        return view('nvkt.reports.index', compact(
            'startDate',
            'endDate',
            'customersServed',
            'serviceStats',
            'dailyStats',
            'serviceRatings'
        ));
    }

    /**
     * Hiển thị báo cáo số lượng khách hàng đã phục vụ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customers(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $customers = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->with('customer')
            ->select('customer_id', DB::raw('count(*) as total_visits'))
            ->groupBy('customer_id')
            ->orderByDesc('total_visits')
            ->paginate(10);

        return view('nvkt.reports.customers', compact('customers', 'startDate', 'endDate'));
    }

    /**
     * Hiển thị thống kê dịch vụ đã thực hiện
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function services(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $services = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->with('service')
            ->select('service_id', DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->paginate(10);

        return view('nvkt.reports.services', compact('services', 'startDate', 'endDate'));
    }

    /**
     * Hiển thị đánh giá kết quả dịch vụ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ratings(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Sửa lại để không sử dụng cột rating
        $ratings = Appointment::where('employee_id', Auth::id())
            ->where('status', 'completed')
            ->whereBetween('date_appointments', [$startDate, $endDate])
            ->with(['customer', 'service'])
            ->orderByDesc('date_appointments')
            ->paginate(10);

        // Tạo dữ liệu mẫu cho thống kê đánh giá
        $ratingStats = [
            'excellent' => 0, // Giả lập số lượng đánh giá 5 sao
            'good' => 0,      // Giả lập số lượng đánh giá 4 sao
            'average' => 0,   // Giả lập số lượng đánh giá 3 sao
            'poor' => 0,      // Giả lập số lượng đánh giá dưới 3 sao
        ];

        return view('nvkt.reports.ratings', compact('ratings', 'ratingStats', 'startDate', 'endDate'));
    }
}
