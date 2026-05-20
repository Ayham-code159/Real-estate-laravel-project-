@extends('layouts.app')

@section('title', __('messages.admins'))

@section('content')
    <style>
        .admins-summary {
            display: grid;
            grid-template-columns: 1.1fr 2fr;
            gap: 18px;
            margin-bottom: 24px;
        }

        .admins-total-card {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.14), rgba(255, 255, 255, 0.82));
            border: 1px solid rgba(124, 58, 237, 0.18);
            border-radius: 26px;
            padding: 24px;
            box-shadow: 0 18px 42px rgba(124, 58, 237, 0.10);
        }

        .admins-total-label {
            color: var(--text-muted);
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .admins-total-value {
            font-size: 42px;
            font-weight: 950;
            line-height: 1;
            color: var(--text-main);
        }

        .admins-total-meta {
            margin-top: 8px;
            color: var(--text-muted);
            font-weight: 700;
        }

        .admins-permission-panel {
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(216, 200, 255, 0.58);
            border-radius: 26px;
            padding: 20px;
            box-shadow: 0 14px 34px rgba(25, 18, 50, 0.06);
        }

        .admins-permission-title {
            font-size: 17px;
            font-weight: 900;
            margin-bottom: 14px;
        }

        .admins-mini-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .admins-mini-stat {
            padding: 10px 13px;
            border-radius: 999px;
            background: rgba(246, 240, 255, 0.95);
            border: 1px solid rgba(216, 200, 255, 0.75);
            color: #6F3CC3;
            font-weight: 850;
            font-size: 13px;
        }

        .admin-list-card {
            margin-bottom: 16px;
            background: rgba(255,255,255,0.78);
            border: 1px solid rgba(216, 200, 255, 0.55);
            border-radius: 24px;
            transition: 0.22s ease;
        }

        .admin-list-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(111, 60, 195, 0.10);
        }

        .admin-card-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 16px;
        }

        .admin-card-title {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 900;
        }

        .admin-card-email {
            margin: 0;
            color: var(--text-muted);
            font-weight: 700;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-actions .btn {
            padding: 11px 16px;
            border-radius: 14px;
            font-size: 14px;
        }

        .admin-card-grid {
            display: grid;
            grid-template-columns: 1.4fr 2fr 1.2fr;
            gap: 18px;
            align-items: start;
        }

        .admin-field-label {
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .admin-field-value {
            font-weight: 850;
        }

        .admin-permission-badges {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .admin-permission-badges .badge {
            font-size: 12px;
            padding: 7px 10px;
            border-radius: 999px;
        }

        @media (max-width: 1000px) {
            .admins-summary {
                grid-template-columns: 1fr;
            }

            .admin-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

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

    <div class="admins-summary">
        <div class="admins-total-card">
            <div class="admins-total-label">{{ __('messages.total_admins') }}</div>
            <div class="admins-total-value">{{ $counts['total_admins'] ?? 0 }}</div>
            <div class="admins-total-meta">{{ __('messages.admins') }}</div>
        </div>

        <div class="admins-permission-panel">
            <div class="admins-permission-title">{{ __('messages.permissions') }}</div>

            <div class="admins-mini-stats">
                <span class="admins-mini-stat">👑 {{ $counts['super_admins'] ?? 0 }} {{ __('messages.super_admin') }}</span>
                <span class="admins-mini-stat">👥 {{ $counts['manage_users_admins'] ?? 0 }} {{ __('messages.users') }}</span>
                <span class="admins-mini-stat">🏢 {{ $counts['manage_business_accounts_admins'] ?? 0 }} {{ __('messages.business_accounts') }}</span>
                <span class="admins-mini-stat">🏷️ {{ $counts['manage_business_types_admins'] ?? 0 }} {{ __('messages.business_types') }}</span>
                <span class="admins-mini-stat">🗂️ {{ $counts['manage_categories_admins'] ?? 0 }} {{ __('messages.categories') }}</span>
                <span class="admins-mini-stat">📦 {{ $counts['manage_items_admins'] ?? 0 }} {{ __('messages.items') }}</span>
                <span class="admins-mini-stat">🎞️ {{ $counts['manage_sliders_admins'] ?? 0 }} {{ __('messages.sliders') }}</span>
                <span class="admins-mini-stat">🏙️ {{ $counts['manage_cities_admins'] ?? 0 }} {{ __('messages.cities') }}</span>
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
            <div class="card admin-list-card">
                <div class="card-body">
                    <div class="admin-card-head">
                        <div>
                            <h3 class="admin-card-title">
                                {{ $admin->name }}
                            </h3>
                            <p class="admin-card-email">
                                {{ $admin->email }}
                            </p>
                        </div>

                        <div class="admin-actions">
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

                    <div class="admin-card-grid">
                        <div>
                            <div class="admin-field-label">{{ __('messages.permission_level') }}</div>

                            @if($admin->isSuperAdmin())
                                <span class="badge badge-danger">{{ __('messages.super_admin') }}</span>
                            @else
                                <div class="admin-permission-badges">
                                  @forelse($admin->permissionTranslationKeys() as $permissionKey)
                                    <span class="badge badge-primary">
                                        {{ __('messages.' . $permissionKey) }}
                                    </span>
                                    @empty
                                        <span class="badge badge-warning">{{ __('messages.basic_admin') }}</span>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="admin-field-label">{{ __('messages.email') }}</div>
                            <div class="admin-field-value">
                                {{ $admin->email }}
                            </div>
                        </div>

                        <div>
                            <div class="admin-field-label">{{ __('messages.last_login') }}</div>
                            <div class="admin-field-value">
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
