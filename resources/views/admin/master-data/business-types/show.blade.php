@extends('layouts.app')

@section('title', __('messages.business_type_details'))

@section('content')
    <x-page-title
        :title="__('messages.business_type_details')"
        :subtitle="__('messages.business_type_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.business-types.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back_to_business_types') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.name_english') }}</div>
                <div class="info-value">{{ $businessType->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.name_arabic') }}</div>
                <div class="info-value">{{ $businessType->name_ar }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.related_business_accounts') }}</div>
                <div class="info-value">{{ $businessType->business_accounts_count }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.created_at') }}</div>
                <div class="info-value">{{ $businessType->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.edit_business_type') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.edit_business_type_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.business-types.update', $businessType->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.name_english') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $businessType->name_en) }}"
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
                        value="{{ old('name_ar', $businessType->name_ar) }}"
                        placeholder="{{ __('messages.enter_name_in_arabic') }}"
                        required
                    >
                </div>
            </div>

            <div>
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_type') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.danger_zone') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.delete_business_type_warning') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.business-types.destroy', $businessType->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_business_type') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
