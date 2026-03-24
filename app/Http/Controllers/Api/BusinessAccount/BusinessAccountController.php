<?php

namespace App\Http\Controllers\Api\BusinessAccount;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\BusinessAccount\BusinessAccountService;
use App\Http\Requests\BusinessAccount\StoreBusinessAccountRequest;

class BusinessAccountController extends Controller
{
    public function __construct(
        private BusinessAccountService $businessAccountService
    ) {}

    public function store(StoreBusinessAccountRequest $request): JsonResponse
    {
        $businessAccount = $this->businessAccountService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Business account created successfully.',
            'business_account' => $businessAccount->load(['businessType', 'city']),
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $businessAccounts = $this->businessAccountService->listForUser($request->user());

        return response()->json([
            'business_accounts' => $businessAccounts,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->businessAccountService->delete($request->user(), $id);

        return response()->json([
            'message' => 'Business account deleted successfully.',
        ]);
    }
}
