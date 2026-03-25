@extends('layouts.app')

@section('title', 'Admins')

@section('content')
    <x-page-title
        title="Admins"
        subtitle="Manage administrator accounts, search by email, and control their permissions."
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <span>＋</span>
                <span>Add Admin</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Admins</div>
                    <div class="stats-value">{{ $counts['total_admins'] }}</div>
                    <div class="stats-meta">All administrator accounts</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Super Admins</div>
                    <div class="stats-value">{{ $counts['super_admins'] }}</div>
                    <div class="stats-meta">Highest permission level</div>
                </div>
                <div class="stats-icon">⭐</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Manage Users</div>
                    <div class="stats-value">{{ $counts['manage_users_admins'] }}</div>
                    <div class="stats-meta">Can manage users and business accounts</div>
                </div>
                <div class="stats-icon">👥</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Business Managers</div>
                    <div class="stats-value">{{ $counts['manage_business_accounts_admins'] }}</div>
                    <div class="stats-meta">Can manage business accounts</div>
                </div>
                <div class="stats-icon">🏢</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.admins.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Search by Email</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="Search admin by email"
                    >
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <x-button type="submit" variant="primary">
                        <span>🔍</span>
                        <span>Search</span>
                    </x-button>

                    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">
                        <span>↺</span>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Admins</h2>
            <p class="section-subtitle">
                View admin accounts, inspect permissions, and update access levels.
            </p>
        </div>

        @forelse($admins as $admin)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">{{ $admin->name }}</h3>
                            <p class="text-muted" style="margin: 0;">{{ $admin->email }}</p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            <span class="badge badge-primary">{{ $admin->permissionLabel() }}</span>

                            <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-primary">
                                <span>👁</span>
                                <span>View Details</span>
                            </a>

                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-outline">
                                <span>✎</span>
                                <span>Edit</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🛡️</div>
                <h3>No Admins Found</h3>
                <p>No admin matched your current search.</p>
            </div>
        @endforelse

        @if($admins->hasPages())
            <div style="margin-top: 24px;">
                {{ $admins->links() }}
            </div>
        @endif
    </x-card>
@endsection
