<?php

use App\Http\Controllers\Admin\CustomerTypeController;
use Illuminate\Support\Facades\Route;

// Customer Types Management
Route::resource('customer-types', CustomerTypeController::class);
Route::post('customer-types/{customerType}/toggle-status', [CustomerTypeController::class, 'toggleStatus'])->name('customer-types.toggle-status');
