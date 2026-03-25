@extends('layouts.app')

@section('title', 'Admin Details')

@section('content')
    <x-page-title
        title="Admin Details"
        subtitle="Inspect this admin account and review its access level."
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary">
                <span>✎</span>
                <span>Edit Admin</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $admin->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $admin->email }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Permission Level</div>
                <div class="info-value">{{ $admin->permissionLabel() }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Super Admin</div>
                <div class="info-value">{{ $admin->is_super_admin ? 'Yes' : 'No' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Can Manage Users</div>
                <div class="info-value">{{ $admin->can_manage_users ? 'Yes' : 'No' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Can Manage Business Accounts</div>
                <div class="info-value">{{ $admin->can_manage_business_accounts ? 'Yes' : 'No' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $admin->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>
@endsection
