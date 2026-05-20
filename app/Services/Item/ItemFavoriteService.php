<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\User;
use App\Models\BusinessAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ItemFavoriteService
{
    public function list(User $user): Collection
    {
        $this->ensureUserHasApprovedActiveBusinessAccount($user);

        return $user->favoriteItems()
            ->with([
                'businessAccount.businessType',
                'businessAccount.city',
                'category',
                'subcategory',
            ])
            ->latest('item_favorites.created_at')
            ->get();
    }

    public function add(User $user, Item $item): Item
    {
        $this->ensureUserHasApprovedActiveBusinessAccount($user);

        $user->favoriteItems()->syncWithoutDetaching([
            $item->id,
        ]);

        return $this->freshItem($item);
    }

    public function remove(User $user, Item $item): void
    {
        $this->ensureUserHasApprovedActiveBusinessAccount($user);

        $user->favoriteItems()->detach($item->id);
    }

    private function ensureUserHasApprovedActiveBusinessAccount(User $user): BusinessAccount
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
                'business_account' => ['You can only use favorites through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function freshItem(Item $item): Item
    {
        return $item->fresh([
            'businessAccount.businessType',
            'businessAccount.city',
            'category',
            'subcategory',
        ]);
    }
}
