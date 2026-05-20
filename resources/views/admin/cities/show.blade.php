@extends('layouts.app')

@section('title', __('messages.city_details'))

@section('content')

<x-page-title
    :title="__('messages.city_details')"
    :subtitle="__('messages.city_details_subtitle')"
>
    <x-slot:actions>

        <a href="{{ route('admin.cities.index') }}"
           class="btn btn-outline">

            <span>←</span>
            <span>{{ __('messages.back') }}</span>

        </a>

    </x-slot:actions>
</x-page-title>

<x-card class="subtle-panel" style="margin-bottom: 24px;">

    <div class="info-list">

        <div class="info-row">
            <div class="info-label">
                {{ __('messages.city_name_english') }}
            </div>

            <div class="info-value">
                {{ $city->name_en }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">
                {{ __('messages.city_name_arabic') }}
            </div>

            <div class="info-value">
                {{ $city->name_ar }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">
                {{ __('messages.related_business_accounts') }}
            </div>

            <div class="info-value">
                {{ $city->business_accounts_count }}
            </div>
        </div>

    </div>

</x-card>

<x-card class="subtle-panel" style="margin-bottom:24px;">

    <div style="margin-bottom:20px;">
        <h2 class="section-title">
            {{ __('messages.edit_city') }}
        </h2>
    </div>

    <form method="POST"
          action="{{ route('admin.cities.update', $city) }}">

        @csrf
        @method('PUT')

        <div class="grid grid-2">

            <div>

                <label class="form-label">
                    {{ __('messages.city_name_english') }}
                </label>

                <input
                    type="text"
                    name="name_en"
                    class="form-input"
                    value="{{ old('name_en', $city->name_en) }}"
                    required
                >

            </div>

            <div>

                <label class="form-label">
                    {{ __('messages.city_name_arabic') }}
                </label>

                <input
                    type="text"
                    name="name_ar"
                    class="form-input"
                    value="{{ old('name_ar', $city->name_ar) }}"
                    required
                >

            </div>

        </div>

        <div style="margin-top:20px;">

            <x-button type="submit" variant="primary">

                <span>💾</span>
                <span>{{ __('messages.edit_city') }}</span>

            </x-button>

        </div>

    </form>

</x-card>

<x-card class="subtle-panel">

    <div style="margin-bottom:20px;">

        <h2 class="section-title">
            {{ __('messages.danger_zone') }}
        </h2>

        <p class="section-subtitle">
            {{ __('messages.delete_city_warning') }}
        </p>

    </div>

    <form method="POST"
          action="{{ route('admin.cities.destroy', $city) }}">

        @csrf
        @method('DELETE')

        <input
            type="hidden"
            name="expected_name"
            value="{{ $city->translated_name }}"
        >

        <div class="form-group">

            <label class="form-label">
                {{ __('messages.confirm_city_delete') }}
            </label>

            <input
                type="text"
                name="confirmation_name"
                class="form-input"
                required
            >

        </div>

        <div style="margin-top:20px;">

            <x-button type="submit" variant="danger">

                <span>🗑</span>
                <span>{{ __('messages.delete_city') }}</span>

            </x-button>

        </div>

    </form>

</x-card>

@endsection
