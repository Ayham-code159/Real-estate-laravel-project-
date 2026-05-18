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

            @if(! $admin->isSuperAdmin())
    <form method="POST"
          action="{{ route('admin.admins.destroy', $admin) }}"
          onsubmit="return confirm('Are you sure you want to delete this admin?');">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">
            <span>🗑</span>
            <span>{{ __('messages.delete') }}</span>
        </button>
    </form>
@endif


        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <div class="info-list">

            <div class="info-row">
                <div class="info-label">{{ __('messages.name') }}</div>
                <div class="info-value">
                    {{ $admin->name }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}</div>
                <div class="info-value">
                    {{ $admin->email }}
                </div>
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

        @if($admin->isSuperAdmin())

            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <span class="badge badge-danger">
                    👑 Super Admin
                </span>
            </div>

        @else

            <div style="display: flex; flex-wrap: wrap; gap: 10px;">

                @forelse($admin->permissionLabels() as $permission)
                    <span class="badge badge-primary">
                        {{ $permission }}
                    </span>
                @empty
                    <span class="badge badge-warning">
                        Basic Admin
                    </span>
                @endforelse

            </div>

        @endif
    </x-card>
@endsection
