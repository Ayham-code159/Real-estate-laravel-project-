<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\BusinessAccount\BusinessAccountController;
use App\Http\Controllers\Api\Offering\OfferingController;
use App\Http\Controllers\Api\BusinessContext\BusinessContextController;
use App\Http\Controllers\Api\BusinessType\BusinessTypeController;
use App\Http\Controllers\Api\ServiceListing\ServiceListingController;
use App\Http\Controllers\Api\ListingRequest\ListingRequestController;

// UserAuth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

// business context (switching)
Route::middleware('auth:api')->prefix('business-context')->group(function () {
    Route::get('/approved-business-accounts', [BusinessContextController::class, 'approvedBusinessAccounts']);
    Route::post('/switch', [BusinessContextController::class, 'switch']);
    Route::get('/current', [BusinessContextController::class, 'current']);
    Route::delete('/clear', [BusinessContextController::class, 'clear']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/business-types', [BusinessTypeController::class, 'index']);

    Route::prefix('business-accounts')->group(function () {
        Route::get('/', [BusinessAccountController::class, 'index']);
        Route::post('/', [BusinessAccountController::class, 'store']);
        Route::delete('/{businessAccount}', [BusinessAccountController::class, 'destroy']);
    });
});

// services
Route::middleware('auth:api')->group(function () {
    Route::get('/services', [ServiceListingController::class, 'services']);
    Route::get('/services/{service}/subcategories', [ServiceListingController::class, 'subcategories']);

    Route::prefix('service-listings')->group(function () {
        Route::get('/', [ServiceListingController::class, 'index']);
        Route::get('/active-business-account', [ServiceListingController::class, 'activeBusinessAccountListings']);
        Route::post('/', [ServiceListingController::class, 'store']);
        Route::put('/{serviceListing}', [ServiceListingController::class, 'update']);
        Route::delete('/{serviceListing}', [ServiceListingController::class, 'destroy']);
        Route::post('/{serviceListing}/sub-photos', [ServiceListingController::class, 'addSubPhotos']);
        Route::post('/{serviceListing}/main-photo', [ServiceListingController::class, 'replaceMainPhoto']);
        Route::delete('/{serviceListing}/sub-photos/{mediaId}', [ServiceListingController::class, 'deleteSubPhoto']);
    });
});

// listing requests
Route::middleware('auth:api')->prefix('listing-requests')->group(function () {
    Route::post('/', [ListingRequestController::class, 'store']);

    Route::get('/seller', [ListingRequestController::class, 'sellerRequests']);
    Route::get('/seller/listings/{serviceListing}', [ListingRequestController::class, 'sellerRequestsForListing']);
    Route::put('/{listingRequest}/status', [ListingRequestController::class, 'updateStatus']);

    Route::get('/buyer', [ListingRequestController::class, 'buyerRequests']);
    Route::get('/buyer/by-seller', [ListingRequestController::class, 'buyerRequestsBySeller']);

    Route::post('/{listingRequest}/rating', [ListingRequestController::class, 'storeRating']);
});
