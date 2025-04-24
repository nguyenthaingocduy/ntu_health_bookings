<?php

use App\Http\Controllers\Technician\DashboardController;
use App\Http\Controllers\Technician\ProfessionalNoteController;
use App\Http\Controllers\Technician\ScheduleController;
use App\Http\Controllers\Technician\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Technician Routes
|--------------------------------------------------------------------------
|
| Here is where you can register technician routes for your application.
|
*/

Route::middleware(['auth'])->prefix('technician')->name('technician.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\Staff\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [\App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
