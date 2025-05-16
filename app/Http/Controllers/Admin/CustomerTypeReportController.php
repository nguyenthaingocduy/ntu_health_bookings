<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerTypeReportController extends Controller
{
    /**
     * Hiển thị báo cáo phân bố khách hàng theo loại
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy tất cả các loại khách hàng
        $customerTypes = CustomerType::withCount('users')
            ->orderBy('priority_level', 'desc')
            ->get();
        
        // Tính tổng số khách hàng
        $totalCustomers = User::whereHas('role', function($query) {
                $query->where('name', 'Customer');
            })->count();
        
        // Tính phần trăm cho mỗi loại khách hàng
        foreach ($customerTypes as $type) {
            $type->percentage = $totalCustomers > 0 ? round(($type->users_count / $totalCustomers) * 100, 2) : 0;
        }
        
        // Lấy dữ liệu chi tiêu trung bình theo loại khách hàng
        $averageSpending = DB::table('users')
            ->join('appointments', 'users.id', '=', 'appointments.customer_id')
            ->where('appointments.status', 'completed')
            ->select('users.type_id', DB::raw('AVG(appointments.final_price) as average_spending'))
            ->groupBy('users.type_id')
            ->get()
            ->keyBy('type_id');
        
        // Lấy dữ liệu số lượng đặt lịch trung bình theo loại khách hàng
        $averageAppointments = DB::table('users')
            ->join('appointments', 'users.id', '=', 'appointments.customer_id')
            ->where('appointments.status', 'completed')
            ->select('users.type_id', DB::raw('COUNT(appointments.id) as appointment_count'), DB::raw('COUNT(DISTINCT users.id) as user_count'))
            ->groupBy('users.type_id')
            ->get()
            ->keyBy('type_id');
        
        // Tính toán số lượng đặt lịch trung bình cho mỗi loại khách hàng
        foreach ($customerTypes as $type) {
            $type->average_spending = isset($averageSpending[$type->id]) ? $averageSpending[$type->id]->average_spending : 0;
            
            if (isset($averageAppointments[$type->id]) && $averageAppointments[$type->id]->user_count > 0) {
                $type->average_appointments = $averageAppointments[$type->id]->appointment_count / $averageAppointments[$type->id]->user_count;
            } else {
                $type->average_appointments = 0;
            }
        }
        
        // Lấy dữ liệu tăng trưởng khách hàng theo loại trong 6 tháng gần nhất
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $monthlyGrowth = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'Customer')
            ->where('users.created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('YEAR(users.created_at) as year'),
                DB::raw('MONTH(users.created_at) as month'),
                'users.type_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month', 'users.type_id')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Tạo mảng dữ liệu cho biểu đồ
        $months = [];
        $chartData = [];
        
        // Khởi tạo dữ liệu cho từng loại khách hàng
        foreach ($customerTypes as $type) {
            $chartData[$type->id] = [
                'label' => $type->type_name,
                'color' => $type->color_code,
                'data' => []
            ];
        }
        
        // Tạo danh sách các tháng từ 6 tháng trước đến hiện tại
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths(5 - $i)->startOfMonth();
            $monthKey = $date->format('Y-m');
            $months[$monthKey] = $date->format('m/Y');
            
            // Khởi tạo giá trị 0 cho tất cả các loại khách hàng trong tháng này
            foreach ($customerTypes as $type) {
                $chartData[$type->id]['data'][$monthKey] = 0;
            }
        }
        
        // Điền dữ liệu vào biểu đồ
        foreach ($monthlyGrowth as $growth) {
            $monthKey = $growth->year . '-' . str_pad($growth->month, 2, '0', STR_PAD_LEFT);
            if (isset($months[$monthKey]) && isset($chartData[$growth->type_id])) {
                $chartData[$growth->type_id]['data'][$monthKey] = $growth->count;
            }
        }
        
        return view('admin.reports.customer_types', compact('customerTypes', 'totalCustomers', 'chartData', 'months'));
    }
}
