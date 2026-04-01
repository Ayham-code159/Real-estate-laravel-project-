<?php

namespace App\Http\Controllers\Admin\ServiceListing;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\ServiceListing\AdminServiceListingManagementService;
use App\Http\Requests\Admin\ServiceListing\UpdateServiceListingStatusRequest;

class AdminServiceListingManagementController extends Controller
{
    public function __construct(
        private AdminServiceListingManagementService $adminServiceListingManagementService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $mode = $request->query('mode');

        $serviceListings = $this->adminServiceListingManagementService->getPaginatedListings($search, $mode);
        $counts = $this->adminServiceListingManagementService->getListingCounts();

        return view('admin.service-listings.index', compact('serviceListings', 'counts', 'search', 'mode'));
    }

    public function show(int $serviceListing)
    {
        $serviceListing = $this->adminServiceListingManagementService->getListingDetails($serviceListing);

        return view('admin.service-listings.show', compact('serviceListing'));
    }

    public function updateStatus(
        UpdateServiceListingStatusRequest $request,
        int $serviceListing
    ): RedirectResponse {
        $this->adminServiceListingManagementService->updateListingStatus(
            $serviceListing,
            $request->validated()
        );

        return redirect()
            ->route('admin.service-listings.show', $serviceListing)
            ->with('success', 'Service listing status updated successfully.');
    }
}
