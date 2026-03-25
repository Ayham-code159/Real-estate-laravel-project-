<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\BusinessAccount\AdminBusinessAccountController;
use App\Http\Controllers\Admin\User\AdminUserController;

Route::prefix('admin')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/business-accounts', [AdminBusinessAccountController::class, 'index'])
        ->name('admin.business-accounts.index');

    Route::put('/business-accounts/{businessAccount}/status', [AdminBusinessAccountController::class, 'updateStatus'])
        ->name('admin.business-accounts.update-status');

    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/users/{user}', [AdminUserController::class, 'show'])
        ->name('admin.users.show');

    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::delete('/users/business-accounts/{businessAccount}', [AdminUserController::class, 'destroyBusinessAccount'])
        ->name('admin.users.business-accounts.destroy');
});




});
