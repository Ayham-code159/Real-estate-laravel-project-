<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use App\Models\BusinessAccount;
use App\Models\ServiceListing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $users = User::query()
            ->withCount([
                'businessAccounts',
                'businessAccounts as approved_business_accounts_count' => function ($query) {
                    $query->where('status', BusinessAccount::STATUS_APPROVED);
                },
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'total_users' => User::count(),
            'with_business_accounts' => User::has('businessAccounts')->count(),
            'with_approved_accounts' => User::whereHas('businessAccounts', function ($query) {
                $query->where('status', BusinessAccount::STATUS_APPROVED);
            })->count(),
            'total_listings' => ServiceListing::count(),
        ];

        return view('admin.users.index', compact('users', 'counts', 'search'));
    }

    public function show(User $user)
    {
        $user->load([
            'businessAccounts.businessType',
            'businessAccounts.city',
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function showBusinessAccount(User $user, BusinessAccount $businessAccount)
    {
        abort_if($businessAccount->user_id !== $user->id, 404);

        $businessAccount->load([
            'businessType',
            'city',
            'serviceListings.service',
            'serviceListings.subcategory',
        ]);

        return view('admin.users.business-account-show', compact('user', 'businessAccount'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function destroyBusinessAccount(BusinessAccount $businessAccount): RedirectResponse
    {
        $businessAccount->delete();

        return back()->with('success', 'Business account deleted successfully.');
    }
}
