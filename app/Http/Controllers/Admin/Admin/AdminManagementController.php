<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\Admin\AdminManagementService;
use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;

class AdminManagementController extends Controller
{
    public function __construct(
        private AdminManagementService $adminManagementService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');

        $admins = $this->adminManagementService->paginateAdmins($search);
        $counts = $this->adminManagementService->getAdminsCounts();

        return view('admin.admins.index', compact('admins', 'counts', 'search'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $this->adminManagementService->create($request->validated());

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {
        $this->adminManagementService->update($admin, $request->validated());

        return redirect()
            ->route('admin.admins.show', $admin)
            ->with('success', 'Admin updated successfully.');
    }
}
