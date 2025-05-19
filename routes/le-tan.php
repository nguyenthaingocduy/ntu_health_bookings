<?php

use App\Http\Controllers\LeTan\AppointmentController;
use App\Http\Controllers\LeTan\CustomerController;
use App\Http\Controllers\LeTan\DashboardController;
use App\Http\Controllers\LeTan\PaymentController;
use App\Http\Controllers\LeTan\ConsultationController;
use App\Http\Controllers\LeTan\ReminderController;
use App\Http\Controllers\LeTan\ServiceController;
use App\Http\Controllers\LeTan\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Lễ tân Routes
|--------------------------------------------------------------------------
|
| Các route dành cho lễ tân
|
*/

Route::middleware(['auth', \App\Http\Middleware\CheckUserRole::class.':Receptionist'])->prefix('le-tan')->name('le-tan.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::match(['post', 'put'], '/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{id}/assign-staff', [AppointmentController::class, 'assignStaff'])->name('appointments.assign-staff');
    Route::post('/appointments/{id}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::match(['post', 'put'], '/appointments/{id}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');

    // Service Consultations
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{id}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::get('/consultations/{id}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{id}', [ConsultationController::class, 'update'])->name('consultations.update');
    Route::delete('/consultations/{id}', [ConsultationController::class, 'destroy'])->name('consultations.destroy');
    Route::get('/consultations/{id}/convert', [ConsultationController::class, 'convert'])->name('consultations.convert');

    // Reminders
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/create', [ReminderController::class, 'create'])->name('reminders.create');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::get('/reminders/{id}', [ReminderController::class, 'show'])->name('reminders.show');
    Route::get('/reminders/{id}/edit', [ReminderController::class, 'edit'])->name('reminders.edit');
    Route::put('/reminders/{id}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{id}', [ReminderController::class, 'destroy'])->name('reminders.destroy');
    Route::post('/reminders/{id}/send', [ReminderController::class, 'sendReminder'])->name('reminders.send');

    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
});

// Redirect từ /receptionist sang /le-tan/dashboard
Route::redirect('/receptionist', '/le-tan/dashboard');
