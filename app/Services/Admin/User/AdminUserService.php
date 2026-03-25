<?php

namespace App\Services\Admin\User;

use App\Models\User;
use App\Models\Offering;
use App\Models\BusinessAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserService
{
    public function paginateUsers(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->withCount('businessAccounts')
            ->withCount([
                'businessAccounts as approved_business_accounts_count' => function ($query) {
                    $query->where('status', BusinessAccount::STATUS_APPROVED);
                },
            ])
            ->when($search, function ($query) use ($search) {
                $search = trim($search);

                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getUsersCounts(): array
    {
        $usersWithBusinessAccounts = User::whereHas('businessAccounts')->count();

        $usersWithApprovedBusinessAccounts = User::whereHas('businessAccounts', function ($query) {
            $query->where('status', BusinessAccount::STATUS_APPROVED);
        })->count();

        return [
            'total_users' => User::count(),
            'users_with_business_accounts' => $usersWithBusinessAccounts,
            'users_with_approved_business_accounts' => $usersWithApprovedBusinessAccounts,
            'total_offerings' => Offering::count(),
        ];
    }

    public function getUserDetails(User $user): User
    {
        return $user->load([
            'activeBusinessAccount.businessType',
            'activeBusinessAccount.city',
            'businessAccounts.businessType',
            'businessAccounts.city',
            'businessAccounts.offerings',
        ]);
    }

    public function getUserDetailsCounts(User $user): array
    {
        $businessAccounts = $user->businessAccounts;

        $approvedBusinessAccounts = $businessAccounts->where('status', BusinessAccount::STATUS_APPROVED)->count();
        $rejectedBusinessAccounts = $businessAccounts->where('status', BusinessAccount::STATUS_REJECTED)->count();
        $pendingBusinessAccounts = $businessAccounts->where('status', BusinessAccount::STATUS_PENDING)->count();

        $totalOfferings = $businessAccounts->sum(function ($businessAccount) {
            return $businessAccount->offerings->count();
        });

        return [
            'business_accounts_count' => $businessAccounts->count(),
            'approved_business_accounts_count' => $approvedBusinessAccounts,
            'rejected_business_accounts_count' => $rejectedBusinessAccounts,
            'pending_business_accounts_count' => $pendingBusinessAccounts,
            'offerings_count' => $totalOfferings,
        ];
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function deleteBusinessAccount(BusinessAccount $businessAccount): void
    {
        $businessAccount->delete();
    }
}
