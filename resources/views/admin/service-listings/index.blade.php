@extends('layouts.app')

@section('title', 'Service Listings')

@section('content')
    <x-page-title
        title="Service Listings"
        subtitle="Review submitted listings, search by title, and filter by mode before opening the full details."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Super Admin Only</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Listings</div>
                    <div class="stats-value">{{ $counts['total'] }}</div>
                    <div class="stats-meta">All submitted listings</div>
                </div>
                <div class="stats-icon">📋</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Pending</div>
                    <div class="stats-value">{{ $counts['pending'] }}</div>
                    <div class="stats-meta">Waiting for review</div>
                </div>
                <div class="stats-icon">⏳</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Approved</div>
                    <div class="stats-value">{{ $counts['approved'] }}</div>
                    <div class="stats-meta">Approved listings</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Rejected</div>
                    <div class="stats-value">{{ $counts['rejected'] }}</div>
                    <div class="stats-meta">Rejected listings</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.service-listings.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Search by Title</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="Search listing by title"
                    >
                </div>

                <div>
                    <label class="form-label">Filter by Mode</label>
                    <select name="mode" class="form-input">
                        <option value="">All Modes</option>
                        <option value="sell" {{ $mode === 'sell' ? 'selected' : '' }}>Sell</option>
                        <option value="rent" {{ $mode === 'rent' ? 'selected' : '' }}>Rent</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap;">
                <x-button type="submit" variant="primary">
                    <span>🔍</span>
                    <span>Search</span>
                </x-button>

                <a href="{{ route('admin.service-listings.index') }}" class="btn btn-outline">
                    <span>↺</span>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Listings</h2>
            <p class="section-subtitle">
                This page shows only brief listing information. Click any listing to open full details and moderate its status.
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
                            {{ ucfirst($listing->mode) }} •
                            {{ $listing->service?->name ?? 'N/A' }} •
                            {{ $listing->subcategory?->name ?? 'N/A' }}
                        </p>

                        <p style="margin-top: 6px;">
                            Owner: {{ $listing->businessAccount?->user?->full_name ?? 'N/A' }} •
                            Business: {{ $listing->businessAccount?->business_name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No Listings Found</h3>
                <p>
                    No service listings matched the current search or filter.
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
