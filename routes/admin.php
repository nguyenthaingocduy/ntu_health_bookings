<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HealthCheckupController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resources
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    Route::resource('services', ServiceController::class);
    Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::post('employees/{id}/reset-password', [EmployeeController::class, 'resetPassword'])->name('employees.reset-password');
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::resource('clinics', ClinicController::class);
    Route::post('clinics/{id}/toggle-status', [ClinicController::class, 'toggleStatus'])->name('clinics.toggle-status');


    // Appointments - specific routes first to avoid conflicts
    Route::get('appointments/export', [AppointmentController::class, 'export'])->name('appointments.export');
    Route::post('appointments/bulk-delete', [AppointmentController::class, 'bulkDelete'])->name('appointments.bulk-delete');
    Route::get('appointments/{id}/assign-staff', [AppointmentController::class, 'assignStaff'])->name('appointments.assign-staff');
    Route::post('appointments/{id}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('appointments/{id}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::resource('appointments', AppointmentController::class);

    // Health Check-up Management
    Route::prefix('health-checkups')->name('health-checkups.')->group(function () {
        Route::get('/', [HealthCheckupController::class, 'index'])->name('index');
        Route::get('/create', [HealthCheckupController::class, 'create'])->name('create');
        Route::post('/', [HealthCheckupController::class, 'store'])->name('store');
        Route::get('/{id}', [HealthCheckupController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [HealthCheckupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HealthCheckupController::class, 'update'])->name('update');

        // Health Records Management
        Route::get('/{id}/record', [HealthCheckupController::class, 'recordForm'])->name('record.form');
        Route::post('/{id}/record', [HealthCheckupController::class, 'saveRecord'])->name('record.save');
        Route::get('/records', [HealthCheckupController::class, 'healthRecords'])->name('records');
        Route::get('/records/{id}', [HealthCheckupController::class, 'showHealthRecord'])->name('records.show');
    });

    // Contact Management
    Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/mark-as-read', [\App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-as-read');

    // Test Image Upload
    Route::get('/test-upload', function() {
        return view('admin.test-upload');
    })->name('test-upload-form');

    Route::post('/test-upload', function(\Illuminate\Http\Request $request) {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('services', $filename, 'public');
            $publicPath = '/storage/services/' . $filename;
            return redirect()->route('admin.test-upload-form')->with('image_path', $publicPath);
        }
        return redirect()->route('admin.test-upload-form')->with('error', 'No image uploaded');
    })->name('test-upload');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Reports
    Route::get('/reports/customer-types', [\App\Http\Controllers\Admin\CustomerTypeReportController::class, 'index'])->name('reports.customer-types');

    // Revenue Statistics
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/export', [RevenueController::class, 'export'])->name('revenue.export');

    // Test route
    Route::get('/revenue/test', function() {
        return 'Revenue test route works!';
    })->name('revenue.test');

    // Include additional admin routes
    require __DIR__.'/admin/invoices.php';
    require __DIR__.'/admin/posts.php';
    require __DIR__.'/admin/promotions.php';
    require __DIR__.'/admin/settings.php';
    require __DIR__.'/admin/permissions.php';
    require __DIR__.'/admin/roles.php';
    require __DIR__.'/admin/work_schedules.php';
    require __DIR__.'/admin/customer_types.php';
});
