<?php

use App\Http\Controllers\Admin\HealthCheckupController;
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
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

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
