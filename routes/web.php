<?php

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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ và các trang công khai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [App\Http\Controllers\Customer\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [App\Http\Controllers\Customer\ServiceController::class, 'show'])->name('services.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
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



// Khu vực khách hàng
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/notification-settings', [ProfileController::class, 'notificationSettings'])->name('profile.notification-settings');
    Route::put('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.update-notifications');

    // Loại khách hàng
    Route::get('/customer-types', [\App\Http\Controllers\Customer\CustomerTypeController::class, 'index'])->name('customer-types.index');
    Route::get('/customer-types/{customerType}', [\App\Http\Controllers\Customer\CustomerTypeController::class, 'show'])->name('customer-types.show');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create/{service?}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::get('/clinics/{clinicId}/services', [ServiceController::class, 'byClinic'])->name('services.by-clinic');

    // Hóa đơn
    Route::get('/invoices', [\App\Http\Controllers\Customer\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [\App\Http\Controllers\Customer\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/download', [\App\Http\Controllers\Customer\InvoiceController::class, 'download'])->name('invoices.download');
});

// Khu vực admin - Moved to admin.php
// See routes/admin.php for all admin routes

// Include admin routes from admin.php
require __DIR__.'/admin.php';

// Include le-tan routes from le-tan.php
require __DIR__.'/le-tan.php';

// Include nvkt routes from nvkt.php
require __DIR__.'/nvkt.php';

// Redirect từ /staff sang /le-tan/dashboard
Route::redirect('/staff', '/le-tan/dashboard');



// Route mặc định sau khi đăng nhập
Route::get('/home', [HomeController::class, 'index'])->name('dashboard.home');

// Route chuyển hướng đến trang chủ
Route::get('/dashboard', function() {
    return redirect('/');
})->name('dashboard');

// Test permission debug
Route::get('/test-permissions', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    $user = auth()->user();

    $html = "<h1>Permission Debug for User: {$user->full_name}</h1>";
    $html .= "<p><strong>Role:</strong> " . ($user->role ? $user->role->name : 'No role') . "</p>";

    // Check role permissions
    if ($user->role) {
        $rolePermissions = $user->role->permissions()->get();
        $html .= "<h2>Role Permissions ({$rolePermissions->count()}):</h2><ul>";
        foreach ($rolePermissions as $perm) {
            $html .= "<li>{$perm->name} - {$perm->display_name}</li>";
        }
        $html .= "</ul>";
    }

    // Check user permissions
    $userPermissions = $user->userPermissions()->with('permission')->get();
    $html .= "<h2>Direct User Permissions ({$userPermissions->count()}):</h2><ul>";
    foreach ($userPermissions as $perm) {
        $actions = [];
        if ($perm->can_view) $actions[] = 'view';
        if ($perm->can_create) $actions[] = 'create';
        if ($perm->can_edit) $actions[] = 'edit';
        if ($perm->can_delete) $actions[] = 'delete';

        $html .= "<li>{$perm->permission->name} - Actions: " . implode(', ', $actions) . "</li>";
    }
    $html .= "</ul>";

    // Test specific permissions
    $testPermissions = ['services.view', 'services.create', 'promotions.view', 'promotions.create'];
    $html .= "<h2>Permission Tests:</h2><ul>";
    foreach ($testPermissions as $testPerm) {
        $hasRole = $user->hasPermissionThroughRole($testPerm);
        $hasDirect = $user->hasDirectPermission(str_replace('.view', '', str_replace('.create', '', $testPerm)), str_contains($testPerm, '.view') ? 'view' : 'create');
        $hasAny = $user->hasAnyPermission(str_replace('.view', '', str_replace('.create', '', $testPerm)), str_contains($testPerm, '.view') ? 'view' : 'create');

        $html .= "<li><strong>{$testPerm}:</strong> Role: " . ($hasRole ? 'YES' : 'NO') . ", Direct: " . ($hasDirect ? 'YES' : 'NO') . ", Any: " . ($hasAny ? 'YES' : 'NO') . "</li>";
    }
    $html .= "</ul>";

    return $html;
});

// Clear permission cache
Route::get('/clear-permission-cache', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    $user = auth()->user();
    $user->clearPermissionCache();

    return 'Permission cache cleared for user: ' . $user->full_name . '<br><a href="/test-permissions">Test permissions again</a>';
});

