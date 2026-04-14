@extends('layouts.app')

@section('title', __('messages.listings'))

@section('content')
    <x-page-title
        :title="__('messages.listings')"
        :subtitle="__('messages.listings_page_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-primary">{{ __('messages.super_admin_only') }}</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.total_listings') }}</div>
                    <div class="stats-value">{{ $counts['total'] }}</div>
                    <div class="stats-meta">{{ __('messages.all_submitted_listings') }}</div>
                </div>
                <div class="stats-icon">📋</div>
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
                    <div class="stats-meta">{{ __('messages.approved_listings_meta') }}</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.rejected') }}</div>
                    <div class="stats-value">{{ $counts['rejected'] }}</div>
                    <div class="stats-meta">{{ __('messages.rejected_listings_meta') }}</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.service-listings.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.search_by_title') }}</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="{{ __('messages.search_listing_by_title') }}"
                    >
                </div>

                <div>
                    <label class="form-label">{{ __('messages.filter_by_mode') }}</label>
                    <select name="mode" class="form-input">
                        <option value="">{{ __('messages.all_modes') }}</option>
                        <option value="sell" {{ $mode === 'sell' ? 'selected' : '' }}>{{ __('messages.sell') }}</option>
                        <option value="rent" {{ $mode === 'rent' ? 'selected' : '' }}>{{ __('messages.rent') }}</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap;">
                <x-button type="submit" variant="primary">
                    <span>🔍</span>
                    <span>{{ __('messages.search') }}</span>
                </x-button>

                <a href="{{ route('admin.service-listings.index') }}" class="btn btn-outline">
                    <span>↺</span>
                    <span>{{ __('messages.reset') }}</span>
                </a>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_listings') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.all_listings_subtitle') }}
            </p>
        </div>

        @forelse($serviceListings as $listing)
            <a href="{{ route('admin.service-listings.show', $listing->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">📦</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $listing->title }}</h4>
                            <span class="badge {{ $listing->status_badge_class }}">{{ $listing->status_label }}</span>
                        </div>

                        <p style="margin-top: 8px;">
                            {{ $listing->mode === 'sell' ? __('messages.sell') : __('messages.rent') }} •
                            {{ $listing->service?->name ?? __('messages.not_available') }} •
                            {{ $listing->subcategory?->name ?? __('messages.not_available') }}
                        </p>

                        <p style="margin-top: 6px;">
                            {{ __('messages.owner') }}: {{ $listing->businessAccount?->user?->full_name ?? __('messages.not_available') }} •
                            {{ __('messages.business_account') }}: {{ $listing->businessAccount?->business_name ?? __('messages.not_available') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>{{ __('messages.no_listings_found') }}</h3>
                <p>
                    {{ __('messages.no_listings_found_subtitle') }}
                </p>
            </div>
        @endforelse

        @if($serviceListings->hasPages())
            <div style="margin-top: 24px;">
                {{ $serviceListings->links() }}
            </div>
        @endif
    </x-card>
@endsection
