@extends('layouts.app')

@section('title', __('messages.admins'))

@section('content')
    <x-page-title
        :title="__('messages.admins')"
        :subtitle="__('messages.admins_page_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <span>➕</span>
                <span>{{ __('messages.create_admin') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <div class="grid grid-4" style="margin-bottom: 24px;">
        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.total_admins') }}</div>
                    <div class="stats-value">{{ $counts['total_admins'] ?? 0 }}</div>
                    <div class="stats-meta">{{ __('messages.admins') }}</div>
                </div>
                <div class="stats-icon">🛡️</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.super_admins') }}</div>
                    <div class="stats-value">{{ $counts['super_admins'] ?? 0 }}</div>
                    <div class="stats-meta">{{ __('messages.access_level') }}</div>
                </div>
                <div class="stats-icon">👑</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.manage_users_admins') }}</div>
                    <div class="stats-value">{{ $counts['manage_users_admins'] ?? 0 }}</div>
                    <div class="stats-meta">{{ __('messages.can_manage_users') }}</div>
                </div>
                <div class="stats-icon">👥</div>
            </div>
        </div>

        <div class="card stats-card glass-accent">
            <div class="stats-head">
                <div>
                    <div class="stats-label">{{ __('messages.manage_business_accounts_admins') }}</div>
                    <div class="stats-value">{{ $counts['manage_business_accounts_admins'] ?? 0 }}</div>
                    <div class="stats-meta">{{ __('messages.can_manage_business_accounts') }}</div>
                </div>
                <div class="stats-icon">🏢</div>
            </div>
        </div>
    </div>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.admins.index') }}">
            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.search_admin') }}</label>
                    <input
                        type="text"
                        name="search"
                        class="form-input"
                        value="{{ $search ?? '' }}"
                        placeholder="{{ __('messages.search_admin_placeholder') }}"
                    >
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary">
                        <span>🔍</span>
                        <span>{{ __('messages.search') }}</span>
                    </button>

                    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">
                        <span>↺</span>
                        <span>{{ __('messages.reset') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_admins') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.all_admins_subtitle') }}
            </p>
        </div>

        @forelse($admins as $admin)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 18px;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 22px;">
                                {{ $admin->name }}
                            </h3>
                            <p class="text-muted" style="margin: 0;">
                                {{ $admin->email }}
                            </p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-primary">
                                <span>👁</span>
                                <span>{{ __('messages.view_details') }}</span>
                            </a>

                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-outline">
                                <span>✏️</span>
                                <span>{{ __('messages.edit') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-3">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.email') }}</div>
                            <div style="font-weight: 800;">
                                {{ $admin->email }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.permission_level') }}</div>
                            <div style="font-weight: 800;">
                                {{ $admin->permissionLabel() }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.last_login') }}</div>
                            <div style="font-weight: 800;">
                                {{ optional($admin->last_login_at)->format('Y-m-d h:i A') ?? __('messages.no_previous_login') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🛡️</div>
                <h3>{{ __('messages.no_admins_found') }}</h3>
                <p>
                    {{ __('messages.no_admins_found_subtitle') }}
                </p>
            </div>
        @endforelse

        @if(method_exists($admins, 'hasPages') && $admins->hasPages())
            <div style="margin-top: 24px;">
                {{ $admins->links() }}
            </div>
        @endif
    </x-card>
@endsection
