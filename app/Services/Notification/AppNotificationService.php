<?php

namespace App\Services\Notification;

use App\Models\Item;
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

    public function sendItemAccepted(Item $item): array
    {
        return $this->notifyUser(
            $item->businessAccount->user,
            'Item Accepted',
            'Your item "' . $item->title . '" has been accepted.',
            [
                'type' => 'item_accepted',
                'item_id' => $item->id,
            ]
        );
    }

    public function sendItemRejected(Item $item): array
    {
        return $this->notifyUser(
            $item->businessAccount->user,
            'Item Rejected',
            'Your item "' . $item->title . '" has been rejected.',
            [
                'type' => 'item_rejected',
                'item_id' => $item->id,
            ]
        );
    }

    public function sendNewItemRequest(Item $item, User $buyer): array
    {
        return $this->notifyUser(
            $item->businessAccount->user,
            'New Item Request',
            $buyer->full_name . ' requested your item "' . $item->title . '".',
            [
                'type' => 'new_item_request',
                'item_id' => $item->id,
                'buyer_id' => $buyer->id,
            ]
        );
    }

    public function sendItemRequestInProgress(User $buyer, Item $item): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Accepted',
            'Your request for "' . $item->title . '" is now in progress.',
            [
                'type' => 'item_request_in_progress',
                'item_id' => $item->id,
            ]
        );
    }

    public function sendItemRequestRejected(User $buyer, Item $item): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Rejected',
            'Your request for "' . $item->title . '" has been rejected.',
            [
                'type' => 'item_request_rejected',
                'item_id' => $item->id,
            ]
        );
    }

    public function sendItemRequestCompleted(User $buyer, Item $item): array
    {
        return $this->notifyUser(
            $buyer,
            'Request Completed',
            'Your request for "' . $item->title . '" has been completed.',
            [
                'type' => 'item_request_completed',
                'item_id' => $item->id,
            ]
        );
    }


// the old notification system for the listing managment
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
