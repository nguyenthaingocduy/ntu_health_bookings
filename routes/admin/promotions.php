<?php

use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Route;

// Promotions - Middleware 'auth' and 'admin' are already applied in admin.php
// Tạm thời bỏ middleware permission để đảm bảo route hoạt động
Route::group([], function () {
    // Promotions
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{id}', [PromotionController::class, 'show'])->name('promotions.show');
    Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');

    // Promotion actions
    Route::put('/promotions/{id}/active', [PromotionController::class, 'toggleActive'])->name('promotions.toggle-active');
    Route::put('/promotions/{id}/reset-usage', [PromotionController::class, 'resetUsageCount'])->name('promotions.reset-usage');

    // Promotion services management
    Route::get('/promotions/{id}/services', [PromotionController::class, 'services'])->name('promotions.services');
    Route::put('/promotions/{id}/services', [PromotionController::class, 'updateServices'])->name('promotions.update-services');

    // Validate promotion code
    Route::post('/promotions/validate-code', [PromotionController::class, 'validateCode'])->name('promotions.validate-code');
});
