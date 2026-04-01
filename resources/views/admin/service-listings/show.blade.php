@extends('layouts.app')

@section('title', 'Listing Details')

@section('content')
    <x-page-title
        title="Listing Details"
        subtitle="Review the full listing information and update its moderation status."
    >
        <x-slot:actions>
            <a href="{{ route('admin.service-listings.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Listings</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $serviceListing->title }}</h2>
                <p class="section-subtitle">
                    {{ ucfirst($serviceListing->mode) }} listing under {{ $serviceListing->service?->name ?? 'N/A' }}
                </p>
            </div>

            <span class="badge {{ $serviceListing->status_badge_class }}">
                {{ $serviceListing->status_label }}
            </span>
        </div>

        @if($serviceListing->main_photo_url)
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">Main Photo</h3>

                <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                    <img
                        src="{{ $serviceListing->main_photo_url }}"
                        alt="Main photo"
                        style="width: 100%; max-height: 420px; object-fit: cover; display: block;"
                    >
                </div>
            </div>
        @endif

        @if(!empty($serviceListing->sub_photo_urls))
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">Sub Photos</h3>

                <div class="grid grid-3">
                    @foreach($serviceListing->sub_photo_urls as $photo)
                        <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                            <a href="{{ $photo['url'] }}" target="_blank" style="display: block;">
                                <img
                                    src="{{ $photo['url'] }}"
                                    alt="Sub photo"
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
                <div class="info-label">Title</div>
                <div class="info-value">{{ $serviceListing->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value">{{ $serviceListing->description ?? 'No description provided.' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Mode</div>
                <div class="info-value">{{ ucfirst($serviceListing->mode) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Main Service</div>
                <div class="info-value">{{ $serviceListing->service?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Subcategory</div>
                <div class="info-value">{{ $serviceListing->subcategory?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price (USD)</div>
                <div class="info-value">${{ number_format((float) $serviceListing->price_usd, 2) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price (SYP)</div>
                <div class="info-value">{{ number_format((float) $serviceListing->price_syp, 2) }} SYP</div>
            </div>

            <div class="info-row">
                <div class="info-label">User</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->user?->full_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Business Account</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->business_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Business Type</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->businessType?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">City</div>
                <div class="info-value">{{ $serviceListing->businessAccount?->city?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $serviceListing->created_at->format('Y-m-d h:i A') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Metadata</div>
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
                        <span>No metadata available.</span>
                    @endif
                </div>
            </div>

            @if($serviceListing->isRejected() && $serviceListing->rejection_reason)
                <div class="info-row">
                    <div class="info-label">Rejection Reason</div>
                    <div class="info-value">{{ $serviceListing->rejection_reason }}</div>
                </div>
            @endif
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Moderation</h2>
            <p class="section-subtitle">
                Approve, reject, or return this listing to pending.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.service-listings.update-status', $serviceListing->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="1" {{ $serviceListing->status == 1 ? 'selected' : '' }}>Pending</option>
                        <option value="2" {{ $serviceListing->status == 2 ? 'selected' : '' }}>Approved</option>
                        <option value="3" {{ $serviceListing->status == 3 ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Rejection Reason (optional)</label>
                    <input
                        type="text"
                        name="rejection_reason"
                        class="form-input"
                        value="{{ old('rejection_reason', $serviceListing->rejection_reason) }}"
                        placeholder="Add a reason if rejecting this listing"
                    >
                </div>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>Update Status</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
