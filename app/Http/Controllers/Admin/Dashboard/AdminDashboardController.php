<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Admin\Service\AdminServiceManagementService;

class AdminDashboardController extends Controller
{
    public function __construct(
        private AdminServiceManagementService $adminServiceManagementService
    ) {}

    public function index()
    {
        $serviceCounts = $this->adminServiceManagementService->getApprovedServicesCount();

        return view('admin.dashboard', compact('serviceCounts'));
    }
}
