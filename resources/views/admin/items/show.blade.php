@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
    <x-page-title
        title="Item Details"
        subtitle="Review item information and update moderation status."
    >
        <x-slot:actions>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">
                ← Back
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $item->title }}</h2>
                <p class="section-subtitle">
                    {{ $item->category?->name ?? 'N/A' }}
                    @if($item->subcategory)
                        • {{ $item->subcategory->name }}
                    @endif
                </p>
            </div>

            <span class="badge {{ $item->status_badge_class }}">
                {{ $item->status_label }}
            </span>
        </div>

        @if($item->main_photo_url)
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">Main Photo</h3>

                <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                    <img
                        src="{{ $item->main_photo_url }}"
                        alt="Main Photo"
                        style="width: 100%; max-height: 420px; object-fit: cover; display: block;"
                    >
                </div>
            </div>
        @endif

        @if(!empty($item->sub_photo_urls))
            <div style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 14px; font-size: 20px;">Sub Photos</h3>

                <div class="grid grid-3">
                    @foreach($item->sub_photo_urls as $photo)
                        <div class="card" style="overflow: hidden; background: rgba(255,255,255,0.72);">
                            <a href="{{ $photo['url'] }}" target="_blank" style="display: block;">
                                <img
                                    src="{{ $photo['url'] }}"
                                    alt="Sub Photo"
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
                <div class="info-value">{{ $item->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value">{{ $item->description ?? 'No description' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Owner</div>
                <div class="info-value">{{ $item->businessAccount?->user?->full_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Business Account</div>
                <div class="info-value">{{ $item->businessAccount?->business_name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Category</div>
                <div class="info-value">{{ $item->category?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Subcategory</div>
                <div class="info-value">{{ $item->subcategory?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Item Type</div>
                <div class="info-value">{{ ucfirst($item->item_type) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price USD</div>
                <div class="info-value">${{ number_format((float) $item->price_usd, 2) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Price SYP</div>
                <div class="info-value">{{ number_format((float) $item->price_syp, 2) }} SYP</div>
            </div>

            <div class="info-row">
                <div class="info-label">Location</div>
                <div class="info-value">
                    {{ $item->location_label ?? 'N/A' }}

                    @if($item->google_maps_url)
                        <div style="margin-top: 10px;">
                            <a href="{{ $item->google_maps_url }}" target="_blank" class="btn btn-outline">
                                🗺 Open Map
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Dynamic Values</div>
                <div class="info-value" style="text-align: left;">
                    @if(!empty($item->dynamic_values))
                        <div class="grid" style="gap: 10px;">
                            @foreach($item->dynamic_values as $key => $value)
                                <div class="activity-item" style="padding: 10px 14px;">
                                    <div class="activity-body">
                                        <h4 style="margin-bottom: 4px;">{{ str_replace('_', ' ', ucfirst($key)) }}</h4>
                                        <p style="margin: 0;">
                                            @if(is_bool($value))
                                                {{ $value ? 'Yes' : 'No' }}
                                            @elseif(is_array($value))
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
                        <span>No dynamic values</span>
                    @endif
                </div>
            </div>

            @if($item->isRejected() && $item->rejection_reason)
                <div class="info-row">
                    <div class="info-label">Rejection Reason</div>
                    <div class="info-value">{{ $item->rejection_reason }}</div>
                </div>
            @endif
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Moderation</h2>
            <p class="section-subtitle">
                Approve or reject this item.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.items.update-status', $item) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        @foreach(\App\Models\Item::statuses() as $value => $label)
                            <option value="{{ $value }}" {{ $item->status == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Rejection Reason</label>
                    <input
                        type="text"
                        name="rejection_reason"
                        class="form-input"
                        value="{{ old('rejection_reason', $item->rejection_reason) }}"
                        placeholder="Add reason if rejecting this item"
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

    <x-card class="subtle-panel">
        <h2 class="section-title">Danger Zone</h2>
        <p class="section-subtitle" style="margin-bottom: 18px;">
            Delete this item completely.
        </p>

        <form method="POST"
              action="{{ route('admin.items.destroy', $item) }}"
              onsubmit="return confirm('Are you sure you want to delete this item?');">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>Delete Item</span>
            </x-button>
        </form>
    </x-card>
@endsection