// Test sidebar permissions
Route::get('/test-sidebar', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    $user = auth()->user();

    $html = "<h1>Sidebar Permission Test for: {$user->full_name}</h1>";

    // Test specific sidebar permissions
    $sidebarTests = [
        ['promotions', 'view', 'Quản lý khuyến mãi - Xem'],
        ['promotions', 'create', 'Quản lý khuyến mãi - Thêm'],
        ['services', 'view', 'Quản lý dịch vụ - Xem'],
        ['services', 'create', 'Quản lý dịch vụ - Thêm'],
    ];

    $html .= "<h2>Sidebar Permission Tests:</h2><ul>";
    foreach ($sidebarTests as [$resource, $action, $description]) {
        $hasPermission = $user->hasAnyPermission($resource, $action);
        $color = $hasPermission ? 'green' : 'red';
        $status = $hasPermission ? 'YES' : 'NO';

        $html .= "<li style='color: {$color}'><strong>{$description}:</strong> {$status}</li>";
    }
    $html .= "</ul>";

    $html .= "<br><a href='/le-tan/dashboard'>Go to Receptionist Dashboard</a>";

    return $html;
});

// Test permission UI
Route::get('/test-permission-ui', function() {
    $permissions = [
        (object)['name' => 'appointments.view', 'display_name' => 'Xem lịch hẹn'],
        (object)['name' => 'appointments.create', 'display_name' => 'Thêm lịch hẹn'],
        (object)['name' => 'appointments.edit', 'display_name' => 'Sửa lịch hẹn'],
        (object)['name' => 'appointments.delete', 'display_name' => 'Xóa lịch hẹn'],
        (object)['name' => 'appointments.cancel', 'display_name' => 'Hủy lịch hẹn'],
        (object)['name' => 'services.view', 'display_name' => 'Xem dịch vụ'],
        (object)['name' => 'services.create', 'display_name' => 'Thêm dịch vụ'],
    ];

    $html = "<h1>Permission UI Test</h1>";
    $html .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    $html .= "<tr><th>TÊN QUYỀN</th><th>NHÓM</th><th>XEM</th><th>THÊM</th><th>SỬA</th><th>XÓA</th></tr>";

    foreach ($permissions as $permission) {
        $permissionAction = null;
        if (str_contains($permission->name, '.')) {
            $parts = explode('.', $permission->name);
            $permissionAction = end($parts);
        }

        $html .= "<tr>";
        $html .= "<td>{$permission->display_name}<br><small>{$permission->name}</small></td>";
        $html .= "<td>" . explode('.', $permission->name)[0] . "</td>";

        // XEM
        $html .= "<td style='text-align: center;'>";
        if ($permissionAction === 'view' || !$permissionAction) {
            $html .= "☑️";
        } else {
            $html .= "-";
        }
        $html .= "</td>";

        // THÊM
        $html .= "<td style='text-align: center;'>";
        if ($permissionAction === 'create' || !$permissionAction) {
            $html .= "☑️";
        } else {
            $html .= "-";
        }
        $html .= "</td>";

        // SỬA
        $html .= "<td style='text-align: center;'>";
        if ($permissionAction === 'edit' || !$permissionAction) {
            $html .= "☑️";
        } else {
            $html .= "-";
        }
        $html .= "</td>";

        // XÓA
        $html .= "<td style='text-align: center;'>";
        if ($permissionAction === 'delete' || $permissionAction === 'cancel' || !$permissionAction) {
            $html .= "☑️";
        } else {
            $html .= "-";
        }
        $html .= "</td>";

        $html .= "</tr>";
    }

    $html .= "</table>";

    return $html;
});

