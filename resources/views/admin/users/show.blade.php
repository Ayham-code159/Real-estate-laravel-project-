@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <x-page-title
        title="User Details"
        subtitle="Review this user, their business accounts, and all listings created under those accounts."
    >
        <x-slot:actions>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Users</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $user->full_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Username</div>
                <div class="info-value">{{ $user->username ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $user->phone ?? 'N/A' }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Business Accounts and Listings</h2>
            <p class="section-subtitle">
                Each business account appears with its service listings underneath it.
            </p>
        </div>

        @forelse($user->businessAccounts as $businessAccount)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
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

                    @if($businessAccount->serviceListings->count())
                        <div style="margin-top: 18px;">
                            <h4 style="margin: 0 0 12px;">Listings</h4>

                            @foreach($businessAccount->serviceListings as $listing)
                                <div class="activity-item" style="margin-bottom: 10px;">
                                    <div class="activity-icon">📦</div>
                                    <div class="activity-body" style="width: 100%;">
                                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                                            <h4 style="margin: 0;">{{ $listing->title }}</h4>
                                            <span class="badge {{ $listing->status_badge_class }}">
                                                {{ $listing->status_label }}
                                            </span>
                                        </div>

                                        <p style="margin-top: 6px;">
                                            {{ ucfirst($listing->mode) }} •
                                            {{ $listing->service?->name ?? 'N/A' }} •
                                            {{ $listing->subcategory?->name ?? 'N/A' }}
                                        </p>

                                        <p style="margin-top: 4px;">
                                            ${{ number_format((float) $listing->price_usd, 2) }} •
                                            {{ number_format((float) $listing->price_syp, 2) }} SYP
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="margin-top: 16px;">
                            <div class="empty-state-icon">📭</div>
                            <h3>No Listings Yet</h3>
                            <p>
                                This business account does not have any listings yet.
                            </p>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.users.business-accounts.destroy', $businessAccount) }}"
                          style="margin-top: 16px;">
                        @csrf
                        @method('DELETE')

                        <x-button type="submit" variant="danger">
                            <span>🗑</span>
                            <span>Delete Business Account</span>
                        </x-button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h3>No Business Accounts</h3>
                <p>
                    This user has not created any business accounts yet.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
