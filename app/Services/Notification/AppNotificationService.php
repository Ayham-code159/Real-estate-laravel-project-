<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Models\AppNotification;
use App\Models\BusinessAccount;
use App\Models\ServiceListing;

class AppNotificationService
{
    public function __construct(
        private FirebaseNotificationService $firebaseNotificationService
    ) {}

    private function notifyUser(User $user, string $title, string $body, array $data = []): array
    {
        AppNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $data['type'] ?? null,
            'data' => $data,
        ]);

        return $this->firebaseNotificationService->sendToUser(
            $user,
            $title,
            $body,
            $data
        );
    }

    public function sendBusinessAccountAccepted(BusinessAccount $businessAccount): array
    {
        return $this->notifyUser(
            $businessAccount->user,
            'Business Account Accepted',
            'Your business account "' . $businessAccount->business_name . '" has been accepted.',
            [
                'type' => 'business_account_accepted',
                'business_account_id' => $businessAccount->id,
            ]
        );
    }

    public function sendBusinessAccountRejected(BusinessAccount $businessAccount): array
    {
        return $this->notifyUser(
            $businessAccount->user,
            'Business Account Rejected',
            'Your business account "' . $businessAccount->business_name . '" has been rejected.',
            [
                'type' => 'business_account_rejected',
                'business_account_id' => $businessAccount->id,
            ]
        );
    }

    public function sendListingAccepted(ServiceListing $listing): array
    {
        return $this->notifyUser(
            $listing->businessAccount->user,
            'Listing Accepted',
            'Your listing "' . $listing->title . '" has been accepted.',
            [
                'type' => 'listing_accepted',
                'listing_id' => $listing->id,
            ]
        );
    }

    public function sendListingRejected(ServiceListing $listing): array
    {
        return $this->notifyUser(
            $listing->businessAccount->user,
            'Listing Rejected',
            'Your listing "' . $listing->title . '" has been rejected.',
            [
                'type' => 'listing_rejected',
                'listing_id' => $listing->id,
            ]
        );
    }

    public function sendNewListingRequest(ServiceListing $listing, User $buyer): array
    {
        return $this->notifyUser(
            $listing->businessAccount->user,
            'New Listing Request',
            $buyer->full_name . ' requested your listing "' . $listing->title . '".',
            [
                'type' => 'new_listing_request',
                'listing_id' => $listing->id,
                'buyer_id' => $buyer->id,
            ]
        );
    }

    public function sendListingRequestAccepted(User $buyer, ServiceListing $listing): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Accepted',
            'Your request for "' . $listing->title . '" has been accepted.',
            [
                'type' => 'listing_request_accepted',
                'listing_id' => $listing->id,
            ]
        );
    }

    public function sendListingRequestRejected(User $buyer, ServiceListing $listing): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Rejected',
            'Your request for "' . $listing->title . '" has been rejected.',
            [
                'type' => 'listing_request_rejected',
                'listing_id' => $listing->id,
            ]
        );
    }

    public function sendListingRequestCompleted(User $buyer, ServiceListing $listing): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Completed',
            'Your request for "' . $listing->title . '" has been completed.',
            [
                'type' => 'listing_request_completed',
                'listing_id' => $listing->id,
            ]
        );
    }
}
