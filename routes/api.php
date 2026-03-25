<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\BusinessAccount\BusinessAccountController;
use App\Http\Controllers\Api\Offering\OfferingController;
use App\Http\Controllers\Api\BusinessContext\BusinessContextController;

// UserAuth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

//Business account routes
Route::middleware('auth:api')->prefix('business-accounts')->group(function () {
    Route::get('/', [BusinessAccountController::class, 'index']);
    Route::post('/', [BusinessAccountController::class, 'store']);
    Route::delete('/{businessAccount}', [BusinessAccountController::class, 'destroy']);

});

//offerings
Route::middleware('auth:api')->prefix('offerings')->group(function () {
    Route::get('/', [OfferingController::class, 'index']);
    Route::get('/active-business-account', [OfferingController::class, 'activeBusinessAccountOfferings']);
    Route::post('/', [OfferingController::class, 'store']);
    Route::put('/{offering}', [OfferingController::class, 'update']);
    Route::delete('/{offering}', [OfferingController::class, 'destroy']);
});


//business context (switching)
Route::middleware('auth:api')->prefix('business-context')->group(function () {
    Route::get('/approved-business-accounts', [BusinessContextController::class, 'approvedBusinessAccounts']);
    Route::post('/switch', [BusinessContextController::class, 'switch']);
    Route::get('/current', [BusinessContextController::class, 'current']);
    Route::delete('/clear', [BusinessContextController::class, 'clear']);

});
