<?php

use App\Http\Controllers\NVKT\DashboardController;
use App\Http\Controllers\NVKT\ProfessionalNoteController;
use App\Http\Controllers\NVKT\ScheduleController;
use App\Http\Controllers\NVKT\SessionController;
use App\Http\Controllers\NVKT\ServiceController;
use App\Http\Controllers\NVKT\CustomerController;
use App\Http\Controllers\NVKT\WorkStatusController;
use App\Http\Controllers\NVKT\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Nhân viên kỹ thuật Routes
|--------------------------------------------------------------------------
|
| Các route dành cho nhân viên kỹ thuật
|
*/

Route::middleware(['auth', \App\Http\Middleware\CheckUserRole::class.':Technician'])->prefix('nvkt')->name('nvkt.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/appointments/assigned', [ScheduleController::class, 'assignedAppointments'])->name('appointments.assigned');

    // Work Schedules - Removed

    // Sessions
    Route::get('/sessions/completed', [SessionController::class, 'completed'])->name('sessions.completed');
    Route::get('/sessions/{id}', [SessionController::class, 'show'])->name('sessions.show');
    Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('sessions.update');

    // Professional Notes
    Route::get('/notes', [ProfessionalNoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [ProfessionalNoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [ProfessionalNoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}', [ProfessionalNoteController::class, 'show'])->name('notes.show');
    Route::get('/notes/{id}/edit', [ProfessionalNoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [ProfessionalNoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [ProfessionalNoteController::class, 'destroy'])->name('notes.destroy');

    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/service-history', [CustomerController::class, 'serviceHistory'])->name('customers.service-history');

    // Work Status
    Route::get('/work-status', [WorkStatusController::class, 'index'])->name('work-status.index');
    Route::put('/work-status/{id}', [WorkStatusController::class, 'update'])->name('work-status.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/services', [ReportController::class, 'services'])->name('reports.services');
    Route::get('/reports/ratings', [ReportController::class, 'ratings'])->name('reports.ratings');
});

// Redirect từ /technician sang /nvkt/dashboard
Route::redirect('/technician', '/nvkt/dashboard');

// Test route for new layout
Route::get('/test-new-layout', function() {
    return view('nvkt.test-new-layout');
});
