<?php

namespace App\Services\BusinessAccount;

use App\Models\User;
use App\Models\BusinessAccount;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

class BusinessAccountService
{
    public function create(User $user, array $data): BusinessAccount
    {
        if ($user->businessAccounts()->count() >= 5) {
            throw ValidationException::withMessages([
                'business_account_limit' => ['You cannot create more than 5 business accounts.'],
            ]);
        }

        $alreadyHasSameType = $user->businessAccounts()
            ->where('business_type_id', $data['business_type_id'])
            ->exists();

        if ($alreadyHasSameType) {
            throw ValidationException::withMessages([
                'business_type_id' => ['You already have a business account for this service type.'],
            ]);
        }

        return BusinessAccount::create([
            'user_id' => $user->id,
            'business_type_id' => $data['business_type_id'],
            'city_id' => $data['city_id'],
            'business_name' => trim($data['business_name']),
            'status' => 'pending',
        ]);
    }

    public function listForUser(User $user): Collection
    {
        return $user->businessAccounts()
            ->with(['businessType', 'city'])
            ->latest()
            ->get();
    }

    public function delete(User $user, int $businessAccountId): void
    {
        $businessAccount = $user->businessAccounts()
            ->where('id', $businessAccountId)
            ->first();

        if (! $businessAccount) {
            throw ValidationException::withMessages([
                'business_account' => ['Business account not found or does not belong to you.'],
            ]);
        }

        $businessAccount->delete();
    }
}
