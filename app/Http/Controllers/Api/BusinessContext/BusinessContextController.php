<?php

namespace App\Http\Controllers\Api\BusinessContext;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\BusinessContext\BusinessContextService;
use App\Http\Requests\BusinessContext\SwitchBusinessAccountRequest;

class BusinessContextController extends Controller
{
    public function __construct(
        private BusinessContextService $businessContextService
    ) {}

    public function approvedBusinessAccounts(Request $request): JsonResponse
    {
        $businessAccounts = $this->businessContextService->listApprovedBusinessAccounts($request->user());

        return response()->json([
            'business_accounts' => $businessAccounts,
        ]);
    }

    public function switch(SwitchBusinessAccountRequest $request): JsonResponse
    {
        $businessAccount = $this->businessContextService->switchActiveBusinessAccount(
            $request->user(),
            (int) $request->validated()['business_account_id']
        );

        return response()->json([
            'message' => 'Active business account selected successfully.',
            'active_business_account' => $businessAccount,
        ]);
    }

    public function current(Request $request): JsonResponse
    {
        $activeBusinessAccount = $this->businessContextService->getActiveBusinessAccount($request->user());

        return response()->json([
            'active_business_account' => $activeBusinessAccount,
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->businessContextService->clearActiveBusinessAccount($request->user());

        return response()->json([
            'message' => 'Active business account cleared successfully.',
        ]);
    }
}
