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
                    <div class="stats-label">Role</div>
                    <div class="stats-value" style="font-size: 24px;">{{ auth('admin')->user()->role }}</div>
                    <div class="stats-meta">Highest access level detected</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Status</div>
                    <div class="stats-value" style="font-size: 24px;">Active</div>
                    <div class="stats-meta">This account is currently enabled</div>
                </div>
                <div class="stats-icon">✅</div>
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
                    <div class="info-label">Role</div>
                    <div class="info-value">{{ auth('admin')->user()->role }}</div>
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
                            <h4>Role loaded</h4>
                            <p>Your administrator role has been loaded and the dashboard is ready for moderation features.</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">⚙️</div>
                        <div class="activity-body">
                            <h4>System prepared</h4>
                            <p>The panel structure is now ready to receive business account moderation, roles, and permissions.</p>
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
                        Business moderation, role controls, and platform management pages will appear here as we continue building the system.
                    </p>
                </div>
            </x-card>
        </div>
    </div>
@endsection
