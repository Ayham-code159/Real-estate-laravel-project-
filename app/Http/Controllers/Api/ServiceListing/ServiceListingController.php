<?php

namespace App\Http\Controllers\Api\ServiceListing;

use App\Models\ServiceListing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ServiceListing\ServiceListingService;
use App\Http\Requests\ServiceListing\StoreServiceListingRequest;
use App\Http\Requests\ServiceListing\StoreServiceListingImagesRequest;
use App\Http\Requests\ServiceListing\UpdateServiceListingRequest;
use App\Http\Requests\ServiceListing\UpdateServiceListingMainImageRequest;

class ServiceListingController extends Controller
{
    public function __construct(
        private ServiceListingService $serviceListingService
    ) {}

    public function services(): JsonResponse
    {
        $services = $this->serviceListingService->getAllServices();

        return response()->json([
            'services' => $services,
        ]);
    }

    public function subcategories(int $service): JsonResponse
    {
        $subcategories = $this->serviceListingService->getSubcategoriesByService($service);

        return response()->json([
            'service_subcategories' => $subcategories,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $serviceListings = $this->serviceListingService->listForUser($request->user());

        return response()->json([
            'service_listings' => $serviceListings,
        ]);
    }

    public function activeBusinessAccountListings(Request $request): JsonResponse
    {
        $serviceListings = $this->serviceListingService->listForActiveBusinessAccount($request->user());

        return response()->json([
            'service_listings' => $serviceListings,
        ]);
    }

    public function store(StoreServiceListingRequest $request): JsonResponse
    {
        $serviceListing = $this->serviceListingService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Service listing created successfully.',
            'service_listing' => $serviceListing,
        ], 201);
    }

    public function update(
        UpdateServiceListingRequest $request,
        ServiceListing $serviceListing
    ): JsonResponse {
        $updatedServiceListing = $this->serviceListingService->update(
            $request->user(),
            $serviceListing,
            $request->validated()
        );

        return response()->json([
            'message' => 'Service listing updated successfully.',
            'service_listing' => $updatedServiceListing,
        ]);
    }

    public function destroy(Request $request, ServiceListing $serviceListing): JsonResponse
    {
        $this->serviceListingService->delete($request->user(), $serviceListing);

        return response()->json([
            'message' => 'Service listing deleted successfully.',
        ]);
    }

    public function addSubPhotos(
        StoreServiceListingImagesRequest $request,
        ServiceListing $serviceListing
    ): JsonResponse {
        $updatedServiceListing = $this->serviceListingService->addSubPhotos(
            $request->user(),
            $serviceListing,
            $request->validated()['sub_photos']
        );

        return response()->json([
            'message' => 'Sub photos added successfully.',
            'service_listing' => $updatedServiceListing,
        ]);
    }

    public function replaceMainPhoto(
        UpdateServiceListingMainImageRequest $request,
        ServiceListing $serviceListing
    ): JsonResponse {
        $updatedServiceListing = $this->serviceListingService->replaceMainPhoto(
            $request->user(),
            $serviceListing,
            $request->validated()['main_photo']
        );

        return response()->json([
            'message' => 'Main photo updated successfully.',
            'service_listing' => $updatedServiceListing,
        ]);
    }

    public function deleteSubPhoto(
        Request $request,
        ServiceListing $serviceListing,
        int $mediaId
    ): JsonResponse {
        $updatedServiceListing = $this->serviceListingService->deleteSubPhoto(
            $request->user(),
            $serviceListing,
            $mediaId
        );

        return response()->json([
            'message' => 'Sub photo deleted successfully.',
            'service_listing' => $updatedServiceListing,
        ]);
    }
}
