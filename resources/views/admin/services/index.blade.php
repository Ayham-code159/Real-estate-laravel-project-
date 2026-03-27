@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <x-page-title
        title="Services"
        subtitle="Browse sell and rent services, search by title, and review submitted records."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Service Management</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Services</div>
                    <div class="stats-value">{{ $counts['total_services'] }}</div>
                    <div class="stats-meta">All sell and rent services</div>
                </div>
                <div class="stats-icon">🧰</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Pending</div>
                    <div class="stats-value">{{ $counts['pending_services'] }}</div>
                    <div class="stats-meta">Waiting for review</div>
                </div>
                <div class="stats-icon">⏳</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Approved</div>
                    <div class="stats-value">{{ $counts['approved_services'] }}</div>
                    <div class="stats-meta">Approved and visible</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Rejected</div>
                    <div class="stats-value">{{ $counts['rejected_services'] }}</div>
                    <div class="stats-meta">Rejected submissions</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.services.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Search by Title</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="Search service by title"
                    >
                </div>

                <div>
                    <label class="form-label">Filter by Main Type</label>
                    <select name="type" class="form-input">
                        <option value="">All Types</option>
                        <option value="sell" {{ $type === 'sell' ? 'selected' : '' }}>Sell</option>
                        <option value="rent" {{ $type === 'rent' ? 'selected' : '' }}>Rent</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap;">
                <x-button type="submit" variant="primary">
                    <span>🔍</span>
                    <span>Search</span>
                </x-button>

                <a href="{{ route('admin.services.index') }}" class="btn btn-outline">
                    <span>↺</span>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Services</h2>
            <p class="section-subtitle">
                Review service summaries, linked user accounts, business accounts, and moderation status.
            </p>
        </div>

        @forelse($services as $service)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">
                                {{ $service['title'] }}
                            </h3>
                            <p class="text-muted" style="margin: 0;">
                                {{ ucfirst($service['type']) }} • {{ $service['subtype_name'] }}
                            </p>
                        </div>

                        <span class="badge {{ $service['status_badge_class'] }}">
                            {{ $service['status_label'] }}
                        </span>
                    </div>

                    <div class="grid grid-3" style="margin-bottom: 16px;">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">User</div>
                            <div style="font-weight: 800;">{{ $service['user_name'] }}</div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Business Account</div>
                            <div style="font-weight: 800;">{{ $service['business_account_name'] }}</div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Created At</div>
                            <div style="font-weight: 800;">{{ $service['created_at']->format('Y-m-d h:i A') }}</div>
                        </div>
                    </div>

                    <a href="{{ route('admin.services.show', ['type' => $service['type'], 'id' => $service['id']]) }}"
                       class="btn btn-primary">
                        <span>👁</span>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🧰</div>
                <h3>No Services Found</h3>
                <p>
                    No service matched your current search or filter.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
