<?php

use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('admin.permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');
    
    // Role permissions
    Route::get('/role-permissions', [PermissionController::class, 'rolePermissions'])->name('admin.permissions.role-permissions');
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions'])->name('admin.permissions.update-role-permissions');
    
    // User permissions
    Route::get('/user-permissions', [PermissionController::class, 'userPermissions'])->name('admin.permissions.user-permissions');
    Route::get('/user-permissions/{id}/edit', [PermissionController::class, 'editUserPermissions'])->name('admin.permissions.edit-user-permissions');
    Route::put('/user-permissions/{id}', [PermissionController::class, 'updateUserPermissions'])->name('admin.permissions.update-user-permissions');
});