// API routes for time slots and services
Route::prefix('api')->group(function () {
    Route::get('/available-time-slots', [\App\Http\Controllers\Api\TimeSlotController::class, 'getAvailableTimeSlots']);
    Route::get('/time-slots/{id}', [\App\Http\Controllers\Api\TimeSlotController::class, 'getTimeSlot']);
    Route::get('/services/{id}', [\App\Http\Controllers\Api\ServiceController::class, 'show']);
});

// Legacy API route for backward compatibility
Route::get('/api/check-available-slots', [\App\Http\Controllers\Api\TimeSlotController::class, 'checkAvailableSlots']);

// Test route for appointment data
Route::get('/test-appointment-data/{id}', function ($id) {
    $appointment = \App\Models\Appointment::with(['customer', 'service'])->findOrFail($id);

    return response()->json([
        'id' => $appointment->id,
        'promotion_code' => $appointment->promotion_code,
        'final_price' => $appointment->final_price,
        'discount_amount' => $appointment->discount_amount,
        'direct_discount_percent' => $appointment->direct_discount_percent,
        'service_price' => $appointment->service->price ?? null,
        'customer_name' => $appointment->customer->first_name . ' ' . $appointment->customer->last_name,
        'created_at' => $appointment->created_at,
    ]);
});

// Debug le-tan promotions
Route::get('/debug-le-tan-promotions', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    try {
        $user = auth()->user();

        $html = "<h1>Debug Le-tan Promotions</h1>";
        $html .= "<p><strong>User:</strong> {$user->full_name} ({$user->email})</p>";
        $html .= "<p><strong>Role:</strong> " . ($user->role ? $user->role->name : 'No role') . "</p>";

        // Test permission
        $hasViewPermission = $user->hasAnyPermission('promotions', 'view');
        $html .= "<p><strong>Has promotions.view permission:</strong> " . ($hasViewPermission ? 'YES' : 'NO') . "</p>";

        if (!$hasViewPermission) {
            $html .= "<p style='color: red;'><strong>ERROR:</strong> User doesn't have promotions.view permission!</p>";
            $html .= "<p><a href='/admin/permissions/edit-user-permissions/{$user->id}'>Grant permissions here</a></p>";
            return $html;
        }

        // Test promotions query
        $promotions = \App\Models\Promotion::orderBy('created_at', 'desc')->paginate(10);
        $html .= "<p><strong>Promotions count:</strong> {$promotions->count()}</p>";

        // Test view exists
        $viewPath = resource_path('views/le-tan/promotions/index.blade.php');
        $viewExists = file_exists($viewPath);
        $html .= "<p><strong>View exists:</strong> " . ($viewExists ? 'YES' : 'NO') . "</p>";

        if ($viewExists) {
            $html .= "<p style='color: green;'><strong>SUCCESS:</strong> Everything looks good!</p>";
            $html .= "<p><a href='/le-tan/promotions'>Try accessing promotions page</a></p>";
        } else {
            $html .= "<p style='color: red;'><strong>ERROR:</strong> View file missing!</p>";
        }

        return $html;

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine();
    }
});

// Test simple promotions page
Route::get('/test-simple-promotions', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    try {
        $user = auth()->user();

        // Test permission
        if (!$user->hasAnyPermission('promotions', 'view')) {
            return 'No permission to view promotions. <a href="/admin/permissions/edit-user-permissions/' . $user->id . '">Grant permissions</a>';
        }

        // Test controller method directly
        $controller = new \App\Http\Controllers\LeTan\PromotionController();
        $request = new \Illuminate\Http\Request();

        return $controller->index($request);

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine() . '<br><br>Stack trace:<br>' . nl2br($e->getTraceAsString());
    }
});

