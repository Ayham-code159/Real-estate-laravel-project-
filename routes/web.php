<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Admin\Admin\AdminManagementController;
use App\Http\Controllers\Admin\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\Service\AdminServiceManagementController;
use App\Http\Controllers\Admin\BusinessAccount\AdminBusinessAccountController;

Route::prefix('admin')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_business_accounts'])->group(function () {
        Route::get('/business-accounts', [AdminBusinessAccountController::class, 'index'])
            ->name('admin.business-accounts.index');

        Route::put('/business-accounts/{businessAccount}/status', [AdminBusinessAccountController::class, 'updateStatus'])
            ->name('admin.business-accounts.update-status');

        Route::get('/services', [AdminServiceManagementController::class, 'index'])
            ->name('admin.services.index');

        Route::get('/services/{type}/{id}', [AdminServiceManagementController::class, 'show'])
            ->name('admin.services.show');

        Route::put('/services/{type}/{id}/status', [AdminServiceManagementController::class, 'updateStatus'])
            ->name('admin.services.update-status');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_users'])->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('admin.users.show');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('admin.users.destroy');

        Route::delete('/users/business-accounts/{businessAccount}', [AdminUserController::class, 'destroyBusinessAccount'])
            ->name('admin.users.business-accounts.destroy');
    });

    Route::middleware(['auth:admin', 'admin.permission:super_admin'])->group(function () {
        Route::get('/admins', [AdminManagementController::class, 'index'])
            ->name('admin.admins.index');

        Route::get('/admins/create', [AdminManagementController::class, 'create'])
            ->name('admin.admins.create');

        Route::post('/admins', [AdminManagementController::class, 'store'])
            ->name('admin.admins.store');

        Route::get('/admins/{admin}', [AdminManagementController::class, 'show'])
            ->name('admin.admins.show');

        Route::get('/admins/{admin}/edit', [AdminManagementController::class, 'edit'])
            ->name('admin.admins.edit');

        Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])
            ->name('admin.admins.update');
    });
});
