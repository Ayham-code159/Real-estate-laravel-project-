<?php

namespace App\Services\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminBusinessAccountService
{
    public function paginateBusinessAccounts(int $perPage = 10): LengthAwarePaginator
    {
        return BusinessAccount::with(['user', 'businessType', 'city'])
            ->latest()
            ->paginate($perPage);
    }

    public function updateStatus(BusinessAccount $businessAccount, array $data): void
    {
        $businessAccount->status = (int) $data['status'];

        if ($businessAccount->status === BusinessAccount::STATUS_REJECTED) {
            $businessAccount->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $businessAccount->rejection_reason = null;
        }

        $businessAccount->save();
    }

    public function getCounts(): array
    {
        return [
            'total' => BusinessAccount::count(),
            'pending' => BusinessAccount::where('status', BusinessAccount::STATUS_PENDING)->count(),
            'approved' => BusinessAccount::where('status', BusinessAccount::STATUS_APPROVED)->count(),
            'rejected' => BusinessAccount::where('status', BusinessAccount::STATUS_REJECTED)->count(),
        ];
    }
}
