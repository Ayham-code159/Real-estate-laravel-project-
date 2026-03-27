<?php

namespace App\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\Service\AdminServiceManagementService;
use App\Http\Requests\Admin\Service\UpdateServiceStatusRequest;

class AdminServiceManagementController extends Controller
{
    public function __construct(
        private AdminServiceManagementService $adminServiceManagementService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');

        $services = $this->adminServiceManagementService->getAllServices(
            $search,
            $type ?: null
        );

        $counts = $this->adminServiceManagementService->getServicesSummaryCounts();

        return view('admin.services.index', compact('services', 'counts', 'search', 'type'));
    }

    public function show(string $type, int $id)
    {
        $service = $this->adminServiceManagementService->getServiceDetails($type, $id);

        return view('admin.services.show', compact('service', 'type'));
    }

    public function updateStatus(
        UpdateServiceStatusRequest $request,
        string $type,
        int $id
    ): RedirectResponse {
        $this->adminServiceManagementService->updateServiceStatus(
            $type,
            $id,
            $request->validated()
        );

        return redirect()
            ->route('admin.services.show', ['type' => $type, 'id' => $id])
            ->with('success', 'Service status updated successfully.');
    }
}
