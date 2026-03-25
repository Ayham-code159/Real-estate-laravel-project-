<?php

namespace App\Http\Controllers\Api\Offering;

use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Offering\OfferingService;
use App\Http\Requests\Offering\StoreOfferingRequest;
use App\Http\Requests\Offering\UpdateOfferingRequest;

class OfferingController extends Controller
{
    public function __construct(
        private OfferingService $offeringService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $offerings = $this->offeringService->listForUser($request->user());

        return response()->json([
            'offerings' => $offerings,
        ]);
    }

    public function store(StoreOfferingRequest $request): JsonResponse
    {
        $offering = $this->offeringService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Offering created successfully.',
            'offering' => $offering->load(['businessAccount.businessType', 'businessAccount.city']),
        ], 201);
    }

    public function update(UpdateOfferingRequest $request, Offering $offering): JsonResponse
    {
        $updatedOffering = $this->offeringService->update(
            $request->user(),
            $offering,
            $request->validated()
        );

        return response()->json([
            'message' => 'Offering updated successfully.',
            'offering' => $updatedOffering,
        ]);
    }

    public function destroy(Request $request, Offering $offering): JsonResponse
    {
        $this->offeringService->delete($request->user(), $offering);

        return response()->json([
            'message' => 'Offering deleted successfully.',
        ]);
    }

    public function activeBusinessAccountOfferings(Request $request): JsonResponse
    {
        $offerings = $this->offeringService->listForActiveBusinessAccount($request->user());
        return response()->json([
            'offerings'=>$offerings,

        ]);
    }
}
