<?php

use App\Http\Controllers\NVKT\DashboardController;
use App\Http\Controllers\NVKT\ProfessionalNoteController;
use App\Http\Controllers\NVKT\ScheduleController;
use App\Http\Controllers\NVKT\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Nhân viên kỹ thuật Routes
|--------------------------------------------------------------------------
|
| Các route dành cho nhân viên kỹ thuật
|
*/

Route::middleware(['auth', 'user.role:Technician'])->prefix('nvkt')->name('nvkt.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

    // Sessions
    Route::get('/sessions/{id}', [SessionController::class, 'show'])->name('sessions.show');
    Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('sessions.update');
    Route::get('/sessions/completed', [SessionController::class, 'completed'])->name('sessions.completed');

    // Professional Notes
    Route::get('/notes', [ProfessionalNoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [ProfessionalNoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [ProfessionalNoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}', [ProfessionalNoteController::class, 'show'])->name('notes.show');
    Route::get('/notes/{id}/edit', [ProfessionalNoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [ProfessionalNoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [ProfessionalNoteController::class, 'destroy'])->name('notes.destroy');
});

// Redirect từ /technician sang /nvkt/dashboard
Route::redirect('/technician', '/nvkt/dashboard');
