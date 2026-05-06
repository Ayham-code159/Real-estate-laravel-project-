<?php

namespace App\Services\ListingRequest;

use App\Models\User;
use App\Models\BusinessAccount;
use App\Models\ListingRequest;
use App\Models\ServiceListing;
use App\Models\ListingRequestRating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use App\Services\Notification\AppNotificationService;

class ListingRequestService
{
    public function __construct(
        private AppNotificationService $appNotificationService
    ) {}

    public function create(User $buyer, array $data): ListingRequest
    {
        $buyerBusinessAccount = $this->getApprovedActiveBusinessAccount($buyer);

        $serviceListing = ServiceListing::query()
            ->with([
                'businessAccount.user',
                'businessAccount.businessType',
                'businessAccount.city',
                'service',
                'subcategory',
            ])
            ->findOrFail((int) $data['service_listing_id']);

        if (! $serviceListing->isApproved()) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['You can only request approved listings.'],
            ]);
        }

        $sellerBusinessAccount = $serviceListing->businessAccount;

        if (! $sellerBusinessAccount) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['The selected listing does not have a valid seller business account.'],
            ]);
        }

        $sellerUser = $sellerBusinessAccount->user;

        if (! $sellerUser) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['The selected listing does not have a valid seller user.'],
            ]);
        }

        if ($sellerUser->id === $buyer->id) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['You cannot request your own listing.'],
            ]);
        }

        $this->ensureNoDuplicateOpenRequest(
            $serviceListing->id,
            $buyerBusinessAccount->id
        );

        $listingRequest = ListingRequest::create([
            'service_listing_id' => $serviceListing->id,
            'buyer_user_id' => $buyer->id,
            'buyer_business_account_id' => $buyerBusinessAccount->id,
            'seller_user_id' => $sellerUser->id,
            'seller_business_account_id' => $sellerBusinessAccount->id,
            'requested_for' => $data['requested_for'],
            'description' => trim($data['description']),
            'request_metadata' => $data['request_metadata'] ?? null,
            'price_usd_snapshot' => (float) $serviceListing->price_usd,
            'price_syp_snapshot' => (float) $serviceListing->price_syp,
            'status' => ListingRequest::STATUS_PENDING,
        ]);

        $this->appNotificationService->sendNewListingRequest($serviceListing, $buyer);

        return $listingRequest->fresh([
            'serviceListing.service',
            'serviceListing.subcategory',
            'buyerUser',
            'buyerBusinessAccount.businessType',
            'buyerBusinessAccount.city',
            'sellerUser',
            'sellerBusinessAccount.businessType',
            'sellerBusinessAccount.city',
            'rating',
        ]);
    }

    public function getSellerRequests(User $seller): Collection
    {
        return ListingRequest::query()
            ->with([
                'serviceListing.service',
                'serviceListing.subcategory',
                'buyerUser',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'rating',
            ])
            ->where('seller_user_id', $seller->id)
            ->latest()
            ->get();
    }

    public function getSellerRequestsForListing(User $seller, int $serviceListingId): Collection
    {
        $serviceListing = ServiceListing::query()
            ->with('businessAccount')
            ->findOrFail($serviceListingId);

        if (! $serviceListing->businessAccount || $serviceListing->businessAccount->user_id !== $seller->id) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['This listing does not belong to you.'],
            ]);
        }

        return ListingRequest::query()
            ->with([
                'serviceListing.service',
                'serviceListing.subcategory',
                'buyerUser',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'rating',
            ])
            ->where('seller_user_id', $seller->id)
            ->where('service_listing_id', $serviceListingId)
            ->latest()
            ->get();
    }

    public function updateStatus(User $seller, int $listingRequestId, array $data): ListingRequest
    {
        $listingRequest = ListingRequest::query()
            ->with([
                'serviceListing.businessAccount',
                'serviceListing.service',
                'serviceListing.subcategory',
                'buyerUser',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerUser',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'rating',
            ])
            ->findOrFail($listingRequestId);

        if ($listingRequest->seller_user_id !== $seller->id) {
            throw ValidationException::withMessages([
                'listing_request' => ['This request does not belong to you as a seller.'],
            ]);
        }

        $oldStatus = $listingRequest->status;
        $newStatus = (int) $data['status'];

        $this->ensureStatusTransitionIsAllowed($listingRequest, $newStatus);

        $listingRequest->status = $newStatus;
        $listingRequest->seller_response_note = $data['seller_response_note'] ?? null;

        if ($newStatus === ListingRequest::STATUS_ACCEPTED && ! $listingRequest->accepted_at) {
            $listingRequest->accepted_at = now();
        }

        if ($newStatus === ListingRequest::STATUS_REJECTED && ! $listingRequest->rejected_at) {
            $listingRequest->rejected_at = now();
        }

        if ($newStatus === ListingRequest::STATUS_COMPLETED && ! $listingRequest->completed_at) {
            $listingRequest->completed_at = now();
        }

        $listingRequest->save();

        $listingRequest = $listingRequest->fresh([
            'serviceListing.service',
            'serviceListing.subcategory',
            'buyerUser',
            'buyerBusinessAccount.businessType',
            'buyerBusinessAccount.city',
            'sellerUser',
            'sellerBusinessAccount.businessType',
            'sellerBusinessAccount.city',
            'rating',
        ]);

        if ($oldStatus !== $newStatus) {
            if ($newStatus === ListingRequest::STATUS_ACCEPTED) {
                    $this->appNotificationService->sendListingRequestAccepted(
                        $listingRequest->buyerUser,
                        $listingRequest->serviceListing
                    );
                }

        if ($newStatus === ListingRequest::STATUS_REJECTED) {
            $this->appNotificationService->sendListingRequestRejected(
                $listingRequest->buyerUser,
                $listingRequest->serviceListing
            );
        }

        if ($newStatus === ListingRequest::STATUS_COMPLETED) {
            $this->appNotificationService->sendListingRequestCompleted(
                $listingRequest->buyerUser,
                $listingRequest->serviceListing
            );
        }
    }

        return $listingRequest;
    }



    public function getBuyerRequests(User $buyer, ?string $search = null): Collection
    {
        return ListingRequest::query()
            ->with([
                'serviceListing.service',
                'serviceListing.subcategory',
                'sellerUser',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'rating',
            ])
            ->where('buyer_user_id', $buyer->id)
            ->when($search !== null && trim($search) !== '', function ($query) use ($search) {
                $search = trim($search);

                $query->whereHas('serviceListing', function ($listingQuery) use ($search) {
                    $listingQuery->where(function ($innerQuery) use ($search) {
                        $innerQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('title_en', 'like', '%' . $search . '%')
                            ->orWhere('title_ar', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->get();
    }

    public function getBuyerRequestsBySellerBusinessAccountName(User $buyer, string $sellerBusinessAccountName): Collection
    {
        $sellerBusinessAccountName = trim($sellerBusinessAccountName);

        return ListingRequest::query()
            ->with([
                'serviceListing.service',
                'serviceListing.subcategory',
                'sellerUser',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'rating',
            ])
            ->where('buyer_user_id', $buyer->id)
            ->whereHas('sellerBusinessAccount', function ($query) use ($sellerBusinessAccountName) {
                $query->where(function ($innerQuery) use ($sellerBusinessAccountName) {
                    $innerQuery->where('business_name', 'like', '%' . $sellerBusinessAccountName . '%')
                        ->orWhere('business_name_en', 'like', '%' . $sellerBusinessAccountName . '%')
                        ->orWhere('business_name_ar', 'like', '%' . $sellerBusinessAccountName . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function createRating(User $buyer, int $listingRequestId, array $data): ListingRequestRating
    {
        $listingRequest = ListingRequest::query()
            ->with([
                'serviceListing',
                'rating',
            ])
            ->findOrFail($listingRequestId);

        if ($listingRequest->buyer_user_id !== $buyer->id) {
            throw ValidationException::withMessages([
                'listing_request' => ['You can only rate your own completed requests.'],
            ]);
        }

        if (! $listingRequest->isCompleted()) {
            throw ValidationException::withMessages([
                'listing_request' => ['You can only rate a request after it has been completed.'],
            ]);
        }

        if ($listingRequest->rating) {
            throw ValidationException::withMessages([
                'listing_request' => ['This request has already been rated.'],
            ]);
        }

        return ListingRequestRating::create([
            'listing_request_id' => $listingRequest->id,
            'service_listing_id' => $listingRequest->service_listing_id,
            'buyer_user_id' => $buyer->id,
            'rating' => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    private function ensureStatusTransitionIsAllowed(ListingRequest $listingRequest, int $newStatus): void
    {
        if ($listingRequest->isCompleted()) {
            throw ValidationException::withMessages([
                'status' => ['A completed request cannot be updated again.'],
            ]);
        }

        if ($listingRequest->isRejected()) {
            throw ValidationException::withMessages([
                'status' => ['A rejected request cannot be updated again.'],
            ]);
        }

        if ($listingRequest->isCancelled()) {
            throw ValidationException::withMessages([
                'status' => ['A cancelled request cannot be updated again.'],
            ]);
        }

        $currentStatus = $listingRequest->status;

        $allowedTransitions = [
            ListingRequest::STATUS_PENDING => [
                ListingRequest::STATUS_ACCEPTED,
                ListingRequest::STATUS_REJECTED,
            ],
            ListingRequest::STATUS_ACCEPTED => [
                ListingRequest::STATUS_COMPLETED,
                ListingRequest::STATUS_REJECTED,
            ],
        ];

        $allowedNextStatuses = $allowedTransitions[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowedNextStatuses, true)) {
            throw ValidationException::withMessages([
                'status' => ['This status transition is not allowed.'],
            ]);
        }
    }

    private function getApprovedActiveBusinessAccount(User $user): BusinessAccount
    {
        if (! $user->active_business_account_id) {
            throw ValidationException::withMessages([
                'business_account' => ['You do not have an active business account selected.'],
            ]);
        }

        $businessAccount = $user->activeBusinessAccount()
            ->with(['businessType', 'city'])
            ->first();

        if (! $businessAccount) {
            throw ValidationException::withMessages([
                'business_account' => ['Your active business account was not found.'],
            ]);
        }

        if ($businessAccount->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'business_account' => ['The selected active business account does not belong to you.'],
            ]);
        }

        if (! $businessAccount->isApproved()) {
            throw ValidationException::withMessages([
                'business_account' => ['You can only request listings through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function ensureNoDuplicateOpenRequest(int $serviceListingId, int $buyerBusinessAccountId): void
    {
        $exists = ListingRequest::query()
            ->where('service_listing_id', $serviceListingId)
            ->where('buyer_business_account_id', $buyerBusinessAccountId)
            ->whereIn('status', [
                ListingRequest::STATUS_PENDING,
                ListingRequest::STATUS_ACCEPTED,
            ])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'service_listing_id' => ['You already have an open request for this listing from the selected active business account.'],
            ]);
        }
    }
}
