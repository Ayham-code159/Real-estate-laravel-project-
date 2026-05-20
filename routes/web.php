<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Admin\Admin\AdminManagementController;
use App\Http\Controllers\Admin\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\BusinessAccount\AdminBusinessAccountController;
use App\Http\Controllers\Admin\MasterData\AdminMasterDataController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\ItemManagementController;
use App\Http\Controllers\Admin\ItemSliderController;

Route::get('/chat-test', function () {
    return view('chat-test');
});

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

    Route::middleware(['auth:admin', 'admin.permission:super_admin'])->group(function () {
        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admin.admins.index');
        Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admin.admins.create');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admin.admins.store');
        Route::get('/admins/{admin}', [AdminManagementController::class, 'show'])->name('admin.admins.show');
        Route::get('/admins/{admin}/edit', [AdminManagementController::class, 'edit'])->name('admin.admins.edit');
        Route::put('/admins/{admin}', [AdminManagementController::class, 'update'])->name('admin.admins.update');
        Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admin.admins.destroy');

    });

    Route::middleware(['auth:admin', 'admin.permission:manage_business_accounts'])->group(function () {
        Route::get('/business-accounts', [AdminBusinessAccountController::class, 'index'])
            ->name('admin.business-accounts.index');

        Route::put('/business-accounts/{businessAccount}/status', [AdminBusinessAccountController::class, 'updateStatus'])
            ->name('admin.business-accounts.update-status');

        Route::get('/business-accounts/{businessAccount}', [AdminBusinessAccountController::class, 'show'])
            ->name('admin.business-accounts.show');

        Route::delete('/business-accounts/{businessAccount}', [AdminBusinessAccountController::class, 'destroy'])
            ->name('admin.business-accounts.destroy');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_users'])->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/business-accounts/{businessAccount}', [AdminUserController::class, 'showBusinessAccount'])
            ->name('admin.users.business-accounts.show');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::delete('/users/business-accounts/{businessAccount}', [AdminUserController::class, 'destroyBusinessAccount'])
            ->name('admin.users.business-accounts.destroy');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_business_types'])->group(function () {
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
    });


    Route::middleware(['auth:admin', 'admin.permission:manage_cities'])->group(function () {

        Route::get('/master-data/cities', [AdminMasterDataController::class, 'cities'])
            ->name('admin.cities.index');

        Route::get('/master-data/cities/{city}', [AdminMasterDataController::class, 'showCity'])
            ->name('admin.cities.show');

        Route::post('/master-data/cities', [AdminMasterDataController::class, 'storeCity'])
            ->name('admin.cities.store');

        Route::put('/master-data/cities/{city}', [AdminMasterDataController::class, 'updateCity'])
            ->name('admin.cities.update');

        Route::delete('/master-data/cities/{city}', [AdminMasterDataController::class, 'destroyCity'])
            ->name('admin.cities.destroy');

        });

    Route::middleware(['auth:admin', 'admin.permission:manage_categories'])->group(function () {
        Route::get('/categories', [CategoryManagementController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/create', [CategoryManagementController::class, 'createCategory'])->name('admin.categories.create');
        Route::get('/categories/{category}', [CategoryManagementController::class, 'showCategory'])->name('admin.categories.show');
        Route::post('/categories', [CategoryManagementController::class, 'storeCategory'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [CategoryManagementController::class, 'editCategory'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [CategoryManagementController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryManagementController::class, 'deleteCategory'])->name('admin.categories.delete');

        Route::get('/categories/{category}/subcategories/create', [CategoryManagementController::class, 'createSubcategory'])
            ->name('admin.categories.subcategories.create');

        Route::post('/categories/{category}/subcategories', [CategoryManagementController::class, 'storeSubcategory'])
            ->name('admin.categories.subcategories.store');

        Route::get('/subcategories/{subcategory}', [CategoryManagementController::class, 'showSubcategory'])
            ->name('admin.categories.subcategories.show');

        Route::get('/subcategories/{subcategory}/edit', [CategoryManagementController::class, 'editSubcategory'])
            ->name('admin.categories.subcategories.edit');

        Route::put('/subcategories/{subcategory}', [CategoryManagementController::class, 'updateSubcategory'])
            ->name('admin.categories.subcategories.update');

        Route::delete('/subcategories/{subcategory}', [CategoryManagementController::class, 'deleteSubcategory'])
            ->name('admin.categories.subcategories.delete');

        Route::get('/subcategories/{subcategory}/fields/create', [CategoryManagementController::class, 'createField'])
            ->name('admin.categories.fields.create');

        Route::post('/subcategories/{subcategory}/fields', [CategoryManagementController::class, 'storeField'])
            ->name('admin.categories.fields.store');

        Route::get('/fields/{field}/edit', [CategoryManagementController::class, 'editField'])
            ->name('admin.categories.fields.edit');

        Route::put('/fields/{field}', [CategoryManagementController::class, 'updateField'])
            ->name('admin.categories.fields.update');

        Route::delete('/fields/{field}', [CategoryManagementController::class, 'deleteField'])
            ->name('admin.categories.fields.delete');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_items'])->group(function () {
        Route::get('/items', [ItemManagementController::class, 'index'])->name('admin.items.index');
        Route::get('/items/{item}', [ItemManagementController::class, 'show'])->name('admin.items.show');
        Route::put('/items/{item}/status', [ItemManagementController::class, 'updateStatus'])->name('admin.items.update-status');
        Route::delete('/items/{item}', [ItemManagementController::class, 'destroy'])->name('admin.items.destroy');
    });

    Route::middleware(['auth:admin', 'admin.permission:manage_sliders'])->group(function () {
        Route::get('/sliders', [ItemSliderController::class, 'index'])->name('admin.sliders.index');
        Route::get('/sliders/{slider}', [ItemSliderController::class, 'show'])->name('admin.sliders.show');
        Route::put('/sliders/{slider}', [ItemSliderController::class, 'update'])->name('admin.sliders.update');
        Route::put('/sliders/{slider}/toggle-active', [ItemSliderController::class, 'toggleActive'])->name('admin.sliders.toggle-active');
        Route::delete('/sliders/{slider}', [ItemSliderController::class, 'destroy'])->name('admin.sliders.destroy');
    });

    Route::get('/locale/{locale}', function (string $locale) {
        $supportedLocales = ['en', 'ar'];

        if (! in_array($locale, $supportedLocales, true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        return back();
    })->name('locale.switch');
});
