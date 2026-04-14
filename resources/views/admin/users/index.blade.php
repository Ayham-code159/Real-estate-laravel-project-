@extends('layouts.app')

@section('title', __('messages.users'))

@section('content')
    <x-page-title
        :title="__('messages.users')"
        :subtitle="__('messages.users_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-primary">{{ __('messages.user_management') }}</span>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.total_users') }}</div>
                    <div class="stats-value">{{ $counts['total_users'] }}</div>
                    <div class="stats-meta">{{ __('messages.registered_platform_users') }}</div>
                </div>
                <div class="stats-icon">👥</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.with_business_accounts') }}</div>
                    <div class="stats-value">{{ $counts['with_business_accounts'] }}</div>
                    <div class="stats-meta">{{ __('messages.users_with_business_accounts') }}</div>
                </div>
                <div class="stats-icon">🏢</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.with_approved_accounts') }}</div>
                    <div class="stats-value">{{ $counts['with_approved_accounts'] }}</div>
                    <div class="stats-meta">{{ __('messages.users_ready_to_publish') }}</div>
                </div>
                <div class="stats-icon">✅</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.total_listings') }}</div>
                    <div class="stats-value">{{ $counts['total_listings'] }}</div>
                    <div class="stats-meta">{{ __('messages.all_submitted_listings') }}</div>
                </div>
                <div class="stats-icon">📦</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.search_user') }}</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search }}"
                        placeholder="{{ __('messages.search_user_placeholder') }}"
                    >
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <x-button type="submit" variant="primary">
                        <span>🔍</span>
                        <span>{{ __('messages.search') }}</span>
                    </x-button>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                        <span>↺</span>
                        <span>{{ __('messages.reset') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_users') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.all_users_subtitle') }}
            </p>
        </div>

        @forelse($users as $user)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">
                                {{ $user->full_name }}
                            </h3>
                            <p class="text-muted" style="margin: 0;">
                                {{ __('messages.username') }}: {{ $user->username ?? __('messages.not_available') }}
                            </p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary">
                                <span>👁</span>
                                <span>{{ __('messages.view_details') }}</span>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}');">
                                @csrf
                                @method('DELETE')

                                <x-button type="submit" variant="danger">
                                    <span>🗑</span>
                                    <span>{{ __('messages.delete') }}</span>
                                </x-button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-3">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.email_or_phone') }}</div>
                            <div style="font-weight: 800;">
                                {{ $user->email ?? $user->phone ?? __('messages.not_available') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.business_accounts') }}</div>
                            <div style="font-weight: 800;">
                                {{ $user->business_accounts_count }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.approved_accounts') }}</div>
                            <div style="font-weight: 800;">
                                {{ $user->approved_business_accounts_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h3>{{ __('messages.no_users_found') }}</h3>
                <p>
                    {{ __('messages.no_users_found_subtitle') }}
                </p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div style="margin-top: 24px;">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
@endsection
