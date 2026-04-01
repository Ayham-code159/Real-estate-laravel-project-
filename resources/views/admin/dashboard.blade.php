@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <x-page-title
        title="Admin Dashboard"
        subtitle="Welcome back. Here is a real overview of the current listings moderation flow and your admin session."
    >
        <x-slot:actions>
            <span class="badge badge-success">System Online</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Admin Name</div>
                    <div class="stats-value" style="font-size: 24px;">{{ auth('admin')->user()->name }}</div>
                    <div class="stats-meta">Current active admin session</div>
                </div>
                <div class="stats-icon">👤</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Access Level</div>
                    <div class="stats-value" style="font-size: 22px;">{{ auth('admin')->user()->permissionLabel() }}</div>
                    <div class="stats-meta">Current permission profile</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Pending Listings</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['pending'] }}</div>
                    <div class="stats-meta">Need moderation review</div>
                </div>
                <div class="stats-icon">⏳</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Last Login</div>
                    <div class="stats-value" style="font-size: 20px;">
                        {{ optional(auth('admin')->user()->last_login_at)->format('M d, Y') ?? 'First login' }}
                    </div>
                    <div class="stats-meta">
                        {{ optional(auth('admin')->user()->last_login_at)->format('h:i A') ?? 'No previous login' }}
                    </div>
                </div>
                <div class="stats-icon">🕒</div>
            </div>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Listings</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['total'] }}</div>
                    <div class="stats-meta">All submitted listings</div>
                </div>
                <div class="stats-icon">📋</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Approved</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['approved'] }}</div>
                    <div class="stats-meta">Approved listings</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Rejected</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['rejected'] }}</div>
                    <div class="stats-meta">Rejected listings</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Status</div>
                    <div class="stats-value" style="font-size: 24px;">Active</div>
                    <div class="stats-meta">This account is currently enabled</div>
                </div>
                <div class="stats-icon">⚡</div>
            </div>
        </div>
    </div>

    <div class="overview-grid">
        <x-card class="subtle-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 20px;">
                <div>
                    <h2 class="section-title">Account Overview</h2>
                    <p class="section-subtitle">
                        This section summarizes your current admin profile and session details.
                    </p>
                </div>

                <span class="badge badge-success">Active</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ auth('admin')->user()->name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ auth('admin')->user()->email }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Permission Level</div>
                    <div class="info-value">{{ auth('admin')->user()->permissionLabel() }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Last Login</div>
                    <div class="info-value">
                        {{ optional(auth('admin')->user()->last_login_at)->format('Y-m-d h:i A') ?? 'First login' }}
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-button type="submit" variant="danger">
                        <span>↩</span>
                        <span>Logout</span>
                    </x-button>
                </form>
            </div>
        </x-card>

        <div class="grid">
            <x-card class="subtle-panel">
                <h2 class="section-title">Recent Pending Listings</h2>
                <p class="section-subtitle">
                    The latest listings waiting for moderation review.
                </p>

                <div class="activity-list">
                    @forelse($recentPendingListings as $listing)
                        <a href="{{ route('admin.service-listings.show', $listing->id) }}" style="display: block;">
                            <div class="activity-item">
                                <div class="activity-icon">📦</div>
                                <div class="activity-body">
                                    <h4>{{ $listing->title }}</h4>
                                    <p>
                                        {{ $listing->businessAccount?->user?->full_name ?? 'N/A' }} •
                                        {{ $listing->businessAccount?->business_name ?? 'N/A' }} •
                                        {{ ucfirst($listing->mode) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">✅</div>
                            <h3>No Pending Listings</h3>
                            <p>
                                There are currently no listings waiting for moderation.
                            </p>
                        </div>
                    @endforelse
                </div>
            </x-card>

            <x-card class="subtle-panel">
                <h2 class="section-title">Quick Area</h2>
                <p class="section-subtitle" style="margin-bottom: 18px;">
                    Fast insight into the current moderation workload.
                </p>

                <div class="info-list">
                    <div class="info-row">
                        <div class="info-label">Total listings in system</div>
                        <div class="info-value">{{ $listingCounts['total'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Listings waiting review</div>
                        <div class="info-value">{{ $listingCounts['pending'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Approved listings</div>
                        <div class="info-value">{{ $listingCounts['approved'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Rejected listings</div>
                        <div class="info-value">{{ $listingCounts['rejected'] }}</div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
