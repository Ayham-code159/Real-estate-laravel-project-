<?php

namespace App\Services\Admin\ServiceListing;

use App\Models\ServiceListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminServiceListingManagementService
{
    public function getPaginatedListings(?string $search = null, ?string $mode = null): LengthAwarePaginator
    {
        return ServiceListing::query()
            ->with([
                'service',
                'subcategory',
                'businessAccount.user',
                'businessAccount.businessType',
                'businessAccount.city',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . trim($search) . '%');
            })
            ->when($mode, function ($query) use ($mode) {
                $query->where('mode', $mode);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function getListingCounts(): array
    {
        return [
            'total' => ServiceListing::count(),
            'pending' => ServiceListing::where('status', ServiceListing::STATUS_PENDING)->count(),
            'approved' => ServiceListing::where('status', ServiceListing::STATUS_APPROVED)->count(),
            'rejected' => ServiceListing::where('status', ServiceListing::STATUS_REJECTED)->count(),
        ];
    }

    public function getListingDetails(int $listingId): ServiceListing
    {
        return ServiceListing::query()
            ->with([
                'service',
                'subcategory',
                'businessAccount.user',
                'businessAccount.businessType',
                'businessAccount.city',
            ])
            ->findOrFail($listingId);
    }

    public function updateListingStatus(int $listingId, array $data): ServiceListing
    {
        $listing = ServiceListing::query()->findOrFail($listingId);

        $listing->status = (int) $data['status'];

        if ($listing->status === ServiceListing::STATUS_REJECTED) {
            $listing->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $listing->rejection_reason = null;
        }

        $listing->save();

        return $listing->fresh([
            'service',
            'subcategory',
            'businessAccount.user',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }
}
