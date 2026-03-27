@extends('layouts.app')

@section('title', 'Service Details')

@section('content')
    <x-page-title
        title="Service Details"
        subtitle="Inspect this service and update its moderation status."
    >
        <x-slot:actions>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Services</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $service->title }}</h2>
                <p class="section-subtitle">
                    {{ ucfirst($type) }} service • {{ $service->subtype?->name ?? 'N/A' }}
                </p>
            </div>

            <span class="badge {{ $service->status_badge_class }}">
                {{ $service->status_label }}
            </span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Main Type</div>
                <div class="info-value">{{ ucfirst($type) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Subtype</div>
                <div class="info-value">{{ $service->subtype?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Title</div>
                <div class="info-value">{{ $service->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value">{{ $service->description ?? 'No description provided.' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price (USD)</div>
                <div class="info-value">${{ number_format((float) $service->price_usd, 2) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price (SYP)</div>
                <div class="info-value">{{ number_format((float) $service->price_syp, 2) }} SYP</div>
            </div>

            <div class="info-row">
                <div class="info-label">User</div>
                <div class="info-value">{{ $service->businessAccount?->user?->full_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Business Account</div>
                <div class="info-value">{{ $service->businessAccount?->business_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Business Type</div>
                <div class="info-value">{{ $service->businessAccount?->businessType?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">City</div>
                <div class="info-value">{{ $service->businessAccount?->city?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $service->created_at->format('Y-m-d h:i A') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Metadata</div>
                <div class="info-value" style="text-align: left;">
                    @if(!empty($service->metadata))
                        <div class="grid" style="gap: 10px;">
                            @foreach($service->metadata as $key => $value)
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

            @if($service->isRejected() && $service->rejection_reason)
                <div class="info-row">
                    <div class="info-label">Rejection Reason</div>
                    <div class="info-value">{{ $service->rejection_reason }}</div>
                </div>
            @endif
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Moderation</h2>
            <p class="section-subtitle">
                Update this service status and optionally provide a rejection reason.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.services.update-status', ['type' => $type, 'id' => $service->id]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="1" {{ $service->status == 1 ? 'selected' : '' }}>Pending</option>
                        <option value="2" {{ $service->status == 2 ? 'selected' : '' }}>Approved</option>
                        <option value="3" {{ $service->status == 3 ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Rejection Reason (optional)</label>
                    <input
                        type="text"
                        name="rejection_reason"
                        class="form-input"
                        value="{{ old('rejection_reason', $service->rejection_reason) }}"
                        placeholder="Add a reason if rejecting this service"
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
