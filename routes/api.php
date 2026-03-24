<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\BusinessAccount\BusinessAccountController;


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




