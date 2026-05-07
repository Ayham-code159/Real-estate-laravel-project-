@extends('layouts.app')

@section('title', __('messages.business_accounts'))

@section('content')
    <x-page-title
        :title="__('messages.business_accounts')"
        :subtitle="__('messages.business_accounts_page_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-primary">{{ __('messages.management_area') }}</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.total_accounts') }}</div>
                    <div class="stats-value">{{ $counts['total'] }}</div>
                    <div class="stats-meta">{{ __('messages.business_accounts') }}</div>
                </div>
                <div class="stats-icon">📦</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.pending') }}</div>
                    <div class="stats-value">{{ $counts['pending'] }}</div>
                    <div class="stats-meta">{{ __('messages.waiting_for_review') }}</div>
                </div>
                <div class="stats-icon">⏳</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.approved') }}</div>
                    <div class="stats-value">{{ $counts['approved'] }}</div>
                    <div class="stats-meta">{{ __('messages.ready_for_listing_flow') }}</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.rejected') }}</div>
                    <div class="stats-value">{{ $counts['rejected'] }}</div>
                    <div class="stats-meta">{{ __('messages.needs_correction') }}</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_business_accounts') }}</h2>
            <p class="section-subtitle">
                Basic overview. Open an account to view full details and update status.
            </p>
        </div>

        @forelse($businessAccounts as $businessAccount)
            <a href="{{ route('admin.business-accounts.show', $businessAccount) }}" style="display: block; margin-bottom: 18px;">
                <div class="card" style="background: rgba(255,255,255,0.72);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
                            <div>
                                <h3 style="margin: 0 0 6px; font-size: 22px;">
                                    {{ $businessAccount->business_name }}
                                </h3>

                                <p class="text-muted" style="margin: 0;">
                                    {{ $businessAccount->businessType->name }} • {{ $businessAccount->city->name }}
                                </p>
                            </div>

                            <span class="badge {{ $businessAccount->status_badge_class }}">
                                {{ $businessAccount->status_label }}
                            </span>
                        </div>

                        <div class="grid grid-4" style="margin-top: 20px;">
                            <div>
                                <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.owner_name') }}</div>
                                <div style="font-weight: 800;">
                                    {{ $businessAccount->user->full_name }}
                                </div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.email_or_phone') }}</div>
                                <div style="font-weight: 800;">
                                    {{ $businessAccount->user->email ?? $businessAccount->user->phone ?? __('messages.not_available') }}
                                </div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Location</div>
                                <div style="font-weight: 800;">
                                    {{ $businessAccount->location_label ?? __('messages.not_available') }}
                                </div>

                                @if($businessAccount->google_maps_url)
                                    <object>
                                        <a href="{{ $businessAccount->google_maps_url }}" target="_blank" class="btn btn-outline" style="margin-top: 8px; padding: 9px 12px;">
                                            🗺 Open Map
                                        </a>
                                    </object>
                                @endif
                            </div>

                            <div>
                                <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.listings_count') }}</div>
                                <div style="font-weight: 800;">
                                    {{ $businessAccount->service_listings_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏢</div>
                <h3>{{ __('messages.no_business_accounts_yet') }}</h3>
                <p>{{ __('messages.no_business_accounts_yet_subtitle') }}</p>
            </div>
        @endforelse

        @if($businessAccounts->hasPages())
            <div style="margin-top: 24px;">
                {{ $businessAccounts->links() }}
            </div>
        @endif
    </x-card>
@endsection
