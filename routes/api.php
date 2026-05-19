<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\BusinessAccount\BusinessAccountController;
use App\Http\Controllers\Api\Offering\OfferingController;
use App\Http\Controllers\Api\BusinessContext\BusinessContextController;
use App\Http\Controllers\Api\BusinessType\BusinessTypeController;
use App\Http\Controllers\Api\ServiceListing\ServiceListingController;
use App\Http\Controllers\Api\ListingRequest\ListingRequestController;
use App\Http\Controllers\Api\Notification\DeviceTokenController;
use App\Http\Controllers\Api\Notification\FirebaseNotificationController;
use App\Http\Controllers\Api\Notification\AppNotificationController;
use App\Http\Controllers\Api\Chat\ChatController;
use App\Http\Controllers\Api\Item\ItemController;
use App\Http\Controllers\Api\ItemRequest\BuyerItemRequestController;
use App\Http\Controllers\Api\ItemRequest\SellerItemRequestController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Payment\StripeWebhookController;

// webhook (outside auth)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// UserAuth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

// business context
Route::middleware('auth:api')->prefix('business-context')->group(function () {
    Route::get('/approved-business-accounts', [BusinessContextController::class, 'approvedBusinessAccounts']);
    Route::post('/switch', [BusinessContextController::class, 'switch']);
    Route::get('/current', [BusinessContextController::class, 'current']);
    Route::delete('/clear', [BusinessContextController::class, 'clear']);
});

Route::middleware('auth:api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::prefix('payments')->group(function () {
        Route::get('/current-plan', [PaymentController::class, 'currentPlan']);
        Route::get('/my-plan', [PaymentController::class, 'myPlan']);
        Route::post('/checkout', [PaymentController::class, 'checkout']);
        Route::get('/success', [PaymentController::class, 'success']);
        Route::get('/cancel', [PaymentController::class, 'cancel']);
    });

    Route::get('/business-types', [BusinessTypeController::class, 'index']);

    Route::prefix('business-accounts')->group(function () {
        Route::get('/', [BusinessAccountController::class, 'index']);
        Route::post('/', [BusinessAccountController::class, 'store']);
        Route::delete('/{businessAccount}', [BusinessAccountController::class, 'destroy']);
    });

    // services
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

    // listing requests
    Route::prefix('listing-requests')->group(function () {
        Route::post('/', [ListingRequestController::class, 'store']);

        Route::get('/seller', [ListingRequestController::class, 'sellerRequests']);
        Route::get('/seller/listings/{serviceListing}', [ListingRequestController::class, 'sellerRequestsForListing']);
        Route::put('/{listingRequest}/status', [ListingRequestController::class, 'updateStatus']);

        Route::get('/buyer', [ListingRequestController::class, 'buyerRequests']);
        Route::get('/buyer/by-seller', [ListingRequestController::class, 'buyerRequestsBySeller']);

        Route::post('/{listingRequest}/rating', [ListingRequestController::class, 'storeRating']);
    });

    // notification
    Route::prefix('device-tokens')->group(function () {
        Route::post('/', [DeviceTokenController::class, 'store']);
        Route::delete('/', [DeviceTokenController::class, 'destroy']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [AppNotificationController::class, 'index']);
        Route::get('/unread-count', [AppNotificationController::class, 'unreadCount']);
        Route::put('/{notification}/read', [AppNotificationController::class, 'markAsRead']);
        Route::put('/read-all', [AppNotificationController::class, 'markAllAsRead']);

        Route::post('/send-to-me', [FirebaseNotificationController::class, 'sendToAuthenticatedUser']);
        Route::post('/send-to-user/{userId}', [FirebaseNotificationController::class, 'sendToUserById']);
    });

    // chat
    Route::prefix('chat')->group(function () {
        Route::get('/conversations', [ChatController::class, 'conversations']);
        Route::post('/conversations/users/{userId}', [ChatController::class, 'startConversation']);
        Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'messages']);
        Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage']);
        Route::put('/conversations/{conversationId}/read', [ChatController::class, 'markAsRead']);
    });

    // items
    Route::get('/item-categories', [ItemController::class, 'categories']);
    Route::get('/item-categories/{categoryId}/subcategories', [ItemController::class, 'subcategories']);

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{item}', [ItemController::class, 'show']);
        Route::put('/{item}', [ItemController::class, 'update']);
        Route::delete('/{item}', [ItemController::class, 'destroy']);

        Route::post('/{item}/sub-photos', [ItemController::class, 'addSubPhotos']);
        Route::post('/{item}/main-photo', [ItemController::class, 'replaceMainPhoto']);
    });

    // buyer item requests
    Route::prefix('item-requests')->group(function () {
        Route::post('/', [BuyerItemRequestController::class, 'store']);
        Route::get('/my', [BuyerItemRequestController::class, 'myRequests']);
        Route::get('/my/search-by-seller-business-account', [BuyerItemRequestController::class, 'searchBySellerBusinessAccount']);
        Route::post('/{itemRequestId}/rate', [BuyerItemRequestController::class, 'rate']);
    });

    // seller item requests
    Route::prefix('seller/item-requests')->group(function () {
        Route::get('/', [SellerItemRequestController::class, 'receivedRequests']);
        Route::get('/search-by-buyer-business-account', [SellerItemRequestController::class, 'searchByBuyerBusinessAccount']);
        Route::put('/{itemRequestId}/status', [SellerItemRequestController::class, 'updateStatus']);
    });
});
