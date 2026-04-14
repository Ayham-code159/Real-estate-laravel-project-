@extends('layouts.app')

@section('title', __('messages.service_details'))

@section('content')
    <x-page-title
        :title="__('messages.service_details')"
        :subtitle="__('messages.service_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.services.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back_to_services') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.name_english') }}</div>
                <div class="info-value">{{ $service->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.name_arabic') }}</div>
                <div class="info-value">{{ $service->name_ar }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.subcategories_count') }}</div>
                <div class="info-value">{{ $service->subcategories->count() }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.created_at') }}</div>
                <div class="info-value">{{ $service->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.edit_main_service') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.edit_main_service_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.services.update', $service->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.name_english') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $service->name_en) }}"
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
                        value="{{ old('name_ar', $service->name_ar) }}"
                        placeholder="{{ __('messages.enter_name_in_arabic') }}"
                        required
                    >
                </div>
            </div>

            <div>
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_service') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.add_new_subcategory') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.add_new_subcategory_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.store') }}">
            @csrf

            <input type="hidden" name="service_id" value="{{ $service->id }}">

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
                    <span>{{ __('messages.add_subcategory') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.related_subcategories') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.related_subcategories_subtitle') }}
            </p>
        </div>

        @forelse($service->subcategories as $subcategory)
            <a href="{{ route('admin.master-data.service-subcategories.show', $subcategory->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">🪜</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $subcategory->translated_name }}</h4>
                            <span class="badge badge-primary">{{ __('messages.id_number') }}{{ $subcategory->id }}</span>
                        </div>

                        <p style="margin-top: 8px;">
                            EN: {{ $subcategory->name_en }} • AR: {{ $subcategory->name_ar }}
                        </p>

                        <p style="margin-top: 8px;">
                            {{ __('messages.created_at_label') }} {{ $subcategory->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🪜</div>
                <h3>{{ __('messages.no_subcategories_yet') }}</h3>
                <p>{{ __('messages.no_subcategories_yet_subtitle') }}</p>
            </div>
        @endforelse
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.danger_zone') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.delete_service_warning') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.services.destroy', $service->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_service') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
