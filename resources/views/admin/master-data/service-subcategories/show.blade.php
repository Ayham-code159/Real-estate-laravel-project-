@extends('layouts.app')

@section('title', __('messages.subcategory_details'))

@section('content')
    <x-page-title
        :title="__('messages.subcategory_details')"
        :subtitle="__('messages.subcategory_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.services.show', $subcategory->service_id) }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back_to_service') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.name_english') }}</div>
                <div class="info-value">{{ $subcategory->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.name_arabic') }}</div>
                <div class="info-value">{{ $subcategory->name_ar }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.parent_service') }}</div>
                <div class="info-value">{{ $subcategory->service?->translated_name ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.created_at') }}</div>
                <div class="info-value">{{ $subcategory->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.edit_subcategory') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.edit_subcategory_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.update', $subcategory->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.name_english') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $subcategory->name_en) }}"
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
                        value="{{ old('name_ar', $subcategory->name_ar) }}"
                        placeholder="{{ __('messages.enter_name_in_arabic') }}"
                        required
                    >
                </div>
            </div>

            <div>
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_subcategory') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.danger_zone') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.delete_subcategory_warning') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.destroy', $subcategory->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_subcategory') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
