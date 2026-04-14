@extends('layouts.app')

@section('title', __('messages.edit_admin'))

@section('content')
    <x-page-title
        :title="__('messages.edit_admin')"
        :subtitle="__('messages.edit_admin_subtitle')"
    >
        <x-slot:actions>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-outline">
                    <span>←</span>
                    <span>{{ __('messages.back') }}</span>
                </a>
            </div>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.name') }}</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $admin->name) }}"
                        placeholder="{{ __('messages.enter_admin_name') }}"
                    >
                    @error('name')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email', $admin->email) }}"
                        placeholder="{{ __('messages.enter_admin_email') }}"
                    >
                    @error('email')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.password') }}</label>
                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="{{ __('messages.leave_blank_to_keep_password') }}"
                >
                @error('password')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 24px; margin-bottom: 18px;">
                <h2 class="section-title">{{ __('messages.permissions') }}</h2>
                <p class="section-subtitle">{{ __('messages.permissions_form_subtitle') }}</p>
            </div>

            <div class="grid grid-3">
                <label class="card" style="background: rgba(255,255,255,0.72); cursor: pointer;">
                    <div class="card-body">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin', $admin->is_super_admin) ? 'checked' : '' }}>
                        <div style="margin-top: 10px; font-weight: 800;">{{ __('messages.make_super_admin') }}</div>
                    </div>
                </label>

                <label class="card" style="background: rgba(255,255,255,0.72); cursor: pointer;">
                    <div class="card-body">
                        <input type="checkbox" name="can_manage_users" value="1" {{ old('can_manage_users', $admin->can_manage_users) ? 'checked' : '' }}>
                        <div style="margin-top: 10px; font-weight: 800;">{{ __('messages.can_manage_users') }}</div>
                    </div>
                </label>

                <label class="card" style="background: rgba(255,255,255,0.72); cursor: pointer;">
                    <div class="card-body">
                        <input type="checkbox" name="can_manage_business_accounts" value="1" {{ old('can_manage_business_accounts', $admin->can_manage_business_accounts) ? 'checked' : '' }}>
                        <div style="margin-top: 10px; font-weight: 800;">{{ __('messages.can_manage_business_accounts') }}</div>
                    </div>
                </label>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_admin_account') }}</span>
                </button>
            </div>
        </form>
    </x-card>
@endsection
