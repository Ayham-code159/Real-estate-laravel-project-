@extends('layouts.app')

@section('title', __('messages.create_admin'))

@section('content')
    <x-page-title
        :title="__('messages.create_admin')"
        :subtitle="__('messages.create_admin_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.name') }}</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}">
                    @error('name')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                    @error('email')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.password') }}</label>
                <input type="password" name="password" class="form-input">
                @error('password')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 24px; margin-bottom: 18px;">
                <h2 class="section-title">{{ __('messages.default_roles') }}</h2>
                <p class="section-subtitle">Choose a preset, then customize permissions if needed.</p>
            </div>

            <div class="grid grid-3" style="margin-bottom: 24px;">
                @foreach($defaultRoles as $roleKey => $role)
                    <button
                        type="button"
                        class="btn btn-outline role-preset"
                        data-permissions='@json($role["permissions"])'
                    >
                        {{ $role['label'] }}
                    </button>
                @endforeach
            </div>

            <div style="margin-top: 24px; margin-bottom: 18px;">
                <h2 class="section-title">{{ __('messages.permissions') }}</h2>
                <p class="section-subtitle">
                    Super admin permission is system-only and cannot be granted.
                </p>
            </div>

            <div class="grid grid-3">
                @foreach($permissions as $key => $label)
                    <label class="card" style="background: rgba(255,255,255,0.72); cursor: pointer;">
                        <div class="card-body">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $key }}"
                                class="permission-checkbox"
                                {{ in_array($key, old('permissions', []), true) ? 'checked' : '' }}
                            >
                            <div style="margin-top: 10px; font-weight: 800;">{{ $label }}</div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <span>💾</span>
                    <span>{{ __('messages.create_admin_account') }}</span>
                </button>
            </div>
        </form>
    </x-card>

    <script>
        document.querySelectorAll('.role-preset').forEach(button => {
            button.addEventListener('click', () => {
                const permissions = JSON.parse(button.dataset.permissions || '[]');

                document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                    checkbox.checked = permissions.includes(checkbox.value);
                });
            });
        });
    </script>
@endsection
