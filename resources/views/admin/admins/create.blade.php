@extends('layouts.app')

@section('title', 'Create Admin')

@section('content')
    <x-page-title
        title="Create Admin"
        subtitle="Create a new admin account and assign permissions."
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div class="grid grid-2">
                <x-input label="Name" name="name" type="text" placeholder="Enter admin name" required />
                <x-input label="Email" name="email" type="email" placeholder="Enter admin email" required />
            </div>

            <div class="grid grid-2">
                <x-input label="Password" name="password" type="password" placeholder="Enter password" required />
                <x-input label="Confirm Password" name="password_confirmation" type="password" placeholder="Confirm password" required />
            </div>

            <div style="margin-top: 24px;">
                <h3 class="section-title" style="font-size: 20px;">Permissions</h3>
                <p class="section-subtitle" style="margin-bottom: 16px;">
                    Super Admin grants all permissions. Manage Users also grants Business Accounts access.
                </p>

                <div class="grid">
                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}>
                        <span><strong>Make Super Admin</strong></span>
                    </label>

                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="can_manage_users" value="1" {{ old('can_manage_users') ? 'checked' : '' }}>
                        <span><strong>Can Manage Users</strong></span>
                    </label>

                    <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: center;">
                        <input type="checkbox" name="can_manage_business_accounts" value="1" {{ old('can_manage_business_accounts') ? 'checked' : '' }}>
                        <span><strong>Can Manage Business Accounts</strong></span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>Create Admin</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
