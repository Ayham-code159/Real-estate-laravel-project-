<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\User;
use App\Models\Item;
use App\Models\ItemSlider;
use App\Models\BusinessAccount;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();

        $stats = [
            'total_users' => User::count(),
            'total_items' => Item::count(),
            'pending_items' => Item::where('status', Item::STATUS_PENDING)->count(),
            'approved_items' => Item::where('status', Item::STATUS_APPROVED)->count(),
            'rejected_items' => Item::where('status', Item::STATUS_REJECTED)->count(),
            'active_sliders' => ItemSlider::where('is_active', true)->count(),
            'pending_business_accounts' => BusinessAccount::where('status', BusinessAccount::STATUS_PENDING)->count(),
            'total_business_accounts' => BusinessAccount::count(),
        ];

        $recentPendingItems = Item::query()
            ->with(['businessAccount.user', 'category', 'subcategory'])
            ->where('status', Item::STATUS_PENDING)
            ->latest()
            ->limit(5)
            ->get();

        $recentBusinessAccounts = BusinessAccount::query()
            ->with(['user', 'businessType', 'city'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'admin',
            'stats',
            'recentPendingItems',
            'recentBusinessAccounts'
        ));
    }
}
