@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
    <style>
        .dashboard-page {
            max-width: 1280px;
            margin: 0 auto;
        }

        .soft-card {
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(216, 200, 255, 0.48);
            border-radius: 26px;
            box-shadow: 0 12px 30px rgba(25, 18, 50, 0.045);
        }

        .dashboard-hero {
            display: grid;
            grid-template-columns: 1.4fr 0.9fr;
            gap: 18px;
            margin-bottom: 20px;
        }

        .hero-card {
            padding: 26px;
        }

        .hero-eyebrow {
            color: var(--primary);
            font-weight: 900;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .hero-title {
            margin: 0;
            font-size: 34px;
            font-weight: 950;
            letter-spacing: -0.035em;
            color: var(--text-main);
        }

        .hero-subtitle {
            margin-top: 10px;
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.6;
        }

        .hero-footer {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        .permission-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .logout-btn {
            border: 1px solid rgba(239, 68, 68, 0.25);
            background: rgba(254, 242, 242, 0.75);
            color: #DC2626;
            border-radius: 14px;
            padding: 11px 16px;
            font-weight: 850;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .logout-btn:hover {
            background: #DC2626;
            color: white;
            box-shadow: 0 12px 26px rgba(220, 38, 38, 0.18);
            transform: translateY(-1px);
        }

        .compact-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .compact-stat {
            padding: 18px;
        }

        .compact-stat-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .compact-stat-value {
            font-size: 27px;
            font-weight: 950;
            color: var(--text-main);
            line-height: 1;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 18px;
            margin-bottom: 20px;
        }

        .panel-card {
            padding: 24px;
        }

        .panel-head {
            margin-bottom: 18px;
        }

        .panel-title {
            margin: 0 0 6px;
            font-size: 24px;
            font-weight: 950;
            letter-spacing: -0.02em;
        }

        .panel-subtitle {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.55;
            font-size: 14px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .quick-action {
            text-decoration: none;
            padding: 15px;
            border-radius: 20px;
            background: rgba(250, 248, 255, 0.72);
            border: 1px solid rgba(216, 200, 255, 0.56);
            transition: 0.2s ease;
            display: flex;
            gap: 12px;
            align-items: center;
            color: var(--text-main);
        }

        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(111, 60, 195, 0.10);
            border-color: rgba(139, 92, 246, 0.38);
            background: rgba(255, 255, 255, 0.9);
        }

        .quick-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 15px;
            background: rgba(124, 58, 237, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex: 0 0 auto;
        }

        .quick-action-title {
            font-weight: 950;
            margin-bottom: 2px;
            font-size: 15px;
        }

        .quick-action-meta {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .activity-item {
            padding: 13px;
            border-radius: 18px;
            background: rgba(250, 248, 255, 0.65);
            border: 1px solid rgba(216, 200, 255, 0.48);
            transition: 0.2s ease;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
        }

        .activity-title {
            font-weight: 900;
            margin-bottom: 5px;
            line-height: 1.35;
        }

        .activity-meta {
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .overview-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .overview-card {
            padding: 18px;
        }

        .overview-label {
            color: var(--text-muted);
            font-weight: 800;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .overview-value {
            font-size: 27px;
            font-weight: 950;
            color: var(--text-main);
        }

        @media (max-width: 1100px) {
            .dashboard-hero,
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .overview-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .compact-stats,
            .quick-actions,
            .overview-row {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 30px;
            }
        }
    </style>

    <div class="dashboard-page">
        <div class="dashboard-hero">
            <div class="soft-card hero-card">
                <div class="hero-eyebrow">{{ __('messages.dashboard') }}</div>

                <h1 class="hero-title">
                    Welcome back, {{ $admin->name }}
                </h1>

                <p class="hero-subtitle">
                    A clean overview of your moderation workspace and available admin actions.
                </p>

                <div class="hero-footer">
                    <div class="permission-badges">
                        @if($admin->isSuperAdmin())
                            <span class="badge badge-danger">👑 Super Admin</span>
                        @else
                            @forelse($admin->permissionLabels() as $permission)
                                <span class="badge badge-primary">{{ $permission }}</span>
                            @empty
                                <span class="badge badge-warning">Basic Admin</span>
                            @endforelse
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            ↩ Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="compact-stats">
                <div class="soft-card compact-stat">
                    <div class="compact-stat-label">Pending Items</div>
                    <div class="compact-stat-value">{{ $stats['pending_items'] }}</div>
                </div>

                <div class="soft-card compact-stat">
                    <div class="compact-stat-label">Total Items</div>
                    <div class="compact-stat-value">{{ $stats['total_items'] }}</div>
                </div>

                <div class="soft-card compact-stat">
                    <div class="compact-stat-label">Total Users</div>
                    <div class="compact-stat-value">{{ $stats['total_users'] }}</div>
                </div>

                <div class="soft-card compact-stat">
                    <div class="compact-stat-label">Active Sliders</div>
                    <div class="compact-stat-value">{{ $stats['active_sliders'] }}</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="soft-card panel-card">
                <div class="panel-head">
                    <h2 class="panel-title">Quick Actions</h2>
                    <p class="panel-subtitle">
                        Jump directly to the sections you are allowed to manage.
                    </p>
                </div>

                <div class="quick-actions">
                    @if($admin->canManageItems())
                        <a href="{{ route('admin.items.index') }}" class="quick-action">
                            <div class="quick-action-icon">📦</div>
                            <div>
                                <div class="quick-action-title">Manage Items</div>
                                <div class="quick-action-meta">{{ $stats['pending_items'] }} pending review</div>
                            </div>
                        </a>
                    @endif

                    @if($admin->canManageSliders())
                        <a href="{{ route('admin.sliders.index') }}" class="quick-action">
                            <div class="quick-action-icon">🎞️</div>
                            <div>
                                <div class="quick-action-title">Manage Sliders</div>
                                <div class="quick-action-meta">{{ $stats['active_sliders'] }} active sliders</div>
                            </div>
                        </a>
                    @endif

                    @if($admin->canManageCategories())
                        <a href="{{ route('admin.categories.index') }}" class="quick-action">
                            <div class="quick-action-icon">🗂️</div>
                            <div>
                                <div class="quick-action-title">Manage Categories</div>
                                <div class="quick-action-meta">Categories and dynamic fields</div>
                            </div>
                        </a>
                    @endif

                    @if($admin->canManageBusinessAccounts())
                        <a href="{{ route('admin.business-accounts.index') }}" class="quick-action">
                            <div class="quick-action-icon">🏢</div>
                            <div>
                                <div class="quick-action-title">Business Accounts</div>
                                <div class="quick-action-meta">{{ $stats['pending_business_accounts'] }} pending review</div>
                            </div>
                        </a>
                    @endif

                    @if($admin->canManageUsers())
                        <a href="{{ route('admin.users.index') }}" class="quick-action">
                            <div class="quick-action-icon">👤</div>
                            <div>
                                <div class="quick-action-title">Manage Users</div>
                                <div class="quick-action-meta">{{ $stats['total_users'] }} registered users</div>
                            </div>
                        </a>
                    @endif

                    @if($admin->canManageBusinessTypes())
                        <a href="{{ route('admin.master-data.business-types.index') }}" class="quick-action">
                            <div class="quick-action-icon">🏷️</div>
                            <div>
                                <div class="quick-action-title">Business Types</div>
                                <div class="quick-action-meta">Manage business type data</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <div class="soft-card panel-card">
                <div class="panel-head">
                    <h2 class="panel-title">Recent Pending Items</h2>
                    <p class="panel-subtitle">
                        Latest items waiting for moderation.
                    </p>
                </div>

                <div class="activity-list">
                    @forelse($recentPendingItems as $item)
                        <a href="{{ route('admin.items.show', $item) }}" class="activity-item" style="text-decoration: none; color: inherit;">
                            <div class="activity-title">{{ $item->title }}</div>
                            <div class="activity-meta">
                                {{ $item->businessAccount?->business_name ?? 'N/A' }}
                                @if($item->category)
                                    • {{ $item->category->name }}
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="activity-item">
                            <div class="activity-title">No pending items</div>
                            <div class="activity-meta">Everything is clear for now.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="overview-row">
            <div class="soft-card overview-card">
                <div class="overview-label">Approved Items</div>
                <div class="overview-value">{{ $stats['approved_items'] }}</div>
            </div>

            <div class="soft-card overview-card">
                <div class="overview-label">Rejected Items</div>
                <div class="overview-value">{{ $stats['rejected_items'] }}</div>
            </div>

            <div class="soft-card overview-card">
                <div class="overview-label">Business Accounts</div>
                <div class="overview-value">{{ $stats['total_business_accounts'] }}</div>
            </div>

            <div class="soft-card overview-card">
                <div class="overview-label">Pending Accounts</div>
                <div class="overview-value">{{ $stats['pending_business_accounts'] }}</div>
            </div>
        </div>
    </div>
@endsection
