<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\LoanFlowController;
use Illuminate\Support\Facades\Route;

Route::redirect('/login', '/admin/login')->name('login');

Route::get('/', [LoanFlowController::class, 'peminjamanAlat']);

Route::get('/items/active', [LoanFlowController::class, 'activeItems']);
Route::post('/tap-card', [LoanFlowController::class, 'tapCard']);
Route::post('/loans/borrow', [LoanFlowController::class, 'storeBorrow']);
Route::post('/loans/return-items', [LoanFlowController::class, 'returnItems']);

Route::prefix('admin')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login.form');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/returns', [AdminAuthController::class, 'returnsDashboard'])->name('admin.returns.dashboard');
        Route::post('/items', [AdminAuthController::class, 'storeItem'])->name('admin.items.store');
        Route::put('/items/{item}', [AdminAuthController::class, 'updateItem'])->name('admin.items.update');
        Route::delete('/items/{item}', [AdminAuthController::class, 'destroyItem'])->name('admin.items.destroy');
        Route::put('/loans/{loan}', [AdminAuthController::class, 'updateLoan'])->name('admin.loans.update');
        Route::delete('/loans/{loan}', [AdminAuthController::class, 'destroyLoan'])->name('admin.loans.destroy');
        Route::post('/returns/{loan}/process', [AdminAuthController::class, 'processReturn'])->name('admin.returns.process');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
