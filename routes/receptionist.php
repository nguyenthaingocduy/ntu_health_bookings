<?php

use App\Http\Controllers\Receptionist\AppointmentController;
use App\Http\Controllers\Receptionist\CustomerController;
use App\Http\Controllers\Receptionist\DashboardController;
use App\Http\Controllers\Receptionist\NotificationController;
use App\Http\Controllers\Receptionist\PaymentController;
use App\Http\Controllers\Receptionist\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Receptionist Routes
|--------------------------------------------------------------------------
|
| Here is where you can register receptionist routes for your application.
|
*/

Route::middleware(['auth'])->prefix('receptionist')->name('receptionist.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');

    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\Staff\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [\App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
