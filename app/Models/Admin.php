<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',

        'is_super_admin',
        'can_manage_users',
        'can_manage_business_accounts',
        'can_manage_business_types',
        'can_manage_categories',
        'can_manage_items',
        'can_manage_sliders',

        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',

            'is_super_admin' => 'boolean',
            'can_manage_users' => 'boolean',
            'can_manage_business_accounts' => 'boolean',
            'can_manage_business_types' => 'boolean',
            'can_manage_categories' => 'boolean',
            'can_manage_items' => 'boolean',
            'can_manage_sliders' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return match ($permission) {
            'manage_users' => $this->canManageUsers(),
            'manage_business_accounts' => $this->canManageBusinessAccounts(),
            'manage_business_types' => $this->canManageBusinessTypes(),
            'manage_categories' => $this->canManageCategories(),
            'manage_items' => $this->canManageItems(),
            'manage_sliders' => $this->canManageSliders(),
            default => false,
        };
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_users;
    }

    public function canManageBusinessAccounts(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_business_accounts;
    }

    public function canManageBusinessTypes(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_business_types;
    }

    public function canManageCategories(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_categories;
    }

    public function canManageItems(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_items;
    }

    public function canManageSliders(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_sliders;
    }

    public function permissionLabel(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        $permissions = $this->permissionLabels();

        if (empty($permissions)) {
            return 'Basic Admin';
        }

        return implode(', ', $permissions);
    }

    public function permissionLabels(): array
    {
        $permissions = [];

        if ($this->can_manage_users) {
            $permissions[] = 'Manage Users';
        }

        if ($this->can_manage_business_accounts) {
            $permissions[] = 'Manage Business Accounts';
        }

        if ($this->can_manage_business_types) {
            $permissions[] = 'Manage Business Types';
        }

        if ($this->can_manage_categories) {
            $permissions[] = 'Manage Categories';
        }

        if ($this->can_manage_items) {
            $permissions[] = 'Manage Items';
        }

        if ($this->can_manage_sliders) {
            $permissions[] = 'Manage Sliders';
        }

        return $permissions;
    }
}
