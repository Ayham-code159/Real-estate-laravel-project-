<?php

namespace App\Http\Controllers\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\BusinessAccount\AdminBusinessAccountService;

class AdminBusinessAccountController extends Controller
{
    public function __construct(
        private AdminBusinessAccountService $businessAccountService
    ) {}

    public function index()
    {
        $businessAccounts = $this->businessAccountService->paginateBusinessAccounts(15);
        $counts = $this->businessAccountService->getCounts();

        return view('admin.business-accounts.index', compact('businessAccounts', 'counts'));
    }

    public function show(BusinessAccount $businessAccount)
    {
        $businessAccount->load([
            'user',
            'businessType',
            'city',
            'items.category',
            'items.subcategory',
        ]);

        return view('admin.business-accounts.show', compact('businessAccount'));
    }

    public function updateStatus(Request $request, BusinessAccount $businessAccount): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2,3'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->businessAccountService->updateStatus($businessAccount, $validated);

        return back()->with('success', 'Business account status updated successfully.');
    }

    public function destroy(BusinessAccount $businessAccount): RedirectResponse
    {
        $this->businessAccountService->deleteBusinessAccount($businessAccount);

        return redirect()
            ->route('admin.business-accounts.index')
            ->with('success', 'Business account and all related items deleted successfully.');
    }
}
