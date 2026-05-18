<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\ItemSlider;
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
                'slider',
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
            'slider',
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

        if ($oldStatus !== $status) {
            if ($status === Item::STATUS_APPROVED) {
                $this->createSliderIfMissing($item);
                $this->notificationService->sendItemAccepted($item);
            }

            if ($status === Item::STATUS_REJECTED) {
                $this->deactivateSliderIfExists($item);
                $this->notificationService->sendItemRejected($item);
            }

            if ($status === Item::STATUS_PENDING) {
                $this->deactivateSliderIfExists($item);
            }
        }

        return $this->getItemDetails($item);
    }

    public function delete(Item $item): void
    {
        $item->delete();
    }

    private function createSliderIfMissing(Item $item): void
    {
        ItemSlider::query()->firstOrCreate(
            [
                'item_id' => $item->id,
            ],
            [
                'is_active' => true,
                'priority' => ItemSlider::PRIORITY_NORMAL,
                'click_count' => 0,
                'admin_note' => null,
            ]
        );

        if ($item->slider && ! $item->slider->is_active) {
            $item->slider->update([
                'is_active' => true,
            ]);
        }
    }

    private function deactivateSliderIfExists(Item $item): void
    {
        $item->slider?->update([
            'is_active' => false,
        ]);
    }
}
