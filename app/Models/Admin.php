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
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function canManageUsers(): bool
    {
        return $this->is_super_admin || $this->can_manage_users;
    }

    public function canManageBusinessAccounts(): bool
    {
        return $this->is_super_admin || $this->can_manage_users || $this->can_manage_business_accounts;
    }

    public function permissionLabel(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        if ($this->canManageUsers()) {
            return 'Manage Users';
        }

        if ($this->canManageBusinessAccounts()) {
            return 'Manage Business Accounts';
        }

        return 'Basic Admin';
    }
}