// Test promotions data only
Route::get('/test-promotions-data', function() {
    if (!auth()->check()) {
        return 'Please login first: <a href="/login">Login</a>';
    }

    try {
        $user = auth()->user();

        // Test permission
        if (!$user->hasAnyPermission('promotions', 'view')) {
            return 'No permission to view promotions. <a href="/admin/permissions/edit-user-permissions/' . $user->id . '">Grant permissions</a>';
        }

        // Test data only
        $promotions = \App\Models\Promotion::orderBy('created_at', 'desc')->paginate(10);

        $html = "<h1>Promotions Data Test</h1>";
        $html .= "<p>Found {$promotions->count()} promotions</p>";

        foreach ($promotions as $promotion) {
            $html .= "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            $html .= "<h3>{$promotion->code}</h3>";
            $html .= "<p>Type: {$promotion->discount_type}</p>";
            $html .= "<p>Value: {$promotion->discount_value}</p>";
            $html .= "<p>Start: {$promotion->start_date}</p>";
            $html .= "<p>End: {$promotion->end_date}</p>";
            $html .= "<p>Active: " . ($promotion->is_active ? 'Yes' : 'No') . "</p>";
            $html .= "</div>";
        }

        $html .= "<br><a href='/le-tan/promotions'>Try real promotions page</a>";

        return $html;

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine();
    }
});

