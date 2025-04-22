<?php

use App\Http\Controllers\Admin\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'permission:invoices.view']], function () {
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('admin.invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('admin.invoices.create')->middleware('permission:invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('admin.invoices.store')->middleware('permission:invoices.create');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('admin.invoices.show');
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('admin.invoices.edit')->middleware('permission:invoices.edit');
    Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('admin.invoices.update')->middleware('permission:invoices.edit');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('admin.invoices.destroy')->middleware('permission:invoices.delete');
    
    // Invoice actions
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('admin.invoices.pdf')->middleware('permission:invoices.print');
    Route::put('/invoices/{id}/status', [InvoiceController::class, 'updateStatus'])->name('admin.invoices.update-status')->middleware('permission:invoices.edit');
    
    // Reports
    Route::get('/reports/invoices', [InvoiceController::class, 'report'])->name('admin.reports.invoices')->middleware('permission:reports.view');
});
