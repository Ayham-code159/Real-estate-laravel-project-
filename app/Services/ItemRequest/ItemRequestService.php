<?php

namespace App\Services\ItemRequest;

use App\Models\Item;
use App\Models\User;
use App\Models\ItemRating;
use App\Models\ItemRequest;
use App\Models\BusinessAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use App\Services\Notification\AppNotificationService;


class ItemRequestService
{
    public function __construct(
        private AppNotificationService $notificationService
    ) {}

    public function createBuyerRequest(User $buyer, array $data): ItemRequest
    {
        $buyerBusinessAccount = $this->getApprovedActiveBusinessAccount($buyer);

        $item = Item::query()
            ->with([
                'businessAccount.user',
                'businessAccount.businessType',
                'businessAccount.city',
                'category',
                'subcategory',
            ])
            ->findOrFail((int) $data['item_id']);

        if (! $item->isApproved()) {
            throw ValidationException::withMessages([
                'item_id' => ['You can only request approved items.'],
            ]);
        }

        $sellerBusinessAccount = $item->businessAccount;

        if (! $sellerBusinessAccount) {
            throw ValidationException::withMessages([
                'item_id' => ['The selected item does not have a valid seller business account.'],
            ]);
        }

        $sellerUser = $sellerBusinessAccount->user;

        if (! $sellerUser) {
            throw ValidationException::withMessages([
                'item_id' => ['The selected item does not have a valid seller user.'],
            ]);
        }

        if ($sellerUser->id === $buyer->id) {
            throw ValidationException::withMessages([
                'item_id' => ['You cannot request your own item.'],
            ]);
        }

        $this->ensureNoDuplicateOpenRequest(
            $item->id,
            $buyerBusinessAccount->id
        );

        $itemRequest = ItemRequest::create([
            'item_id' => $item->id,

            'buyer_user_id' => $buyer->id,
            'seller_user_id' => $sellerUser->id,

            'buyer_business_account_id' => $buyerBusinessAccount->id,
            'seller_business_account_id' => $sellerBusinessAccount->id,

            'status' => ItemRequest::STATUS_PENDING,
            'message' => $data['message'] ?? null,
        ]);

        $this->notificationService->sendNewItemRequest($item, $buyer);

        return $this->freshItemRequest($itemRequest);
    }

