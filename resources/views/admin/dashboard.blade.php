@extends('layouts.app')

@section('title', __('messages.admin_dashboard'))

@section('content')
    <x-page-title
        :title="__('messages.admin_dashboard')"
        :subtitle="__('messages.dashboard_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-success">{{ __('messages.system_online') }}</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.admin_name') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ auth('admin')->user()->name }}</div>
                    <div class="stats-meta">{{ __('messages.current_active_admin_session') }}</div>
                </div>
                <div class="stats-icon">👤</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.access_level') }}</div>
                    <div class="stats-value" style="font-size: 22px;">{{ auth('admin')->user()->permissionLabel() }}</div>
                    <div class="stats-meta">{{ __('messages.current_permission_profile') }}</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.pending_listings') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['pending'] }}</div>
                    <div class="stats-meta">{{ __('messages.need_moderation_review') }}</div>
                </div>
                <div class="stats-icon">⏳</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.last_login') }}</div>
                    <div class="stats-value" style="font-size: 20px;">
                        {{ optional(auth('admin')->user()->last_login_at)->format('M d, Y') ?? __('messages.first_login') }}
                    </div>
                    <div class="stats-meta">
                        {{ optional(auth('admin')->user()->last_login_at)->format('h:i A') ?? __('messages.no_previous_login') }}
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
                    <div class="stats-label">{{ __('messages.total_listings') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['total'] }}</div>
                    <div class="stats-meta">{{ __('messages.all_submitted_listings') }}</div>
                </div>
                <div class="stats-icon">📋</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.approved') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['approved'] }}</div>
                    <div class="stats-meta">{{ __('messages.approved_listings_meta') }}</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.rejected') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ $listingCounts['rejected'] }}</div>
                    <div class="stats-meta">{{ __('messages.rejected_listings_meta') }}</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.status') }}</div>
                    <div class="stats-value" style="font-size: 24px;">{{ __('messages.active') }}</div>
                    <div class="stats-meta">{{ __('messages.account_enabled') }}</div>
                </div>
                <div class="stats-icon">⚡</div>
            </div>
        </div>
    </div>

    <div class="overview-grid">
        <x-card class="subtle-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 20px;">
                <div>
                    <h2 class="section-title">{{ __('messages.account_overview') }}</h2>
                    <p class="section-subtitle">
                        {{ __('messages.account_overview_subtitle') }}
                    </p>
                </div>

                <span class="badge badge-success">{{ __('messages.active') }}</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <div class="info-label">{{ __('messages.full_name') }}</div>
                    <div class="info-value">{{ auth('admin')->user()->name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">{{ __('messages.email') }}</div>
                    <div class="info-value">{{ auth('admin')->user()->email }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">{{ __('messages.permission_level') }}</div>
                    <div class="info-value">{{ auth('admin')->user()->permissionLabel() }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">{{ __('messages.last_login') }}</div>
                    <div class="info-value">
                        {{ optional(auth('admin')->user()->last_login_at)->format('Y-m-d h:i A') ?? __('messages.first_login') }}
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-button type="submit" variant="danger">
                        <span>↩</span>
                        <span>{{ __('messages.logout') }}</span>
                    </x-button>
                </form>
            </div>
        </x-card>

        <div class="grid">
            <x-card class="subtle-panel">
                <h2 class="section-title">{{ __('messages.recent_pending_listings') }}</h2>
                <p class="section-subtitle">
                    {{ __('messages.recent_pending_listings_subtitle') }}
                </p>

                <div class="activity-list">
                    {{-- @forelse($recentPendingListings as $listing)
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
                            <h3>{{ __('messages.no_pending_listings') }}</h3>
                            <p>
                                {{ __('messages.no_pending_listings_subtitle') }}
                            </p>
                        </div>
                    @endforelse --}}
                </div>
            </x-card>

            <x-card class="subtle-panel">
                <h2 class="section-title">{{ __('messages.quick_area') }}</h2>
                <p class="section-subtitle" style="margin-bottom: 18px;">
                    {{ __('messages.quick_area_subtitle') }}
                </p>

                <div class="info-list">
                    <div class="info-row">
                        <div class="info-label">{{ __('messages.total_listings_in_system') }}</div>
                        <div class="info-value">{{ $listingCounts['total'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">{{ __('messages.listings_waiting_review') }}</div>
                        <div class="info-value">{{ $listingCounts['pending'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">{{ __('messages.approved') }}</div>
                        <div class="info-value">{{ $listingCounts['approved'] }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">{{ __('messages.rejected') }}</div>
                        <div class="info-value">{{ $listingCounts['rejected'] }}</div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