// Debug promotion code
Route::get('/debug-promotion/{code?}', function($code = null) {
    if (!$code) {
        return 'Usage: /debug-promotion/YOUR_CODE';
    }

    try {
        $html = "<h1>Debug Promotion Code: {$code}</h1>";

        // Tìm promotion
        $promotion = \App\Models\Promotion::where('code', strtoupper($code))->first();

        if (!$promotion) {
            $html .= "<p style='color: red;'><strong>ERROR:</strong> Promotion code not found!</p>";

            // Hiển thị tất cả promotion codes
            $allPromotions = \App\Models\Promotion::all();
            $html .= "<h3>Available promotion codes:</h3><ul>";
            foreach ($allPromotions as $p) {
                $html .= "<li><strong>{$p->code}</strong> - {$p->title} (Active: " . ($p->is_active ? 'Yes' : 'No') . ")</li>";
            }
            $html .= "</ul>";

            return $html;
        }

        $html .= "<h3>Promotion Details:</h3>";
        $html .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $html .= "<tr><td><strong>ID</strong></td><td>{$promotion->id}</td></tr>";
        $html .= "<tr><td><strong>Code</strong></td><td>{$promotion->code}</td></tr>";
        $html .= "<tr><td><strong>Title</strong></td><td>{$promotion->title}</td></tr>";
        $html .= "<tr><td><strong>Description</strong></td><td>{$promotion->description}</td></tr>";
        $html .= "<tr><td><strong>Discount Type</strong></td><td>{$promotion->discount_type}</td></tr>";
        $html .= "<tr><td><strong>Discount Value</strong></td><td>{$promotion->discount_value}</td></tr>";
        $html .= "<tr><td><strong>Minimum Purchase</strong></td><td>" . number_format($promotion->minimum_purchase ?? 0) . " VNĐ</td></tr>";
        $html .= "<tr><td><strong>Maximum Discount</strong></td><td>" . ($promotion->maximum_discount ? number_format($promotion->maximum_discount) . " VNĐ" : "Unlimited") . "</td></tr>";
        $html .= "<tr><td><strong>Start Date</strong></td><td>{$promotion->start_date}</td></tr>";
        $html .= "<tr><td><strong>End Date</strong></td><td>{$promotion->end_date}</td></tr>";
        $html .= "<tr><td><strong>Is Active</strong></td><td>" . ($promotion->is_active ? 'YES' : 'NO') . "</td></tr>";
        $html .= "<tr><td><strong>Usage Count</strong></td><td>{$promotion->usage_count}</td></tr>";
        $html .= "<tr><td><strong>Usage Limit</strong></td><td>" . ($promotion->usage_limit ?? 'Unlimited') . "</td></tr>";
        $html .= "</table>";

        // Kiểm tra tính hợp lệ
        $now = now();
        $html .= "<h3>Validation Check:</h3>";
        $html .= "<ul>";
        $html .= "<li><strong>Is Active:</strong> " . ($promotion->is_active ? '✅ YES' : '❌ NO') . "</li>";
        $html .= "<li><strong>Start Date Valid:</strong> " . ($promotion->start_date <= $now ? '✅ YES' : '❌ NO') . " (Start: {$promotion->start_date}, Now: {$now})</li>";
        $html .= "<li><strong>End Date Valid:</strong> " . ($promotion->end_date >= $now ? '✅ YES' : '❌ NO') . " (End: {$promotion->end_date}, Now: {$now})</li>";
        $html .= "<li><strong>Usage Limit OK:</strong> " . (($promotion->usage_limit === null || $promotion->usage_count < $promotion->usage_limit) ? '✅ YES' : '❌ NO') . "</li>";
        $html .= "</ul>";

        // Test với service
        $testService = \App\Models\Service::first();
        if ($testService) {
            $html .= "<h3>Test with Service: {$testService->name} (Price: " . number_format($testService->price) . " VNĐ)</h3>";

            // Test minimum purchase
            if ($promotion->minimum_purchase && $testService->price < $promotion->minimum_purchase) {
                $html .= "<p style='color: red;'><strong>❌ FAILED:</strong> Service price (" . number_format($testService->price) . " VNĐ) is less than minimum purchase (" . number_format($promotion->minimum_purchase) . " VNĐ)</p>";
            } else {
                $html .= "<p style='color: green;'><strong>✅ PASSED:</strong> Service price meets minimum purchase requirement</p>";

                // Calculate discount
                $discount = $promotion->calculateDiscount($testService->price);
                $finalPrice = $testService->price - $discount;

                $html .= "<p><strong>Calculated Discount:</strong> " . number_format($discount) . " VNĐ</p>";
                $html .= "<p><strong>Final Price:</strong> " . number_format($finalPrice) . " VNĐ</p>";
            }
        }

        // Test với PricingService
        if ($testService) {
            $html .= "<h3>Test with PricingService:</h3>";
            $pricingService = new \App\Services\PricingService();
            $priceDetails = $pricingService->calculateFinalPrice($testService, $promotion->code);

            $html .= "<pre>" . json_encode($priceDetails, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        }

        return $html;

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine();
    }
});

// Test promotion validation
Route::get('/test-promotion-validation', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test Promotion Validation</title>
        <meta name="csrf-token" content="' . csrf_token() . '">
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .container { max-width: 600px; margin: 0 auto; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input, button { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
            input { width: 100%; box-sizing: border-box; }
            button { background: #007cba; color: white; cursor: pointer; }
            button:hover { background: #005a87; }
            .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
            .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Test Promotion Validation</h1>

            <div class="form-group">
                <label for="promotion_code">Mã khuyến mãi:</label>
                <input type="text" id="promotion_code" value="SUMMER2025" placeholder="Nhập mã khuyến mãi">
            </div>

            <div class="form-group">
                <label for="amount">Số tiền:</label>
                <input type="number" id="amount" value="500000" placeholder="Nhập số tiền">
            </div>

            <button onclick="testPromotion()">Kiểm tra mã khuyến mãi</button>

            <div id="result"></div>
        </div>

        <script>
        function testPromotion() {
            const code = document.getElementById("promotion_code").value;
            const amount = document.getElementById("amount").value;
            const resultDiv = document.getElementById("result");

            if (!code || !amount) {
                resultDiv.innerHTML = "<div class=\"error\">Vui lòng nhập đầy đủ thông tin</div>";
                return;
            }

            resultDiv.innerHTML = "<div>Đang kiểm tra...</div>";

            fetch("/api/validate-promotion", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                },
                body: JSON.stringify({
                    code: code,
                    amount: parseInt(amount)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Mã khuyến mãi hợp lệ!</h3>
                            <p><strong>Mã:</strong> ${data.data.promotion.code}</p>
                            <p><strong>Tên:</strong> ${data.data.promotion.title}</p>
                            <p><strong>Loại giảm giá:</strong> ${data.data.promotion.discount_type}</p>
                            <p><strong>Giá trị giảm:</strong> ${data.data.promotion.discount_value}${data.data.promotion.discount_type === "percentage" ? "%" : " VNĐ"}</p>
                            <p><strong>Số tiền được giảm:</strong> ${data.data.formatted_discount}</p>
                            <p><strong>Số tiền phải trả:</strong> ${new Intl.NumberFormat("vi-VN").format(parseInt(amount) - data.data.discount)} VNĐ</p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `<div class="error"><strong>❌ Lỗi:</strong> ${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                resultDiv.innerHTML = `<div class="error"><strong>❌ Lỗi kết nối:</strong> ${error.message}</div>`;
            });
        }
        </script>
    </body>
    </html>
    ';
});

// Test booking with promotion
Route::get('/test-booking-promotion', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test Booking with Promotion</title>
        <meta name="csrf-token" content="' . csrf_token() . '">
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .container { max-width: 800px; margin: 0 auto; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input, select, button { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
            input, select { width: 100%; box-sizing: border-box; }
            button { background: #007cba; color: white; cursor: pointer; }
            button:hover { background: #005a87; }
            .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
            .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
            .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Test Booking with Promotion</h1>

            <div class="form-group">
                <label for="service_id">Dịch vụ:</label>
                <select id="service_id">
                    <option value="">Chọn dịch vụ</option>
                    <option value="11">test - 500,000 VNĐ</option>
                    <option value="1">Massage Thư Giãn - 500,000 VNĐ</option>
                    <option value="2">Chăm Sóc Da Mặt Cơ Bản - 750,000 VNĐ</option>
                </select>
            </div>

            <div class="form-group">
                <label for="promotion_code">Mã khuyến mãi:</label>
                <input type="text" id="promotion_code" value="SUMMER2025" placeholder="Nhập mã khuyến mãi">
            </div>

            <button onclick="testPricing()">Kiểm tra giá với mã khuyến mãi</button>

            <div id="result"></div>
        </div>

        <script>
        function testPricing() {
            const serviceId = document.getElementById("service_id").value;
            const promotionCode = document.getElementById("promotion_code").value;
            const resultDiv = document.getElementById("result");

            if (!serviceId) {
                resultDiv.innerHTML = "<div class=\"error\">Vui lòng chọn dịch vụ</div>";
                return;
            }

            resultDiv.innerHTML = "<div class=\"info\">Đang kiểm tra...</div>";

            // Lấy thông tin dịch vụ trước
            const services = {
                "11": { name: "test", price: 500000 },
                "1": { name: "Massage Thư Giãn", price: 500000 },
                "2": { name: "Chăm Sóc Da Mặt Cơ Bản", price: 750000 }
            };

            const service = services[serviceId];
            if (!service) {
                resultDiv.innerHTML = "<div class=\"error\">Dịch vụ không hợp lệ</div>";
                return;
            }

            let html = `<div class="info">
                <h3>📋 Thông tin dịch vụ</h3>
                <p><strong>Tên:</strong> ${service.name}</p>
                <p><strong>Giá gốc:</strong> ${new Intl.NumberFormat("vi-VN").format(service.price)} VNĐ</p>
            </div>`;

            if (promotionCode) {
                // Test mã khuyến mãi
                fetch("/api/validate-promotion", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                    },
                    body: JSON.stringify({
                        code: promotionCode,
                        amount: service.price
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const finalPrice = service.price - data.data.discount;
                        html += `
                            <div class="success">
                                <h3>✅ Mã khuyến mãi hợp lệ!</h3>
                                <p><strong>Mã:</strong> ${data.data.promotion.code}</p>
                                <p><strong>Tên khuyến mãi:</strong> ${data.data.promotion.title}</p>
                                <p><strong>Loại giảm giá:</strong> ${data.data.promotion.discount_type}</p>
                                <p><strong>Giá trị giảm:</strong> ${data.data.promotion.discount_value}${data.data.promotion.discount_type === "percentage" ? "%" : " VNĐ"}</p>
                                <p><strong>Số tiền được giảm:</strong> ${data.data.formatted_discount}</p>
                                <p><strong>Giá sau giảm:</strong> ${new Intl.NumberFormat("vi-VN").format(finalPrice)} VNĐ</p>
                                <p><strong>Tiết kiệm:</strong> ${Math.round((data.data.discount / service.price) * 100)}%</p>
                            </div>
                        `;
                    } else {
                        html += `<div class="error"><strong>❌ Lỗi mã khuyến mãi:</strong> ${data.message}</div>`;
                    }
                    resultDiv.innerHTML = html;
                })
                .catch(error => {
                    console.error("Error:", error);
                    html += `<div class="error"><strong>❌ Lỗi kết nối:</strong> ${error.message}</div>`;
                    resultDiv.innerHTML = html;
                });
            } else {
                html += `<div class="info">Không có mã khuyến mãi</div>`;
                resultDiv.innerHTML = html;
            }
        }
        </script>
    </body>
    </html>
    ';
});

// Debug reminders
Route::get('/debug-reminders', function() {
    try {
        echo '<h1>Debug Reminders</h1>';

        // Check if table exists
        $tableExists = \Illuminate\Support\Facades\Schema::hasTable('reminders');
        echo '<p><strong>Table exists:</strong> ' . ($tableExists ? 'YES' : 'NO') . '</p>';

        if (!$tableExists) {
            return 'Table does not exist!';
        }

        // Check model
        echo '<p><strong>Model test:</strong></p>';
        $reminderCount = \App\Models\Reminder::count();
        echo '<p>Total reminders: ' . $reminderCount . '</p>';

        // Test query with relationships
        echo '<p><strong>Testing relationships:</strong></p>';
        $reminders = \App\Models\Reminder::with(['appointment', 'appointment.customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        echo '<p>Loaded ' . $reminders->count() . ' reminders with relationships</p>';

        foreach ($reminders as $reminder) {
            echo '<div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">';
            echo '<p><strong>ID:</strong> ' . $reminder->id . '</p>';
            echo '<p><strong>Message:</strong> ' . substr($reminder->message, 0, 100) . '...</p>';
            echo '<p><strong>Status:</strong> ' . $reminder->status . '</p>';
            echo '<p><strong>Reminder Date:</strong> ' . $reminder->reminder_date . '</p>';

            if ($reminder->appointment) {
                echo '<p><strong>Appointment ID:</strong> ' . $reminder->appointment->id . '</p>';
                if ($reminder->appointment->customer) {
                    echo '<p><strong>Customer:</strong> ' . $reminder->appointment->customer->first_name . ' ' . $reminder->appointment->customer->last_name . '</p>';
                } else {
                    echo '<p><strong>Customer:</strong> Not found</p>';
                }
            } else {
                echo '<p><strong>Appointment:</strong> Not found</p>';
            }

            if ($reminder->createdBy) {
                echo '<p><strong>Created by:</strong> ' . $reminder->createdBy->first_name . ' ' . $reminder->createdBy->last_name . '</p>';
            } else {
                echo '<p><strong>Created by:</strong> Not found</p>';
            }
            echo '</div>';
        }

        return '';

    } catch (\Exception $e) {
        return '<h1>Error</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

// Test reminders page without auth
Route::get('/test-reminders-page', function() {
    try {
        $reminders = \App\Models\Reminder::with(['appointment', 'appointment.customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('le-tan.reminders.index', compact('reminders'));
    } catch (\Exception $e) {
        return '<h1>Error in reminders page</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

// Test popup directly
Route::get('/test-popup', function() {
    return view('test-popup');
});

// Reset popup session
Route::get('/reset-popup', function() {
    return '
    <script>
        sessionStorage.removeItem("popupShown");
        alert("Popup session reset! Go back to homepage to see popup.");
        window.history.back();
    </script>
    ';
});

// Force show popup
Route::get('/force-popup', function() {
    return view('force-popup');
});