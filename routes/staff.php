<?php

use App\Http\Controllers\Staff\HealthCheckupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
|
| Here is where you can register staff routes for your application.
|
*/

Route::middleware(['auth', \App\Http\Middleware\StaffMiddleware::class])->prefix('staff')->name('staff.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    // Work Schedule
    Route::get('/work-schedule', [\App\Http\Controllers\Staff\WorkScheduleController::class, 'index'])->name('work-schedule');
    Route::get('/appointments/{id}/complete', [\App\Http\Controllers\Staff\WorkScheduleController::class, 'completeAppointment'])->name('appointments.complete');

    // Statistics
    Route::get('/statistics', [\App\Http\Controllers\Staff\StatisticsController::class, 'index'])->name('statistics');

    // Health Check-up Routes
    Route::prefix('health-checkups')->name('health-checkups.')->middleware(\App\Http\Middleware\UniversityStaffMiddleware::class)->group(function () {
        Route::get('/', [HealthCheckupController::class, 'index'])->name('index');
        Route::get('/create', [HealthCheckupController::class, 'create'])->name('create');
        Route::post('/', [HealthCheckupController::class, 'store'])->name('store');
        Route::get('/{id}', [HealthCheckupController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [HealthCheckupController::class, 'cancel'])->name('cancel');

        // Health Records
        Route::get('/records', [HealthCheckupController::class, 'healthRecords'])->name('records');
        Route::get('/records/{id}', [HealthCheckupController::class, 'showHealthRecord'])->name('records.show');
    });
});
