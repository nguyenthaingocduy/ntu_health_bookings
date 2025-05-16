<?php

use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

// Middleware 'auth' and 'admin' are already applied in admin.php
Route::group([], function () {
    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Post actions
    Route::put('/posts/{id}/status', [PostController::class, 'updateStatus'])->name('posts.update-status');
    Route::put('/posts/{id}/featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
});
