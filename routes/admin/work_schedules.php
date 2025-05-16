<?php

use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Route;

// Work Schedules
Route::get('/work-schedules', [WorkScheduleController::class, 'index'])->name('work-schedules.index');
Route::post('/work-schedules', [WorkScheduleController::class, 'store'])->name('work-schedules.store');
Route::delete('/work-schedules', [WorkScheduleController::class, 'destroy'])->name('work-schedules.destroy');
Route::get('/work-schedules/view-week', [WorkScheduleController::class, 'viewWeek'])->name('work-schedules.view-week');
Route::get('/work-schedules/test', [TestController::class, 'index'])->name('work-schedules.test');
