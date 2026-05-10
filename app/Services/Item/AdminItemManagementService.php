<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class AdminItemManagementService
{
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

        $item->status = $status;

        if ($status === Item::STATUS_REJECTED) {
            $item->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $item->rejection_reason = null;
        }

        $item->save();

        return $this->getItemDetails($item);
    }

    public function delete(Item $item): void
    {
        $item->delete();
    }
}
