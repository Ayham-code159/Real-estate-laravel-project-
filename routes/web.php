<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Admin\Admin\AdminManagementController;
use App\Http\Controllers\Admin\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\BusinessAccount\AdminBusinessAccountController;
use App\Http\Controllers\Admin\MasterData\AdminMasterDataController;
use App\Http\Controllers\Admin\ServiceListing\AdminServiceListingManagementController;

// admin login
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

// admin that can manage the business_accounts

    Route::middleware(['auth:admin', 'admin.permission:manage_business_accounts'])->group(function () {
        Route::get('/business-accounts', [AdminBusinessAccountController::class, 'index'])
            ->name('admin.business-accounts.index');

        Route::put('/business-accounts/{businessAccount}/status', [AdminBusinessAccountController::class, 'updateStatus'])
            ->name('admin.business-accounts.update-status');
    });
// admin that can manage the users
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
// the super admin
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

        Route::get('/master-data/business-types', [AdminMasterDataController::class, 'businessTypes'])
            ->name('admin.master-data.business-types.index');

        Route::get('/master-data/business-types/{businessType}', [AdminMasterDataController::class, 'showBusinessType'])
            ->name('admin.master-data.business-types.show');

        Route::post('/master-data/business-types', [AdminMasterDataController::class, 'storeBusinessType'])
            ->name('admin.master-data.business-types.store');

        Route::put('/master-data/business-types/{businessType}', [AdminMasterDataController::class, 'updateBusinessType'])
            ->name('admin.master-data.business-types.update');

        Route::delete('/master-data/business-types/{businessType}', [AdminMasterDataController::class, 'destroyBusinessType'])
            ->name('admin.master-data.business-types.destroy');

        Route::get('/master-data/services', [AdminMasterDataController::class, 'services'])
            ->name('admin.master-data.services.index');

        Route::post('/master-data/services', [AdminMasterDataController::class, 'storeService'])
            ->name('admin.master-data.services.store');

        Route::get('/master-data/services/{service}', [AdminMasterDataController::class, 'showService'])
            ->name('admin.master-data.services.show');

        Route::put('/master-data/services/{service}', [AdminMasterDataController::class, 'updateService'])
            ->name('admin.master-data.services.update');

        Route::delete('/master-data/services/{service}', [AdminMasterDataController::class, 'destroyService'])
            ->name('admin.master-data.services.destroy');

        Route::post('/master-data/service-subcategories', [AdminMasterDataController::class, 'storeServiceSubcategory'])
            ->name('admin.master-data.service-subcategories.store');

        Route::get('/master-data/service-subcategories/{serviceSubcategory}', [AdminMasterDataController::class, 'showServiceSubcategory'])
            ->name('admin.master-data.service-subcategories.show');

        Route::put('/master-data/service-subcategories/{serviceSubcategory}', [AdminMasterDataController::class, 'updateServiceSubcategory'])
            ->name('admin.master-data.service-subcategories.update');

        Route::delete('/master-data/service-subcategories/{serviceSubcategory}', [AdminMasterDataController::class, 'destroyServiceSubcategory'])
            ->name('admin.master-data.service-subcategories.destroy');

        Route::get('/service-listings', [AdminServiceListingManagementController::class, 'index'])
            ->name('admin.service-listings.index');

        Route::get('/service-listings/{serviceListing}', [AdminServiceListingManagementController::class, 'show'])
            ->name('admin.service-listings.show');

        Route::put('/service-listings/{serviceListing}/status', [AdminServiceListingManagementController::class, 'updateStatus'])
            ->name('admin.service-listings.update-status');
    });

});
