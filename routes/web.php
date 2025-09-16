<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InstallmentPlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Customers
    Route::resource('customers', CustomerController::class);
    
    // Installment Plans
    Route::resource('installment-plans', InstallmentPlanController::class);
    Route::post('/installment-plans/{plan}/payment', [InstallmentPlanController::class, 'recordPayment'])->name('installment-plans.payment');
    
    // Payments
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.mark-paid');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportsController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/overdue', [ReportsController::class, 'overdue'])->name('reports.overdue');
    Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';