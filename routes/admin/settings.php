<?php

use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'permission:settings.view']], function () {
    // General settings
    Route::get('/settings/general', [SettingController::class, 'general'])->name('admin.settings.general');
    Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('admin.settings.update-general')->middleware('permission:settings.edit');
    
    // Contact settings
    Route::get('/settings/contact', [SettingController::class, 'contact'])->name('admin.settings.contact');
    Route::put('/settings/contact', [SettingController::class, 'updateContact'])->name('admin.settings.update-contact')->middleware('permission:settings.edit');
    
    // Payment settings
    Route::get('/settings/payment', [SettingController::class, 'payment'])->name('admin.settings.payment');
    Route::put('/settings/payment', [SettingController::class, 'updatePayment'])->name('admin.settings.update-payment')->middleware('permission:settings.edit');
    
    // Working hours settings
    Route::get('/settings/working-hours', [SettingController::class, 'workingHours'])->name('admin.settings.working-hours');
    Route::put('/settings/working-hours', [SettingController::class, 'updateWorkingHours'])->name('admin.settings.update-working-hours')->middleware('permission:settings.edit');
    
    // Email settings
    Route::get('/settings/email', [SettingController::class, 'email'])->name('admin.settings.email');
    Route::put('/settings/email', [SettingController::class, 'updateEmail'])->name('admin.settings.update-email')->middleware('permission:settings.edit');
    Route::post('/settings/email/test', [SettingController::class, 'sendTestEmail'])->name('admin.settings.send-test-email')->middleware('permission:settings.edit');
    
    // Cache settings
    Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('admin.settings.clear-cache')->middleware('permission:settings.edit');
});
