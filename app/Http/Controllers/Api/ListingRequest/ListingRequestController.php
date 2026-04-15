<?php

namespace App\Http\Controllers\Api\ListingRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ListingRequest\ListingRequestService;
use App\Http\Requests\ListingRequest\StoreListingRequestRequest;
use App\Http\Requests\ListingRequest\StoreListingRequestRatingRequest;
use App\Http\Requests\ListingRequest\UpdateListingRequestStatusRequest;

class ListingRequestController extends Controller
{
    public function __construct(
        private ListingRequestService $listingRequestService
    ) {}

    public function store(StoreListingRequestRequest $request): JsonResponse
    {
        $listingRequest = $this->listingRequestService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Listing request created successfully.',
            'listing_request' => $listingRequest,
        ], 201);
    }

    public function sellerRequests(Request $request): JsonResponse
    {
        $listingRequests = $this->listingRequestService->getSellerRequests($request->user());

        return response()->json([
            'listing_requests' => $listingRequests,
        ]);
    }

    public function sellerRequestsForListing(Request $request, int $serviceListing): JsonResponse
    {
        $listingRequests = $this->listingRequestService->getSellerRequestsForListing(
            $request->user(),
            $serviceListing
        );

        return response()->json([
            'listing_requests' => $listingRequests,
        ]);
    }

    public function updateStatus(
        UpdateListingRequestStatusRequest $request,
        int $listingRequest
    ): JsonResponse {
        $updatedListingRequest = $this->listingRequestService->updateStatus(
            $request->user(),
            $listingRequest,
            $request->validated()
        );

        return response()->json([
            'message' => 'Listing request status updated successfully.',
            'listing_request' => $updatedListingRequest,
        ]);
    }

    public function buyerRequests(Request $request): JsonResponse
    {
        $listingRequests = $this->listingRequestService->getBuyerRequests(
            $request->user(),
            $request->query('search')
        );

        return response()->json([
            'listing_requests' => $listingRequests,
        ]);
    }

    public function buyerRequestsBySeller(Request $request): JsonResponse
    {
        $sellerBusinessAccountName = trim((string) $request->query('seller_business_account_name'));

        if ($sellerBusinessAccountName === '') {
            return response()->json([
                'message' => 'seller_business_account_name is required.',
            ], 422);
        }

        $listingRequests = $this->listingRequestService->getBuyerRequestsBySellerBusinessAccountName(
            $request->user(),
            $sellerBusinessAccountName
        );

        return response()->json([
            'listing_requests' => $listingRequests,
        ]);
    }

    public function storeRating(
        StoreListingRequestRatingRequest $request,
        int $listingRequest
    ): JsonResponse {
        $rating = $this->listingRequestService->createRating(
            $request->user(),
            $listingRequest,
            $request->validated()
        );

        return response()->json([
            'message' => 'Listing request rated successfully.',
            'rating' => $rating,
        ], 201);
    }
}
