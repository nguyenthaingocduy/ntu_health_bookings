<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HealthCheckupController;
use App\Http\Controllers\Admin\ServiceController;
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
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::resource('clinics', ClinicController::class);
    Route::patch('clinics/{id}/toggle-status', [ClinicController::class, 'toggleStatus'])->name('clinics.toggle-status');
    Route::resource('appointments', AppointmentController::class);
    Route::post('appointments/{id}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('appointments/{id}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

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
});
