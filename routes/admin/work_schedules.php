<?php

use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Route;

// Work Schedules
// Chuyển hướng từ trang chính sang trang phân công theo tuần
Route::redirect('/work-schedules', '/admin/work-schedules/weekly-assignment')->name('work-schedules.index');

// Các route cho phân công lịch làm việc theo tuần
Route::get('/work-schedules/weekly-assignment', [WorkScheduleController::class, 'weeklyAssignment'])->name('work-schedules.weekly-assignment');
Route::post('/work-schedules/weekly-assignment', [WorkScheduleController::class, 'storeWeeklyAssignment'])->name('work-schedules.store-weekly-assignment');

// Xem lịch làm việc
Route::get('/work-schedules/view-week', [WorkScheduleController::class, 'viewWeek'])->name('work-schedules.view-week');

// Xóa lịch làm việc
Route::delete('/work-schedules', [WorkScheduleController::class, 'destroy'])->name('work-schedules.destroy');

// Route test
Route::get('/work-schedules/test', [TestController::class, 'index'])->name('work-schedules.test');
