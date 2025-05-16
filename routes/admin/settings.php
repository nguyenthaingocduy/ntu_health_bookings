<?php

use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

// General settings
Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.update-general');

// Contact settings
Route::get('/settings/contact', [SettingController::class, 'contact'])->name('settings.contact');
Route::put('/settings/contact', [SettingController::class, 'updateContact'])->name('settings.update-contact');

// Payment settings
Route::get('/settings/payment', [SettingController::class, 'payment'])->name('settings.payment');
Route::put('/settings/payment', [SettingController::class, 'updatePayment'])->name('settings.update-payment');

// Working hours settings
Route::get('/settings/working-hours', [SettingController::class, 'workingHours'])->name('settings.working-hours');
Route::put('/settings/working-hours', [SettingController::class, 'updateWorkingHours'])->name('settings.update-working-hours');

// Email settings
Route::get('/settings/email', [SettingController::class, 'email'])->name('settings.email');
Route::put('/settings/email', [SettingController::class, 'updateEmail'])->name('settings.update-email');
Route::post('/settings/email/test', [SettingController::class, 'sendTestEmail'])->name('settings.send-test-email');

// Cache settings
Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
