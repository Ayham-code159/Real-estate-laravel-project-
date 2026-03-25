<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use App\Models\BusinessAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\User\AdminUserService;

class AdminUserController extends Controller
{
    public function __construct(
        private AdminUserService $adminUserService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = $this->adminUserService->paginateUsers($search);
        $counts = $this->adminUserService->getUsersCounts();

        return view('admin.users.index', compact('users', 'counts', 'search'));
    }

    public function show(User $user)
    {
        $user = $this->adminUserService->getUserDetails($user);
        $counts = $this->adminUserService->getUserDetailsCounts($user);

        return view('admin.users.show', compact('user', 'counts'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->adminUserService->deleteUser($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function destroyBusinessAccount(BusinessAccount $businessAccount): RedirectResponse
    {
        $userId = $businessAccount->user_id;

        $this->adminUserService->deleteBusinessAccount($businessAccount);

        return redirect()
            ->route('admin.users.show', $userId)
            ->with('success', 'Business account deleted successfully.');
    }
}
