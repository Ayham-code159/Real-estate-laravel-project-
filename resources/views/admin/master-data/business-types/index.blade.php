@extends('layouts.app')

@section('title', __('messages.business_types'))

@section('content')
    <x-page-title
        :title="__('messages.business_account_types')"
        :subtitle="__('messages.business_types_page_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-primary">{{ __('messages.super_admin_only') }}</span>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.master-data.business-types.store') }}">
            @csrf

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.name_english') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en') }}"
                        placeholder="{{ __('messages.enter_name_in_english') }}"
                        required
                    >
                </div>

                <div>
                    <label class="form-label">{{ __('messages.name_arabic') }}</label>
                    <input
                        type="text"
                        name="name_ar"
                        class="form-input"
                        value="{{ old('name_ar') }}"
                        placeholder="{{ __('messages.enter_name_in_arabic') }}"
                        required
                    >
                </div>
            </div>

            <div>
                <x-button type="submit" variant="primary">
                    <span>＋</span>
                    <span>{{ __('messages.add_type') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_business_types') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.all_business_types_subtitle_clickable') }}
            </p>
        </div>

        @forelse($businessTypes as $businessType)
            <a href="{{ route('admin.master-data.business-types.show', $businessType->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">🏷️</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $businessType->translated_name }}</h4>
                            <span class="badge badge-primary">{{ __('messages.id_number') }}{{ $businessType->id }}</span>
                        </div>

                        <p style="margin-top: 8px;">
                            EN: {{ $businessType->name_en }} • AR: {{ $businessType->name_ar }}
                        </p>

                        <p style="margin-top: 8px;">
                            {{ __('messages.created_at_label') }} {{ $businessType->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏷️</div>
                <h3>{{ __('messages.no_business_types') }}</h3>
                <p>{{ __('messages.no_business_types_subtitle') }}</p>
            </div>
        @endforelse
    </x-card>
@endsection
