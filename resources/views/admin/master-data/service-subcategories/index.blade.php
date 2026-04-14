@extends('layouts.app')

@section('title', __('messages.service_subcategories'))

@section('content')
    <x-page-title
        :title="__('messages.service_subcategories')"
        :subtitle="__('messages.service_subcategories_page_subtitle')"
    >
        <x-slot:actions>
            <span class="badge badge-primary">{{ __('messages.super_admin_only') }}</span>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.master-data.service-subcategories.store') }}">
            @csrf

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.main_service') }}</label>
                    <select name="service_id" class="form-input" required>
                        <option value="">{{ __('messages.select_main_service') }}</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->translated_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
            </div>

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
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

                <div>
                    <x-button type="submit" variant="primary">
                        <span>＋</span>
                        <span>{{ __('messages.add_subcategory') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_service_subcategories') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.all_service_subcategories_subtitle') }}
            </p>
        </div>

        @forelse($serviceSubcategories as $subcategory)
            <div class="activity-item" style="margin-bottom: 12px;">
                <div class="activity-icon">🪜</div>

                <div class="activity-body" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <h4 style="margin: 0;">{{ $subcategory->translated_name }}</h4>
                        <span class="badge badge-primary">{{ $subcategory->service?->translated_name ?? __('messages.not_available') }}</span>
                    </div>

                    <p style="margin-top: 8px;">
                        EN: {{ $subcategory->name_en }} • AR: {{ $subcategory->name_ar }}
                    </p>

                    <p style="margin-top: 8px;">
                        {{ __('messages.created_at_label') }} {{ $subcategory->created_at->format('Y-m-d h:i A') }}
                    </p>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🪜</div>
                <h3>{{ __('messages.no_service_subcategories') }}</h3>
                <p>{{ __('messages.no_service_subcategories_subtitle') }}</p>
            </div>
        @endforelse
    </x-card>
@endsection
