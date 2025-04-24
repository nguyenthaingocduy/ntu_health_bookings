<?php

use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;

// Middleware 'auth' and 'admin' are already applied in admin.php
Route::group([], function () {
    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Role permissions
    Route::get('/role-permissions', [PermissionController::class, 'rolePermissions'])->name('permissions.role-permissions');
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions'])->name('permissions.update-role-permissions');

    // User permissions
    Route::get('/user-permissions', [PermissionController::class, 'userPermissions'])->name('permissions.user-permissions');
    Route::get('/user-permissions/{id}/edit', [PermissionController::class, 'editUserPermissions'])->name('permissions.edit-user-permissions');
    Route::put('/user-permissions/{id}', [PermissionController::class, 'updateUserPermissions'])->name('permissions.update-user-permissions');
});
