<?php

namespace App\Services\BusinessContext;

use App\Models\User;
use App\Models\BusinessAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class BusinessContextService
{
    public function listApprovedBusinessAccounts(User $user): Collection
    {
        return $user->businessAccounts()
            ->with(['businessType', 'city'])
            ->where('status', BusinessAccount::STATUS_APPROVED)
            ->latest()
            ->get();
    }

    public function switchActiveBusinessAccount(User $user, int $businessAccountId): BusinessAccount
    {
        $businessAccount = $user->businessAccounts()
            ->with(['businessType', 'city'])
            ->where('id', $businessAccountId)
            ->first();

        if (! $businessAccount) {
            throw ValidationException::withMessages([
                'business_account_id' => ['This business account does not belong to you.'],
            ]);
        }

        if (! $businessAccount->isApproved()) {
            throw ValidationException::withMessages([
                'business_account_id' => ['You can only switch to an approved business account.'],
            ]);
        }

        $user->update([
            'active_business_account_id' => $businessAccount->id,
        ]);

        return $businessAccount;
    }

    public function getActiveBusinessAccount(User $user): ?BusinessAccount
    {
        if (! $user->active_business_account_id) {
            return null;
        }

        return $user->activeBusinessAccount()
            ->with(['businessType', 'city'])
            ->first();
    }

    public function clearActiveBusinessAccount(User $user): void
    {
        $user->update([
            'active_business_account_id' => null,
        ]);
    }
}
