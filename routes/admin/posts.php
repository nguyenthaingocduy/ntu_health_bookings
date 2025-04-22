<?php

use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'permission:posts.view']], function () {
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('admin.posts.create')->middleware('permission:posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('admin.posts.store')->middleware('permission:posts.create');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('admin.posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('admin.posts.edit')->middleware('permission:posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('admin.posts.update')->middleware('permission:posts.edit');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('admin.posts.destroy')->middleware('permission:posts.delete');
    
    // Post actions
    Route::put('/posts/{id}/status', [PostController::class, 'updateStatus'])->name('admin.posts.update-status')->middleware('permission:posts.edit');
    Route::put('/posts/{id}/featured', [PostController::class, 'toggleFeatured'])->name('admin.posts.toggle-featured')->middleware('permission:posts.edit');
});
