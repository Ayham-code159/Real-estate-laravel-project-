<?php

namespace App\Http\Controllers\Api\ItemRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ItemRequest\ItemRequestService;
use App\Http\Requests\ItemRequest\StoreItemRatingRequest;
use App\Http\Requests\ItemRequest\StoreItemRequestRequest;

class BuyerItemRequestController extends Controller
{
    public function __construct(
        private ItemRequestService $itemRequestService
    ) {}

    public function store(StoreItemRequestRequest $request): JsonResponse
    {
        $itemRequest = $this->itemRequestService->createBuyerRequest(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'item_request.created',
            'data' => $itemRequest,
        ], 201);
    }

    public function myRequests(Request $request): JsonResponse
    {
        $requests = $this->itemRequestService->getBuyerRequests(
            $request->user(),
            $request->query('search')
        );

        return response()->json([
            'message' => 'item_requests.retrieved',
            'data' => $requests,
        ]);
    }

    public function searchBySellerBusinessAccount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seller_business_account_name' => ['required', 'string', 'max:255'],
        ]);

        $requests = $this->itemRequestService->getBuyerRequestsBySellerBusinessAccount(
            $request->user(),
            $validated['seller_business_account_name']
        );

        return response()->json([
            'message' => 'item_requests.retrieved',
            'data' => $requests,
        ]);
    }

    public function rate(StoreItemRatingRequest $request, int $itemRequestId): JsonResponse
    {
        $rating = $this->itemRequestService->createRating(
            $request->user(),
            $itemRequestId,
            $request->validated()
        );

        return response()->json([
            'message' => 'item_request.rated',
            'data' => $rating,
        ], 201);
    }
}
