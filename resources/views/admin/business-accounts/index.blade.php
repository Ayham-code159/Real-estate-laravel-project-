@extends('layouts.app')

@section('title', 'Business Accounts')

@section('content')
    <x-page-title
        title="Business Accounts"
        subtitle="Review user-created business accounts, inspect their details, and update moderation status."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Management Area</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Total Accounts</div>
                    <div class="stats-value">{{ $counts['total'] }}</div>
                    <div class="stats-meta">All business accounts</div>
                </div>
                <div class="stats-icon">📦</div>
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
                    <div class="stats-meta">Ready for service flow</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">Rejected</div>
                    <div class="stats-value">{{ $counts['rejected'] }}</div>
                    <div class="stats-meta">Needs correction</div>
                </div>
                <div class="stats-icon">❌</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Business Accounts</h2>
            <p class="section-subtitle">
                Each record shows the business owner, contact reference, type, city, and current moderation state.
            </p>
        </div>

        @forelse($businessAccounts as $businessAccount)
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

                    <div class="grid grid-2" style="margin-bottom: 18px;">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Owner Name</div>
                            <div style="font-weight: 800;">
                                {{ $businessAccount->user->full_name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Owner Email / Phone</div>
                            <div style="font-weight: 800;">
                                {{ $businessAccount->user->email ?? $businessAccount->user->phone ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($businessAccount->isRejected() && $businessAccount->rejection_reason)
                        <div class="alert alert-danger" style="margin-top: 6px;">
                            <strong>Rejection reason:</strong> {{ $businessAccount->rejection_reason }}
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.business-accounts.update-status', $businessAccount) }}"
                          style="margin-top: 18px;">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-2" style="align-items: end;">
                            <div>
                                <label class="form-label">Update Status</label>
                                <select name="status" class="form-input">
                                    @foreach(\App\Models\BusinessAccount::statuses() as $value => $label)
                                        <option value="{{ $value }}" {{ $businessAccount->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Rejection Reason (optional)</label>
                                <input
                                    type="text"
                                    name="rejection_reason"
                                    class="form-input"
                                    value="{{ old('rejection_reason', $businessAccount->rejection_reason) }}"
                                    placeholder="Add a reason if rejecting this account"
                                >
                            </div>
                        </div>

                        <div style="margin-top: 16px;">
                            <x-button type="submit" variant="primary">
                                <span>💾</span>
                                <span>Update Status</span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏢</div>
                <h3>No Business Accounts Yet</h3>
                <p>
                    User-created business accounts will appear here once they are submitted.
                </p>
            </div>
        @endforelse

        @if($businessAccounts->hasPages())
            <div style="margin-top: 24px;">
                {{ $businessAccounts->links() }}
            </div>
        @endif
    </x-card>
@endsection
