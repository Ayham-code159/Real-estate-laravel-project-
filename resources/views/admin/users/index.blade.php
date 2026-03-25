@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <x-page-title
        title="Users"
        subtitle="Browse all users, search by first or last name, and inspect their business accounts and offerings."
    >
        <x-slot:actions>
            <span class="badge badge-primary">User Management</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Users</div>
                    <div class="stats-value">{{ $counts['total_users'] }}</div>
                    <div class="stats-meta">Registered platform users</div>
                </div>
                <div class="stats-icon">👥</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">With Business Accounts</div>
                    <div class="stats-value">{{ $counts['users_with_business_accounts'] }}</div>
                    <div class="stats-meta">Users owning at least one business account</div>
                </div>
                <div class="stats-icon">🏢</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">With Approved Accounts</div>
                    <div class="stats-value">{{ $counts['users_with_approved_business_accounts'] }}</div>
                    <div class="stats-meta">Users ready to use offerings</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Offerings</div>
                    <div class="stats-value">{{ $counts['total_offerings'] }}</div>
                    <div class="stats-meta">Products, services, and rentals</div>
                </div>
                <div class="stats-icon">📦</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Search User</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="Search by first name or last name"
                    >
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <x-button type="submit" variant="primary">
                        <span>🔍</span>
                        <span>Search</span>
                    </x-button>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                        <span>↺</span>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Users</h2>
            <p class="section-subtitle">
                View user summaries, related business accounts, and quick profile details.
            </p>
        </div>

        @forelse($users as $user)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">
                                {{ $user->full_name }}
                            </h3>
                            <p class="text-muted" style="margin: 0;">
                                Username: {{ $user->username }}
                            </p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary">
                                <span>👁</span>
                                <span>View Details</span>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')

                                <x-button type="submit" variant="danger">
                                    <span>🗑</span>
                                    <span>Delete</span>
                                </x-button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-3">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Email / Phone</div>
                            <div style="font-weight: 800;">
                                {{ $user->email ?? $user->phone ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Business Accounts</div>
                            <div style="font-weight: 800;">
                                {{ $user->business_accounts_count }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Approved Accounts</div>
                            <div style="font-weight: 800;">
                                {{ $user->approved_business_accounts_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h3>No Users Found</h3>
                <p>
                    No users matched your current search.
                </p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div style="margin-top: 24px;">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
@endsection
