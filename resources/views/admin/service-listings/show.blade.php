@extends('layouts.app')

@section('title', __('messages.listing_details'))

@section('content')
    <x-page-title
        :title="__('messages.listing_details')"
        :subtitle="__('messages.listing_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.service-listings.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back_to_listings') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $serviceListing->title }}</h2>
                <p class="section-subtitle">
                    {{ $serviceListing->mode === 'sell' ? __('messages.sell') : __('messages.rent') }}
                    {{ __('messages.listing_under') }}
                    {{ $serviceListing->service?->name ?? __('messages.not_available') }}
                </p>
            </div>

            <span class="badge {{ $serviceListing->status_badge_class }}">
                {{ $serviceListing->status_label }}
            </span>
        </div>

        @if($serviceListing->main_photo_url)
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">{{ __('messages.main_photo') }}</h3>

                <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                    <img
                        src="{{ $serviceListing->main_photo_url }}"
                        alt="{{ __('messages.main_photo') }}"
                        style="width: 100%; max-height: 420px; object-fit: cover; display: block;"
                    >
                </div>
            </div>
        @endif

        @if(!empty($serviceListing->sub_photo_urls))
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">{{ __('messages.sub_photos') }}</h3>

                <div class="grid grid-3">
                    @foreach($serviceListing->sub_photo_urls as $photo)
                        <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                            <a href="{{ $photo['url'] }}" target="_blank" style="display: block;">
                                <img
                                    src="{{ $photo['url'] }}"
                                    alt="{{ __('messages.sub_photo') }}"
                                    style="width: 100%; height: 220px; object-fit: cover; display: block; cursor: pointer;"
                                >
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.title') }}</div>
                <div class="info-value">{{ $serviceListing->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.description') }}</div>
                <div class="info-value">{{ $serviceListing->description ?? __('messages.no_description') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.mode') }}</div>
                <div class="info-value">{{ $serviceListing->mode === 'sell' ? __('messages.sell') : __('messages.rent') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.main_service') }}</div>
                <div class="info-value">{{ $serviceListing->service?->name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.subcategory') }}</div>
                <div class="info-value">{{ $serviceListing->subcategory?->name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.price_usd') }}</div>
                <div class="info-value">${{ number_format((float) $serviceListing->price_usd, 2) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.price_syp') }}</div>
                <div class="info-value">{{ number_format((float) $serviceListing->price_syp, 2) }} SYP</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.user') }}</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->user?->full_name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.business_account') }}</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->business_name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.business_type') }}</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->businessType?->name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.city') }}</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->city?->name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.created_at') }}</div>
                <div class="info-value">{{ $serviceListing->created_at->format('Y-m-d h:i A') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.extra_information') }}</div>
                <div class="info-value" style="text-align: left;">
                    @if(!empty($serviceListing->metadata))
                        <div class="grid" style="gap: 10px;">
                            @foreach($serviceListing->metadata as $key => $value)
                                <div class="activity-item" style="padding: 10px 14px;">
                                    <div class="activity-body">
                                        <h4 style="margin-bottom: 4px;">{{ str_replace('_', ' ', ucfirst($key)) }}</h4>
                                        <p style="margin: 0;">
                                            @if(is_array($value))
                                                {{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span>{{ __('messages.no_metadata') }}</span>
                    @endif
                </div>
            </div>

            @if($serviceListing->isRejected() && $serviceListing->rejection_reason)
                <div class="info-row">
                    <div class="info-label">{{ __('messages.rejection_reason') }}</div>
                    <div class="info-value">{{ $serviceListing->rejection_reason }}</div>
                </div>
            @endif
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.moderation') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.moderation_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.service-listings.update-status', $serviceListing->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.status') }}</label>
                    <select name="status" class="form-input">
                        <option value="1" {{ $serviceListing->status == 1 ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                        <option value="2" {{ $serviceListing->status == 2 ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                        <option value="3" {{ $serviceListing->status == 3 ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">{{ __('messages.rejection_reason_optional') }}</label>
                    <input
                        type="text"
                        name="rejection_reason"
                        class="form-input"
                        value="{{ old('rejection_reason', $serviceListing->rejection_reason) }}"
                        placeholder="{{ __('messages.add_reason_if_rejecting_listing') }}"
                    >
                </div>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_status') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
