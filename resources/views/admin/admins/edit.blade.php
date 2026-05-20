@extends('layouts.app')

@section('title', __('messages.edit_admin'))

@section('content')
    <x-page-title
        :title="__('messages.edit_admin')"
        :subtitle="__('messages.edit_admin_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.admins.index', $admin) }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">

        <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">

                <div class="form-group">
                    <label class="form-label">
                        {{ __('messages.name') }}
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $admin->name) }}"
                    >

                    @error('name')
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        {{ __('messages.email') }}
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email', $admin->email) }}"
                    >

                    @error('email')
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="form-group">

                <label class="form-label">
                    {{ __('messages.password') }}
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="{{ __('messages.leave_blank_to_keep_password') }}"
                >

                @error('password')
                    <div class="alert alert-danger" style="margin-top: 10px;">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            @if(! $admin->isSuperAdmin())

                <div style="margin-top: 24px; margin-bottom: 18px;">

                    <h2 class="section-title">
                        {{ __('messages.default_roles') }}
                    </h2>

                    <p class="section-subtitle">
                        {{ __('messages.default_roles_subtitle') }}
                    </p>

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

                    <h2 class="section-title">
                        {{ __('messages.permissions') }}
                    </h2>

                    <p class="section-subtitle">
                        {{ __('messages.permissions_subtitle') }}
                    </p>

                </div>

                @php
                    $adminPermissions = [
                        'can_manage_users' => $admin->can_manage_users,
                        'can_manage_business_accounts' => $admin->can_manage_business_accounts,
                        'can_manage_business_types' => $admin->can_manage_business_types,
                        'can_manage_categories' => $admin->can_manage_categories,
                        'can_manage_items' => $admin->can_manage_items,
                        'can_manage_sliders' => $admin->can_manage_sliders,
                        'can_manage_cities' => $admin->can_manage_cities,
                    ];
                @endphp

                <div class="grid grid-3">

                    @foreach($permissions as $key => $label)

                        <label class="card"
                               style="background: rgba(255,255,255,0.72); cursor: pointer;">

                            <div class="card-body">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $key }}"
                                    class="permission-checkbox"
                                    {{ in_array($key, old('permissions', array_keys(array_filter($adminPermissions))), true) ? 'checked' : '' }}
                                >

                                <div style="margin-top: 10px; font-weight: 800;">
                                    {{ $label }}
                                </div>

                            </div>

                        </label>

                    @endforeach

                </div>

            @endif

            <div style="margin-top: 24px;">

                <button type="submit" class="btn btn-primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_admin_account') }}</span>
                </button>

            </div>

        </form>

    </x-card>

    <script>
        document.querySelectorAll('.role-preset').forEach(button => {

            button.addEventListener('click', () => {

                const permissions = JSON.parse(
                    button.dataset.permissions || '[]'
                );

                document.querySelectorAll('.permission-checkbox')
                    .forEach(checkbox => {

                        checkbox.checked = permissions.includes(
                            checkbox.value
                        );

                    });

            });

        });
    </script>
@endsection
