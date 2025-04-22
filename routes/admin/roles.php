<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
});
