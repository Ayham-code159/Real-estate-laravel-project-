@extends('layouts.app')

@section('title', __('messages.admin_details'))

@section('content')
    <x-page-title
        :title="__('messages.admin_details')"
        :subtitle="__('messages.admin_details_subtitle')"
    >
        <x-slot:actions>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">
                    <span>←</span>
                    <span>{{ __('messages.back') }}</span>
                </a>

                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary">
                    <span>✏️</span>
                    <span>{{ __('messages.edit') }}</span>
                </a>
            </div>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.name') }}</div>
                <div class="info-value">{{ $admin->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}</div>
                <div class="info-value">{{ $admin->email }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.permission_level') }}</div>
                <div class="info-value">{{ $admin->permissionLabel() }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.last_login') }}</div>
                <div class="info-value">
                    {{ optional($admin->last_login_at)->format('Y-m-d h:i A') ?? __('messages.no_previous_login') }}
                </div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-top: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.permissions') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.admin_permissions_subtitle') }}
            </p>
        </div>

        <div class="grid grid-3">
            <div class="card" style="background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div class="text-muted" style="margin-bottom: 8px;">{{ __('messages.make_super_admin') }}</div>
                    <div style="font-weight: 800; font-size: 18px;">
                        {{ $admin->is_super_admin ? __('messages.yes') : __('messages.no') }}
                    </div>
                </div>
            </div>

            <div class="card" style="background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div class="text-muted" style="margin-bottom: 8px;">{{ __('messages.can_manage_users') }}</div>
                    <div style="font-weight: 800; font-size: 18px;">
                        {{ $admin->can_manage_users ? __('messages.yes') : __('messages.no') }}
                    </div>
                </div>
            </div>

            <div class="card" style="background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div class="text-muted" style="margin-bottom: 8px;">{{ __('messages.can_manage_business_accounts') }}</div>
                    <div style="font-weight: 800; font-size: 18px;">
                        {{ $admin->can_manage_business_accounts ? __('messages.yes') : __('messages.no') }}
                    </div>
                </div>
            </div>
        </div>
    </x-card>
@endsection