    public function getBuyerRequests(User $buyer, ?string $search = null): Collection
    {
        return ItemRequest::query()
            ->with([
                'item.category',
                'item.subcategory',
                'item.businessAccount.user',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'seller',
            ])
            ->where('buyer_user_id', $buyer->id)
            ->when($search !== null && trim($search) !== '', function ($query) use ($search) {
                $search = trim($search);

                $query->whereHas('item', function ($itemQuery) use ($search) {
                    $itemQuery->where(function ($innerQuery) use ($search) {
                        $innerQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('title_en', 'like', '%' . $search . '%')
                            ->orWhere('title_ar', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->get();
    }

    public function getBuyerRequestsBySellerBusinessAccount(User $buyer, string $sellerBusinessAccountName): Collection
    {
        $sellerBusinessAccountName = trim($sellerBusinessAccountName);

        return ItemRequest::query()
            ->with([
                'item.category',
                'item.subcategory',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
                'seller',
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



    public function getSellerRequests(User $seller, ?string $search = null): Collection
    {
        return ItemRequest::query()
            ->with([
                'item.category',
                'item.subcategory',
                'buyer',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
            ])
            ->where('seller_user_id', $seller->id)
            ->when($search !== null && trim($search) !== '', function ($query) use ($search) {
                $search = trim($search);

                $query->whereHas('item', function ($itemQuery) use ($search) {
                    $itemQuery->where(function ($innerQuery) use ($search) {
                        $innerQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('title_en', 'like', '%' . $search . '%')
                            ->orWhere('title_ar', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->get();
    }

    public function getSellerRequestsByBuyerBusinessAccount(User $seller, string $buyerBusinessAccountName): Collection
    {
        $buyerBusinessAccountName = trim($buyerBusinessAccountName);

        return ItemRequest::query()
            ->with([
                'item.category',
                'item.subcategory',
                'buyer',
                'buyerBusinessAccount.businessType',
                'buyerBusinessAccount.city',
                'sellerBusinessAccount.businessType',
                'sellerBusinessAccount.city',
            ])
            ->where('seller_user_id', $seller->id)
            ->whereHas('buyerBusinessAccount', function ($query) use ($buyerBusinessAccountName) {
                $query->where(function ($innerQuery) use ($buyerBusinessAccountName) {
                    $innerQuery->where('business_name', 'like', '%' . $buyerBusinessAccountName . '%')
                        ->orWhere('business_name_en', 'like', '%' . $buyerBusinessAccountName . '%')
                        ->orWhere('business_name_ar', 'like', '%' . $buyerBusinessAccountName . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function updateSellerRequestStatus(User $seller, int $itemRequestId, array $data): ItemRequest
    {
        $itemRequest = ItemRequest::query()
            ->with([
                'item.businessAccount.user',
                'item.category',
                'item.subcategory',
                'buyer',
                'seller',
                'buyerBusinessAccount',
                'sellerBusinessAccount',
            ])
            ->findOrFail($itemRequestId);

        if ($itemRequest->seller_user_id !== $seller->id) {
            throw ValidationException::withMessages([
                'item_request' => ['This request does not belong to you as a seller.'],
            ]);
        }

        $newStatus = $data['status'];

        $this->ensureStatusTransitionIsAllowed($itemRequest, $newStatus);

        $itemRequest->status = $newStatus;
        $itemRequest->save();

        $freshRequest = $this->freshItemRequest($itemRequest);

        if ($newStatus === ItemRequest::STATUS_IN_PROGRESS) {
            $this->notificationService->sendItemRequestInProgress(
                $freshRequest->buyer,
                $freshRequest->item
            );
        }

        if ($newStatus === ItemRequest::STATUS_REJECTED) {
            $this->notificationService->sendItemRequestRejected(
                $freshRequest->buyer,
                $freshRequest->item
            );
        }

        if ($newStatus === ItemRequest::STATUS_COMPLETED) {
            $this->notificationService->sendItemRequestCompleted(
                $freshRequest->buyer,
                $freshRequest->item
            );
        }

        return $freshRequest;
    }

    private function ensureStatusTransitionIsAllowed(ItemRequest $itemRequest, string $newStatus): void
    {
        if ($itemRequest->isCompleted()) {
            throw ValidationException::withMessages([
                'status' => ['A completed request cannot be updated again.'],
            ]);
        }

        if ($itemRequest->isRejected()) {
            throw ValidationException::withMessages([
                'status' => ['A rejected request cannot be updated again.'],
            ]);
        }

        $allowedTransitions = [
            ItemRequest::STATUS_PENDING => [
                ItemRequest::STATUS_IN_PROGRESS,
                ItemRequest::STATUS_REJECTED,
            ],
            ItemRequest::STATUS_IN_PROGRESS => [
                ItemRequest::STATUS_COMPLETED,
                ItemRequest::STATUS_REJECTED,
            ],
        ];

        $allowedNextStatuses = $allowedTransitions[$itemRequest->status] ?? [];

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
                'business_account' => ['You can only request items through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function ensureNoDuplicateOpenRequest(int $itemId, int $buyerBusinessAccountId): void
    {
        $exists = ItemRequest::query()
            ->where('item_id', $itemId)
            ->where('buyer_business_account_id', $buyerBusinessAccountId)
            ->whereIn('status', [
                ItemRequest::STATUS_PENDING,
                ItemRequest::STATUS_IN_PROGRESS,
            ])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'item_id' => ['You already have an open request for this item from the selected active business account.'],
            ]);
        }
    }

    private function freshItemRequest(ItemRequest $itemRequest): ItemRequest
    {
        return $itemRequest->fresh([
            'item.category',
            'item.subcategory',
            'item.businessAccount.user',
            'buyer',
            'seller',
            'buyerBusinessAccount.businessType',
            'buyerBusinessAccount.city',
            'sellerBusinessAccount.businessType',
            'sellerBusinessAccount.city',
        ]);
    }

    public function createRating(User $buyer, int $itemRequestId, array $data): ItemRating
    {
        $itemRequest = ItemRequest::query()
            ->with(['item'])
            ->findOrFail($itemRequestId);

        if ($itemRequest->buyer_user_id !== $buyer->id) {
            throw ValidationException::withMessages([
                'item_request' => ['You can only rate your own completed requests.'],
            ]);
        }

        if (! $itemRequest->isCompleted()) {
            throw ValidationException::withMessages([
                'item_request' => ['You can only rate a request after it has been completed.'],
            ]);
        }

        $alreadyRated = ItemRating::query()
            ->where('item_request_id', $itemRequest->id)
            ->exists();

        if ($alreadyRated) {
            throw ValidationException::withMessages([
                'item_request' => ['This request has already been rated.'],
            ]);
        }

        return ItemRating::create([
            'item_request_id' => $itemRequest->id,
            'item_id' => $itemRequest->item_id,

            'buyer_user_id' => $buyer->id,
            'seller_user_id' => $itemRequest->seller_user_id,

            'rating' => (int) $data['rating'],
            'review' => $data['review'] ?? null,
        ]);
    }




    public function updateRating(User $user, int $itemRequestId, array $data): ItemRating
    {
        $itemRequest = ItemRequest::query()
            ->with('rating')
            ->where('id', $itemRequestId)
            ->where('buyer_user_id', $user->id)
            ->first();

        if (! $itemRequest || ! $itemRequest->isCompleted()) {
            throw ValidationException::withMessages([
                'item_request' => ['You cannot rate a service you have not completed before.'],
            ]);
        }

        if (! $itemRequest->rating) {
            throw ValidationException::withMessages([
                'rating' => ['You have not rated this service yet.'],
            ]);
        }

        $itemRequest->rating->update([
            'rating' => (int) $data['rating'],
            'review' => $data['review'] ?? null,
        ]);

        return $itemRequest->rating->fresh();
    }

    public function getAverageRatingForItem(Item $item): array
    {
        $average = ItemRating::query()
            ->where('item_id', $item->id)
            ->avg('rating');

        $count = ItemRating::query()
            ->where('item_id', $item->id)
            ->count();

        return [
            'item_id' => $item->id,
            'average_rating' => $average ? round((float) $average, 2) : 0,
            'ratings_count' => $count,
        ];
    }
}
