<?php

namespace App\Services\Admin\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminManagementService
{
    public function paginateAdmins(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Admin::query()
            ->when($search, function ($query) use ($search) {
                $query->where('email', 'like', '%' . trim($search) . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAdminsCounts(): array
    {
        return [
            'total_admins' => Admin::count(),
            'super_admins' => Admin::where('is_super_admin', true)->count(),
            'manage_users_admins' => Admin::where('can_manage_users', true)->count(),
            'manage_business_accounts_admins' => Admin::where('can_manage_business_accounts', true)->count(),
        ];
    }

    public function create(array $data): Admin
    {
        $permissions = $this->normalizePermissions($data);

        return Admin::create([
            'name' => trim($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_super_admin' => $permissions['is_super_admin'],
            'can_manage_users' => $permissions['can_manage_users'],
            'can_manage_business_accounts' => $permissions['can_manage_business_accounts'],
        ]);
    }

    public function update(Admin $admin, array $data): Admin
    {
        $permissions = $this->normalizePermissions($data);

        $admin->name = trim($data['name']);
        $admin->email = $data['email'];
        $admin->is_super_admin = $permissions['is_super_admin'];
        $admin->can_manage_users = $permissions['can_manage_users'];
        $admin->can_manage_business_accounts = $permissions['can_manage_business_accounts'];

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return $admin->fresh();
    }

    private function normalizePermissions(array $data): array
    {
        $isSuperAdmin = (bool) ($data['is_super_admin'] ?? false);
        $canManageUsers = (bool) ($data['can_manage_users'] ?? false);
        $canManageBusinessAccounts = (bool) ($data['can_manage_business_accounts'] ?? false);

        if ($isSuperAdmin) {
            $canManageUsers = true;
            $canManageBusinessAccounts = true;
        }

        if ($canManageUsers) {
            $canManageBusinessAccounts = true;
        }

        return [
            'is_super_admin' => $isSuperAdmin,
            'can_manage_users' => $canManageUsers,
            'can_manage_business_accounts' => $canManageBusinessAccounts,
        ];
    }
}
