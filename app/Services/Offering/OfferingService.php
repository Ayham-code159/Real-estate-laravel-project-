<?php

namespace App\Services\Offering;

use App\Models\User;
use App\Models\Offering;
use App\Models\BusinessAccount;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

class OfferingService
{
    public function create(User $user, array $data): Offering
    {
        $businessAccount = $this->getApprovedActiveBusinessAccount($user);

        return Offering::create([
            'business_account_id' => $businessAccount->id,
            'type' => $data['type'],
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);
    }

    public function listForUser(User $user): Collection
    {
        return Offering::with(['businessAccount.businessType', 'businessAccount.city'])
            ->whereHas('businessAccount', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    public function update(User $user, Offering $offering, array $data): Offering
    {
        $this->ensureOfferingBelongsToUserApprovedBusinessAccount($user, $offering);

        $offering->update([
            'type' => $data['type'],
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        return $offering->fresh(['businessAccount.businessType', 'businessAccount.city']);
    }

    public function delete(User $user, Offering $offering): void
    {
        $this->ensureOfferingBelongsToUserApprovedBusinessAccount($user, $offering);

        $offering->delete();
    }

    public function listForActiveBusinessAccount(User $user): Collection
    {
        $businessAccount = $this->getApprovedActiveBusinessAccount($user);

        return Offering::with(['businessAccount.businessType', 'businessAccount.city'])
            ->where('business_account_id', $businessAccount->id)
            ->latest()
            ->get();
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
                'business_account' => ['You can only manage offerings through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function ensureOfferingBelongsToUserApprovedBusinessAccount(User $user, Offering $offering): void
    {
        $businessAccount = $offering->businessAccount;

        if (! $businessAccount || $businessAccount->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'offering' => ['This offering does not belong to you.'],
            ]);
        }

        if (! $businessAccount->isApproved()) {
            throw ValidationException::withMessages([
                'offering' => ['You cannot manage offerings unless the related business account is approved.'],
            ]);
        }
    }
}
