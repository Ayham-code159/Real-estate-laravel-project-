<?php

namespace App\Services\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\Notification\AppNotificationService;

class AdminBusinessAccountService
{
    public function __construct(
        private AppNotificationService $notificationService
    ) {}

    public function paginateBusinessAccounts(int $perPage = 10): LengthAwarePaginator
    {
        return BusinessAccount::query()
            ->with(['user', 'businessType', 'city'])
            ->withCount('items')
            ->latest()
            ->paginate($perPage);
    }

    public function updateStatus(BusinessAccount $businessAccount, array $data): void
    {
        $oldStatus = (int) $businessAccount->status;
        $newStatus = (int) $data['status'];

        $businessAccount->status = $newStatus;

        if ($newStatus === BusinessAccount::STATUS_REJECTED) {
            $businessAccount->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $businessAccount->rejection_reason = null;
        }

        $businessAccount->save();

        if ($oldStatus === $newStatus) {
            return;
        }

        $businessAccount->load('user');

        if ($newStatus === BusinessAccount::STATUS_APPROVED) {
            $this->notificationService->sendBusinessAccountAccepted($businessAccount);
        }

        if ($newStatus === BusinessAccount::STATUS_REJECTED) {
            $this->notificationService->sendBusinessAccountRejected($businessAccount);
        }
    }

    public function deleteBusinessAccount(BusinessAccount $businessAccount): void
    {
        DB::transaction(function () use ($businessAccount) {
            $businessAccount->items()->delete();
            $businessAccount->delete();
        });
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
