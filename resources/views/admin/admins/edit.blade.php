@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')
    <x-page-title
        title="Edit Admin"
        subtitle="Update this admin account and adjust permissions."
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-outline">
                <span>←</span>
                <span>Back</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <x-input label="Name" name="name" type="text" :value="$admin->name" placeholder="Enter admin name" required />
                <x-input label="Email" name="email" type="email" :value="$admin->email" placeholder="Enter admin email" required />
            </div>

            <div class="grid grid-2">
                <x-input label="New Password (optional)" name="password" type="password" placeholder="Leave empty to keep current password" />
                <x-input label="Confirm New Password" name="password_confirmation" type="password" placeholder="Confirm new password" />
            </div>

            <div style="margin-top: 24px;">
                <h3 class="section-title" style="font-size: 20px;">Permissions</h3>
                <p class="section-subtitle" style="margin-bottom: 16px;">
                    Super Admin grants all permissions. Manage Users also grants Business Accounts access.
                </p>

                <div class="grid">
                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin', $admin->is_super_admin) ? 'checked' : '' }}>
                        <span><strong>Make Super Admin</strong></span>
                    </label>

                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="can_manage_users" value="1" {{ old('can_manage_users', $admin->can_manage_users) ? 'checked' : '' }}>
                        <span><strong>Can Manage Users</strong></span>
                    </label>

                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="can_manage_business_accounts" value="1" {{ old('can_manage_business_accounts', $admin->can_manage_business_accounts) ? 'checked' : '' }}>
                        <span><strong>Can Manage Business Accounts</strong></span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>Update Admin</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
