<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Traits\HasRoleCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use HasRoleCheck;

    public function index()
    {
        // Thêm log để kiểm tra user khi truy cập dashboard
        $user = Auth::user();
        Log::info('Customer Dashboard: Accessing dashboard', [
            'user_id' => $user ? $user->id : 'Not logged in',
            'email' => $user ? $user->email : 'Not logged in',
            'role' => $user && $user->role ? $user->role->name : 'No Role'
        ]);

        if (!Auth::check()) {
            Log::info('Customer Dashboard: User not authenticated, redirecting to login');
            return redirect('/login');
        }

        // Bỏ qua kiểm tra role tạm thời để debug
        /*
        if ($redirect = $this->checkRole('Customer')) {
            return $redirect;
        }
        */

        try {
            // Lấy lịch hẹn sắp tới
            $upcomingAppointments = Appointment::with(['service', 'employee'])
                ->where('customer_id', Auth::id())
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereDate('date_appointments', '>=', now())
                ->orderBy('date_appointments')
                ->limit(5)
                ->get();
                
            // Lấy lịch hẹn đã qua
            $pastAppointments = Appointment::with(['service', 'employee'])
                ->where('customer_id', Auth::id())
                ->whereIn('status', ['completed', 'cancelled'])
                ->orderBy('date_appointments', 'desc')
                ->limit(5)
                ->get();
            
            // Thống kê số lượng lịch hẹn, dịch vụ đã sử dụng
            $appointmentsCount = Appointment::where('customer_id', Auth::id())->count();
            $servicesUsedCount = Appointment::where('customer_id', Auth::id())
                ->where('status', 'completed')
                ->count();
            $points = 0; // Chưa có tính năng tích điểm
                
            return view('customer.dashboard', compact(
                'upcomingAppointments', 
                'pastAppointments',
                'appointmentsCount',
                'servicesUsedCount',
                'points'
            ));
        } catch (\Exception $e) {
            Log::error('Customer Dashboard Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Trả về view với thông báo lỗi
            return view('customer.dashboard', [
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'upcomingAppointments' => [],
                'pastAppointments' => [],
                'appointmentsCount' => 0,
                'servicesUsedCount' => 0,
                'points' => 0
            ]);
        }
    }
}
