<?php

use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'permission:promotions.view']], function () {
    // Promotions
    Route::get('/promotions', [PromotionController::class, 'index'])->name('admin.promotions.index');
    Route::get('/promotions/create', [PromotionController::class, 'create'])->name('admin.promotions.create')->middleware('permission:promotions.create');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('admin.promotions.store')->middleware('permission:promotions.create');
    Route::get('/promotions/{id}', [PromotionController::class, 'show'])->name('admin.promotions.show');
    Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('admin.promotions.edit')->middleware('permission:promotions.edit');
    Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('admin.promotions.update')->middleware('permission:promotions.edit');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy')->middleware('permission:promotions.delete');
    
    // Promotion actions
    Route::put('/promotions/{id}/active', [PromotionController::class, 'toggleActive'])->name('admin.promotions.toggle-active')->middleware('permission:promotions.edit');
    Route::put('/promotions/{id}/reset-usage', [PromotionController::class, 'resetUsageCount'])->name('admin.promotions.reset-usage')->middleware('permission:promotions.edit');
});
