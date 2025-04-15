<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\AppointmentController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ServiceController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Time;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\MailTestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ và các trang công khai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'index2'])->name('services.index');
Route::get('/services/{id}', [HomeController::class, 'show'])->name('services.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/login-status', function() {
    return view('auth.login-status');
})->name('login.status');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route kiểm tra đăng nhập
Route::get('/check-auth', function() {
    if (Auth::check()) {
        return 'Đã đăng nhập với email: ' . Auth::user()->email .
            '<br>Role: ' . (Auth::user()->role ? Auth::user()->role->name : 'Không có vai trò') .
            '<br><a href="/logout" onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();">Đăng xuất</a>' .
            '<form id="logout-form" action="/logout" method="POST" style="display: none;">' . csrf_field() . '</form>';
    }
    return 'Chưa đăng nhập. <a href="/login">Đăng nhập ngay</a>';
})->name('check.auth');

// Khu vực khách hàng
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create/{service?}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/clinics/{clinicId}/services', [ServiceController::class, 'byClinic'])->name('services.by-clinic');
});

// Khu vực admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {

        Route::resource('services', AdminServiceController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('customers', CustomerController::class)->only(['index', 'show']);
        Route::resource('clinics', ClinicController::class);
        Route::resource('appointments', AdminAppointmentController::class);
    });
});

// Include admin routes from admin.php
require __DIR__.'/admin.php';

