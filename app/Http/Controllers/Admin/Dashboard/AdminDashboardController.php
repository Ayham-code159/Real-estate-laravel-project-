<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\ServiceListing;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $listingCounts = [
            'total' => ServiceListing::count(),
            'pending' => ServiceListing::where('status', ServiceListing::STATUS_PENDING)->count(),
            'approved' => ServiceListing::where('status', ServiceListing::STATUS_APPROVED)->count(),
            'rejected' => ServiceListing::where('status', ServiceListing::STATUS_REJECTED)->count(),
        ];

        $recentPendingListings = ServiceListing::query()
            ->with([
                'service',
                'subcategory',
                'businessAccount.user',
            ])
            ->where('status', ServiceListing::STATUS_PENDING)
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('listingCounts', 'recentPendingListings'));
    }
}
