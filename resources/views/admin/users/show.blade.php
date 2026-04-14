@extends('layouts.app')

@section('title', __('messages.user_details'))

@section('content')
    <x-page-title
        :title="__('messages.user_details')"
        :subtitle="__('messages.user_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.full_name') }}</div>
                <div class="info-value">{{ $user->full_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.username') }}</div>
                <div class="info-value">{{ $user->username ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}</div>
                <div class="info-value">{{ $user->email ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.phone') }}</div>
                <div class="info-value">{{ $user->phone ?? __('messages.not_available') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.business_accounts') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.business_accounts_only_subtitle') }}
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

                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <div class="text-muted">
                            {{ __('messages.open_this_business_account_to_view_its_listings') }}
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.users.business-accounts.show', [$user, $businessAccount]) }}" class="btn btn-primary">
                                <span>📂</span>
                                <span>{{ __('messages.open_business_account') }}</span>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.users.business-accounts.destroy', $businessAccount) }}"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete_business_account') }}');">
                                @csrf
                                @method('DELETE')

                                <x-button type="submit" variant="danger">
                                    <span>🗑</span>
                                    <span>{{ __('messages.delete_business_account') }}</span>
                                </x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h3>{{ __('messages.no_business_accounts') }}</h3>
                <p>
                    {{ __('messages.no_business_accounts_subtitle') }}
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
