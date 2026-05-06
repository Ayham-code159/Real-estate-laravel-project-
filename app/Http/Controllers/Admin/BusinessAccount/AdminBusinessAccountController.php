<?php

namespace App\Http\Controllers\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Notification\AppNotificationService;

class AdminBusinessAccountController extends Controller
{
    public function __construct(
        private AppNotificationService $appNotificationService
    ) {}

    public function index()
    {
        $businessAccounts = BusinessAccount::query()
            ->with([
                'user',
                'businessType',
                'city',
            ])
            ->withCount('serviceListings')
            ->latest()
            ->paginate(10);

        $counts = [
            'total' => BusinessAccount::count(),
            'pending' => BusinessAccount::where('status', BusinessAccount::STATUS_PENDING)->count(),
            'approved' => BusinessAccount::where('status', BusinessAccount::STATUS_APPROVED)->count(),
            'rejected' => BusinessAccount::where('status', BusinessAccount::STATUS_REJECTED)->count(),
        ];

        return view('admin.business-accounts.index', compact('businessAccounts', 'counts'));
    }

    public function updateStatus(Request $request, BusinessAccount $businessAccount): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2,3'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $businessAccount->status;
        $newStatus = (int) $validated['status'];

        $businessAccount->status = $newStatus;

        if ($businessAccount->status === BusinessAccount::STATUS_REJECTED) {
            $businessAccount->rejection_reason = $validated['rejection_reason'] ?? null;
        } else {
            $businessAccount->rejection_reason = null;
        }

        $businessAccount->save();

        $businessAccount->load('user');

        if ($oldStatus !== $newStatus) {
            if ($newStatus === BusinessAccount::STATUS_APPROVED) {
                $this->appNotificationService->sendBusinessAccountAccepted($businessAccount);
            }

            if ($newStatus === BusinessAccount::STATUS_REJECTED) {
                $this->appNotificationService->sendBusinessAccountRejected($businessAccount);
            }
        }

        return back()->with('success', 'Business account status updated successfully.');
    }
}