// Khu vực cán bộ viên chức (Staff)
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    Route::middleware([\App\Http\Middleware\StaffMiddleware::class])->group(function () {

        // Quản lý lịch hẹn khám sức khỏe
        Route::get('/appointments', [\App\Http\Controllers\Staff\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create/{serviceId?}', [\App\Http\Controllers\Staff\AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [\App\Http\Controllers\Staff\AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{id}', [\App\Http\Controllers\Staff\AppointmentController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{id}/cancel', [\App\Http\Controllers\Staff\AppointmentController::class, 'cancel'])->name('appointments.cancel');

        // Quản lý thông tin cá nhân
        Route::get('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [\App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [\App\Http\Controllers\Staff\ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::put('/profile/password', [\App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });
});

// Include staff routes from staff.php
require __DIR__.'/staff.php';

// Route test chuyển hướng dashboard
Route::get('/test-dashboard', function() {
    if (Auth::check()) {
        $user = Auth::user();
        Log::info('Test dashboard route', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_authenticated' => Auth::check(),
            'role' => $user->role ? $user->role->name : 'No Role'
        ]);

        if ($user->role && strtolower($user->role->name) === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/customer/dashboard');
    }
    return redirect('/login');
})->name('test.dashboard');

// Thêm route mới không dùng middleware để kiểm tra
Route::get('/direct-dashboard', function() {
    // Lấy thông tin người dùng hiện tại
    $user = Auth::user();

    // Nếu chưa đăng nhập, hiển thị thông báo
    if (!$user) {
        return 'Bạn chưa đăng nhập. <a href="/login">Đăng nhập</a> hoặc <a href="/test-login">Đăng nhập test</a>';
    }

    // Hiển thị thông tin người dùng và link đến dashboard
    $html = '
    <h2>Thông tin người dùng</h2>
    <p>ID: '.$user->id.'</p>
    <p>Email: '.$user->email.'</p>
    <p>Họ tên: '.$user->first_name.' '.$user->last_name.'</p>
    <p>Role: '.($user->role ? $user->role->name : 'Không có').'</p>
    <hr>
    <h3>Liên kết</h3>
    <a href="/customer/dashboard">Đến Customer Dashboard</a><br>
    <a href="/admin/dashboard">Đến Admin Dashboard</a><br>
    <form method="POST" action="/logout">
        <input type="hidden" name="_token" value="'.csrf_token().'">
        <button type="submit">Đăng xuất</button>
    </form>
    ';

    return $html;
})->name('direct.dashboard');

// Route mặc định sau khi đăng nhập
Route::get('/home', [HomeController::class, 'index'])->name('dashboard.home');

// API routes for time slots and services
Route::prefix('api')->group(function () {
    Route::get('/available-time-slots', [\App\Http\Controllers\Api\TimeSlotController::class, 'getAvailableTimeSlots']);
    Route::get('/services/{id}', [\App\Http\Controllers\Api\ServiceController::class, 'show']);
});

// Legacy API route for backward compatibility
Route::get('/api/check-available-slots', function (Request $request) {
    try {
        Log::info('API được gọi: /check-available-slots (web route)', [
            'service_id' => $request->service_id,
            'date' => $request->date
        ]);

        // Validate request
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        // Kiểm tra xem ngày đã chọn có phải ngày hôm nay không
        $isToday = date('Y-m-d', strtotime('now +7 hours')) === $request->date;
        $currentTime = now()->setTimezone('Asia/Ho_Chi_Minh');

        Log::info('Kiểm tra thời gian', [
            'isToday' => $isToday,
            'currentTime' => $currentTime->format('H:i:s'),
            'date_requested' => $request->date,
            'today_date' => date('Y-m-d', strtotime('now +7 hours')),
            'timezone' => config('app.timezone'),
            'timezone_vietnam' => 'Asia/Ho_Chi_Minh',
            'local_time' => now()->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s')
        ]);

        // Lấy tất cả các khung giờ
        $allTimeSlots = Time::orderBy('started_time')->get();

        // Nếu không có khung giờ nào, trả về thông báo lỗi
        if ($allTimeSlots->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khung giờ nào trong hệ thống',
                'available_slots' => []
            ]);
        }

        // Lấy các khung giờ đã được đặt trong ngày đã chọn
        $bookedTimeSlots = Appointment::where('date_appointments', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('time_appointments_id')
            ->toArray();

        // Lọc ra các khung giờ còn trống và nếu là ngày hôm nay, chỉ lấy các giờ trong tương lai
        $availableSlots = $allTimeSlots->filter(function($timeSlot) use ($bookedTimeSlots, $isToday, $currentTime) {
            // Kiểm tra nếu khung giờ đã bị đặt
            if (in_array($timeSlot->id, $bookedTimeSlots)) {
                Log::info("Khung giờ {$timeSlot->id} đã được đặt", ['time' => $timeSlot->started_time]);
                return false;
            }

            // Nếu là ngày hôm nay, kiểm tra xem khung giờ có nằm trong tương lai không
            if ($isToday) {
                // Lấy giờ và phút từ started_time
                $slotTime = \Carbon\Carbon::parse($timeSlot->started_time);
                $slotHour = $slotTime->hour;
                $slotMinute = $slotTime->minute;

                // Lấy giờ và phút hiện tại
                $currentHour = $currentTime->hour;
                $currentMinute = $currentTime->minute;

                Log::info("So sánh khung giờ {$timeSlot->id}", [
                    'slot_time' => $slotTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'slot_hour' => $slotHour,
                    'current_hour' => $currentHour,
                    'slot_minute' => $slotMinute,
                    'current_minute' => $currentMinute,
                    'is_future' => ($slotHour > $currentHour || ($slotHour == $currentHour && $slotMinute > $currentMinute)) ? 'true' : 'false'
                ]);

                // So sánh theo giờ và phút
                if ($slotHour > $currentHour) {
                    return true; // Giờ sau hiện tại
                } elseif ($slotHour == $currentHour && $slotMinute > $currentMinute) {
                    return true; // Cùng giờ nhưng phút sau hiện tại
                } else {
                    return false; // Thời gian đã qua
                }
            }

            return true;
        })->map(function($timeSlot) {
            return [
                'id' => $timeSlot->id,
                'time' => \Carbon\Carbon::parse($timeSlot->started_time)->format('H:i')
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách khung giờ khả dụng thành công',
            'available_slots' => $availableSlots,
            'total_slots' => $availableSlots->count(),
            'date_requested' => $request->date,
            'debug_info' => [
                'is_today' => $isToday,
                'current_time' => $currentTime->format('H:i:s'),
                'timezone' => config('app.timezone'),
                'timezone_vietnam' => 'Asia/Ho_Chi_Minh',
                'local_time' => $currentTime->format('Y-m-d H:i:s')
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Lỗi khi lấy khung giờ khả dụng: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
            'available_slots' => []
        ], 500);
    }
});

// Debug route để kiểm tra roles
Route::get('check-roles', function() {
    dd(App\Models\Role::all()->toArray());
});

// Routes kiểm tra email
Route::get('/mail-test', [MailTestController::class, 'index'])->name('mail.test');
Route::post('/mail-test', [MailTestController::class, 'sendTestMail'])->name('mail.test.send');
