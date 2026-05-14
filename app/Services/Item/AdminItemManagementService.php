<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use App\Services\Notification\AppNotificationService;

class AdminItemManagementService
{
    public function __construct(
        private AppNotificationService $notificationService
    ) {}

    public function paginatedItems(): LengthAwarePaginator
    {
        return Item::query()
            ->with([
                'businessAccount.user',
                'businessAccount.businessType',
                'businessAccount.city',
                'category',
                'subcategory',
            ])
            ->latest()
            ->paginate(10);
    }

    public function getItemDetails(Item $item): Item
    {
        return $item->load([
            'businessAccount.user',
            'businessAccount.businessType',
            'businessAccount.city',
            'category',
            'subcategory',
        ]);
    }

    public function updateStatus(Item $item, array $data): Item
    {
        $status = (int) $data['status'];

        if (! array_key_exists($status, Item::statuses())) {
            throw ValidationException::withMessages([
                'status' => ['Invalid item status.'],
            ]);
        }

        $oldStatus = (int) $item->status;

        $item->status = $status;

        if ($status === Item::STATUS_REJECTED) {
            $item->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $item->rejection_reason = null;
        }

        $item->save();

        $item = $this->getItemDetails($item);

        if ($oldStatus === $status) {
            return $item;
        }

        if ($status === Item::STATUS_APPROVED) {
            $this->notificationService->sendItemAccepted($item);
        }

        if ($status === Item::STATUS_REJECTED) {
            $this->notificationService->sendItemRejected($item);
        }

        return $item;
    }

    public function delete(Item $item): void
    {
        $item->delete();
    }
}
