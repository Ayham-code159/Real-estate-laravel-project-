<?php

namespace App\Http\Controllers\Api\ItemRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ItemRequest\ItemRequestService;
use App\Http\Requests\ItemRequest\UpdateItemRequestStatusRequest;

class SellerItemRequestController extends Controller
{
    public function __construct(
        private ItemRequestService $itemRequestService
    ) {}

    public function receivedRequests(Request $request): JsonResponse
    {
        $requests = $this->itemRequestService->getSellerRequests(
            $request->user(),
            $request->query('search')
        );

        return response()->json([
            'message' => 'seller_item_requests.retrieved',
            'data' => $requests,
        ]);
    }

    public function searchByBuyerBusinessAccount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buyer_business_account_name' => ['required', 'string', 'max:255'],
        ]);

        $requests = $this->itemRequestService->getSellerRequestsByBuyerBusinessAccount(
            $request->user(),
            $validated['buyer_business_account_name']
        );

        return response()->json([
            'message' => 'seller_item_requests.retrieved',
            'data' => $requests,
        ]);
    }

    public function updateStatus(UpdateItemRequestStatusRequest $request, int $itemRequestId): JsonResponse
    {
        $itemRequest = $this->itemRequestService->updateSellerRequestStatus(
            $request->user(),
            $itemRequestId,
            $request->validated()
        );

        return response()->json([
            'message' => 'item_request.status_updated',
            'data' => $itemRequest,
        ]);
    }
}
