@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <x-page-title
        title="User Details"
        subtitle="Inspect this user's profile, business accounts, and offerings."
    >
        <x-slot:actions>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Users</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Business Accounts</div>
                    <div class="stats-value">{{ $counts['business_accounts_count'] }}</div>
                    <div class="stats-meta">Total linked business accounts</div>
                </div>
                <div class="stats-icon">🏢</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Approved</div>
                    <div class="stats-value">{{ $counts['approved_business_accounts_count'] }}</div>
                    <div class="stats-meta">Approved business accounts</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Rejected</div>
                    <div class="stats-value">{{ $counts['rejected_business_accounts_count'] }}</div>
                    <div class="stats-meta">Rejected business accounts</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Offerings</div>
                    <div class="stats-value">{{ $counts['offerings_count'] }}</div>
                    <div class="stats-meta">All offerings under this user</div>
                </div>
                <div class="stats-icon">📦</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">Profile Overview</h2>
                <p class="section-subtitle">
                    Main account information and current active business account.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')

                <x-button type="submit" variant="danger">
                    <span>🗑</span>
                    <span>Delete User</span>
                </x-button>
            </form>
        </div>

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $user->full_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Username</div>
                <div class="info-value">{{ $user->username }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $user->phone ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Active Business Account</div>
                <div class="info-value">
                    {{ $user->activeBusinessAccount?->business_name ?? 'No active business account selected' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Joined At</div>
                <div class="info-value">{{ $user->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Business Accounts & Offerings</h2>
            <p class="section-subtitle">
                Review all business accounts for this user and inspect offerings under each account.
            </p>
        </div>

        @forelse($user->businessAccounts as $businessAccount)
            <div class="card" style="margin-bottom: 20px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">
                                {{ $businessAccount->business_name }}
                            </h3>
                            <p class="text-muted" style="margin: 0;">
                                {{ $businessAccount->businessType->name }} • {{ $businessAccount->city->name }}
                            </p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            <span class="badge {{ $businessAccount->status_badge_class }}">
                                {{ $businessAccount->status_label }}
                            </span>

                            <form method="POST"
                                  action="{{ route('admin.users.business-accounts.destroy', $businessAccount) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this business account?');">
                                @csrf
                                @method('DELETE')

                                <x-button type="submit" variant="danger">
                                    <span>🗑</span>
                                    <span>Delete Business Account</span>
                                </x-button>
                            </form>
                        </div>
                    </div>

                    @if($businessAccount->isRejected() && $businessAccount->rejection_reason)
                        <div class="alert alert-danger" style="margin-bottom: 16px;">
                            <strong>Rejection reason:</strong> {{ $businessAccount->rejection_reason }}
                        </div>
                    @endif

                    <div style="margin-bottom: 18px;">
                        <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Created At</div>
                        <div style="font-weight: 800;">{{ $businessAccount->created_at->format('Y-m-d h:i A') }}</div>
                    </div>

                    <div>
                        <h4 style="margin: 0 0 14px; font-size: 18px;">Offerings</h4>

                        @forelse($businessAccount->offerings as $offering)
                            <div class="activity-item" style="margin-bottom: 12px;">
                                <div class="activity-icon">📦</div>

                                <div class="activity-body" style="width: 100%;">
                                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                                        <h4 style="margin: 0;">{{ $offering->title }}</h4>

                                        <span class="badge badge-primary">
                                            {{ ucfirst($offering->type) }}
                                        </span>
                                    </div>

                                    <p style="margin-top: 8px;">
                                        {{ $offering->description ?? 'No description provided.' }}
                                    </p>

                                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px;">
                                        <div>
                                            <span class="text-muted">Price:</span>
                                            <strong>{{ $offering->price ?? 'N/A' }}</strong>
                                        </div>

                                        <div>
                                            <span class="text-muted">Created:</span>
                                            <strong>{{ $offering->created_at->format('Y-m-d h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <h3>No Offerings</h3>
                                <p>
                                    This business account does not have offerings yet.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏢</div>
                <h3>No Business Accounts</h3>
                <p>
                    This user does not have any business accounts yet.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
