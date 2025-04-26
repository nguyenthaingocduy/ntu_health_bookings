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

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create/{service?}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/clinics/{clinicId}/services', [ServiceController::class, 'byClinic'])->name('services.by-clinic');
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

// Route chuyển hướng dựa trên vai trò
Route::get('/dashboard', function() {
    return redirect()->route('home');
})->middleware(['auth', 'redirect.role'])->name('dashboard');

// API routes for time slots and services
Route::prefix('api')->group(function () {
    Route::get('/available-time-slots', [\App\Http\Controllers\Api\TimeSlotController::class, 'getAvailableTimeSlots']);
    Route::get('/time-slots/{id}', [\App\Http\Controllers\Api\TimeSlotController::class, 'getTimeSlot']);
    Route::get('/services/{id}', [\App\Http\Controllers\Api\ServiceController::class, 'show']);
});

// Legacy API route for backward compatibility
Route::get('/api/check-available-slots', [\App\Http\Controllers\Api\TimeSlotController::class, 'checkAvailableSlots']);




