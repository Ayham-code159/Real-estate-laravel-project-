@extends('layouts.app')

@section('title', __('messages.business_account_details'))

@section('content')
    <x-page-title
        :title="__('messages.business_account_details')"
        :subtitle="__('messages.business_account_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $businessAccount->business_name }}</h2>
                <p class="section-subtitle">
                    {{ $businessAccount->businessType->name }} • {{ $businessAccount->city->name }}
                </p>
            </div>

            <span class="badge {{ $businessAccount->status_badge_class }}">
                {{ $businessAccount->status_label }}
            </span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.owner') }}</div>
                <div class="info-value">{{ $user->full_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}</div>
                <div class="info-value">{{ $user->email ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.phone') }}</div>
                <div class="info-value">{{ $user->phone ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.business_type') }}</div>
                <div class="info-value">{{ $businessAccount->businessType->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.city') }}</div>
                <div class="info-value">{{ $businessAccount->city->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.location') }}</div>
                <div class="info-value">
                    {{ $businessAccount->location_label ?? __('messages.not_available') }}

                    @if($businessAccount->google_maps_url)
                        <div style="margin-top: 10px;">
                            <a href="{{ $businessAccount->google_maps_url }}" target="_blank" class="btn btn-outline">
                                🗺 {{ __('messages.open_map') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.listings') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.listings_subtitle_under_account') }}
            </p>
        </div>

        @forelse($businessAccount->serviceListings as $listing)
            <a href="{{ route('admin.service-listings.show', $listing->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">📦</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $listing->title }}</h4>
                            <span class="badge {{ $listing->status_badge_class }}">
                                {{ $listing->status_label }}
                            </span>
                        </div>

                        <p style="margin-top: 6px;">
                            {{ $listing->mode === 'sell' ? __('messages.sell') : __('messages.rent') }} •
                            {{ $listing->service?->name ?? __('messages.not_available') }} •
                            {{ $listing->subcategory?->name ?? __('messages.not_available') }}
                        </p>

                        <p style="margin-top: 4px;">
                            ${{ number_format((float) $listing->price_usd, 2) }} •
                            {{ number_format((float) $listing->price_syp, 2) }} SYP
                        </p>

                        <p style="margin-top: 4px;">
                            Location: {{ $listing->location_label ?? __('messages.not_available') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>{{ __('messages.no_listings_yet') }}</h3>
                <p>
                    {{ __('messages.no_listings_yet_subtitle') }}
                </p>
            </div>
        @endforelse

        <form method="POST"
              action="{{ route('admin.users.business-accounts.destroy', $businessAccount) }}"
              onsubmit="return confirm('{{ __('messages.confirm_delete_business_account') }}');"
              style="margin-top: 18px;">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_business_account') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
