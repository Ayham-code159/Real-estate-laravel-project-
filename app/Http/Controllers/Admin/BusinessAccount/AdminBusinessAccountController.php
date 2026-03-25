<?php

namespace App\Http\Controllers\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\BusinessAccount\AdminBusinessAccountService;
use App\Http\Requests\Admin\BusinessAccount\UpdateBusinessAccountStatusRequest;

class AdminBusinessAccountController extends Controller
{
    public function __construct(
        private AdminBusinessAccountService $adminBusinessAccountService
    ) {}

    public function index()
    {
        $businessAccounts = $this->adminBusinessAccountService->paginateBusinessAccounts();
        $counts = $this->adminBusinessAccountService->getCounts();

        return view('admin.business-accounts.index', compact('businessAccounts', 'counts'));
    }

    public function updateStatus(
        UpdateBusinessAccountStatusRequest $request,
        BusinessAccount $businessAccount
    ): RedirectResponse {
        $this->adminBusinessAccountService->updateStatus($businessAccount, $request->validated());

        return redirect()
            ->route('admin.business-accounts.index')
            ->with('success', 'Business account status updated successfully.');
    }
}
