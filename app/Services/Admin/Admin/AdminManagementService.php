<?php

namespace App\Services\Admin\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminManagementService
{
    private const ALLOWED_PERMISSION_KEYS = [
        'can_manage_users',
        'can_manage_business_accounts',
        'can_manage_business_types',
        'can_manage_categories',
        'can_manage_items',
        'can_manage_sliders',
        'can_manage_cities',
    ];

    public function paginateAdmins(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Admin::query()
            ->when($search, function ($query) use ($search) {
                $query->where('email', 'like', '%' . trim($search) . '%')
                    ->orWhere('name', 'like', '%' . trim($search) . '%');
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
            'manage_business_types_admins' => Admin::where('can_manage_business_types', true)->count(),
            'manage_categories_admins' => Admin::where('can_manage_categories', true)->count(),
            'manage_items_admins' => Admin::where('can_manage_items', true)->count(),
            'manage_sliders_admins' => Admin::where('can_manage_sliders', true)->count(),
            'manage_cities_admins' => Admin::where('can_manage_cities', true)->count(),
        ];
    }

    public function availablePermissions(): array
    {
        return [
            'can_manage_users' => __('messages.manage_users'),
            'can_manage_business_accounts' => __('messages.manage_business_accounts'),
            'can_manage_business_types' => __('messages.manage_business_types'),
            'can_manage_categories' => __('messages.manage_categories'),
            'can_manage_items' => __('messages.manage_items'),
            'can_manage_sliders' => __('messages.manage_sliders'),
            'can_manage_cities' => __('messages.manage_cities'),
        ];
    }



    public function defaultRoles(): array
{
    return [
        'account_manager' => [
            'label' => __('messages.account_manager'),
            'permissions' => [
                'can_manage_users',
                'can_manage_business_accounts',
            ],
        ],

        'content_manager' => [
            'label' => __('messages.content_manager'),
            'permissions' => [
                'can_manage_categories',
                'can_manage_items',
                'can_manage_sliders',
                'can_manage_cities',
            ],
        ],

        'marketplace_moderator' => [
            'label' => __('messages.marketplace_moderator'),
            'permissions' => [
                'can_manage_business_accounts',
                'can_manage_items',
                'can_manage_sliders',
            ],
        ],

        'settings_manager' => [
            'label' => __('messages.settings_manager'),
            'permissions' => [
                'can_manage_business_types',
                'can_manage_categories',
                'can_manage_cities',
            ],
        ],

        'full_staff_manager' => [
            'label' => __('messages.full_staff_manager'),
            'permissions' => [
                'can_manage_users',
                'can_manage_business_accounts',
                'can_manage_business_types',
                'can_manage_categories',
                'can_manage_items',
                'can_manage_sliders',
                'can_manage_cities',
            ],
        ],
    ];
}

    public function create(array $data): Admin
    {
        $permissions = $this->normalizePermissions($data);

        return Admin::create(array_merge([
            'name' => trim($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_super_admin' => false,
        ], $permissions));
    }

    public function update(Admin $admin, array $data): Admin
    {
        $permissions = $this->normalizePermissions($data);

        $admin->name = trim($data['name']);
        $admin->email = $data['email'];

        if (! $admin->isSuperAdmin()) {
            foreach ($permissions as $key => $value) {
                $admin->{$key} = $value;
            }

            $admin->is_super_admin = false;
        }

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return $admin->fresh();
    }

    private function normalizePermissions(array $data): array
    {
        $permissions = [];

        foreach (self::ALLOWED_PERMISSION_KEYS as $key) {
            $permissions[$key] = false;
        }

        $selectedPermissions = $data['permissions'] ?? [];

        if (! is_array($selectedPermissions)) {
            $selectedPermissions = [];
        }

        foreach ($selectedPermissions as $permissionKey) {
            if (in_array($permissionKey, self::ALLOWED_PERMISSION_KEYS, true)) {
                $permissions[$permissionKey] = true;
            }
        }

        return $permissions;
    }

    public function delete(Admin $admin): void
    {
        if ($admin->isSuperAdmin()) {
            abort(403, 'The seeded Super Admin cannot be deleted.');
    }

        $admin->delete();
    }
}
