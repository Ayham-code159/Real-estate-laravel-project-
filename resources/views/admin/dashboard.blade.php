@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <x-page-title
        title="Admin Dashboard"
        subtitle="Welcome back. Here is a quick overview of your admin account, current session, and platform activity."
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
                    <div class="stats-value" style="font-size: 24px;">{{ auth('admin')->user()->permissionLabel() }}</div>
                    <div class="stats-meta">Current permission profile</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Approved Services</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $serviceCounts['total_approved_services'] }}</div>
                    <div class="stats-meta">
                        Sell: {{ $serviceCounts['approved_sell_services'] }} • Rent: {{ $serviceCounts['approved_rent_services'] }}
                    </div>
                </div>
                <div class="stats-icon">🧰</div>
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
                    <div class="info-label">Approved Sell Services</div>
                    <div class="info-value">{{ $serviceCounts['approved_sell_services'] }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Approved Rent Services</div>
                    <div class="info-value">{{ $serviceCounts['approved_rent_services'] }}</div>
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
                <h2 class="section-title">Recent Activity</h2>
                <p class="section-subtitle">
                    A lightweight activity snapshot to make the dashboard feel more alive and informative.
                </p>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">🔐</div>
                        <div class="activity-body">
                            <h4>Successful login</h4>
                            <p>Your current session was opened successfully and passed the admin guard checks.</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">🛡️</div>
                        <div class="activity-body">
                            <h4>Permissions loaded</h4>
                            <p>Your administrator permissions were loaded successfully and the panel is ready for moderation tasks.</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">🧰</div>
                        <div class="activity-body">
                            <h4>Services moderation ready</h4>
                            <p>The system is now prepared to review and moderate sell and rent services.</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card class="subtle-panel">
                <h2 class="section-title">Quick Area</h2>
                <p class="section-subtitle" style="margin-bottom: 18px;">
                    This space is reserved for upcoming tools and actions.
                </p>

                <div class="empty-state">
                    <div class="empty-state-icon">✨</div>
                    <h3>More tools are coming</h3>
                    <p>
                        More advanced service moderation, media handling, and deeper analytics can be added here next.
                    </p>
                </div>
            </x-card>
        </div>
    </div>
@endsection
