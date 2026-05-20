@extends('layouts.app')

@section('title', __('messages.cities_management'))

@section('content')

<x-page-title
    :title="__('messages.cities_management')"
    :subtitle="__('messages.cities_management_subtitle')"
/>

<x-card class="subtle-panel" style="margin-bottom: 24px;">

    <form method="POST" action="{{ route('admin.cities.store') }}">
        @csrf

        <div class="grid grid-2" style="align-items: end;">

            <div>
                <label class="form-label">
                    {{ __('messages.city_name_english') }}
                </label>

                <input
                    type="text"
                    name="name_en"
                    class="form-input"
                    value="{{ old('name_en') }}"
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
                    value="{{ old('name_ar') }}"
                    required
                >
            </div>

        </div>

        <div style="margin-top: 20px;">
            <x-button type="submit" variant="primary">
                <span>＋</span>
                <span>{{ __('messages.add_city') }}</span>
            </x-button>
        </div>

    </form>

</x-card>

<x-card class="subtle-panel">

    <div style="margin-bottom: 20px;">
        <h2 class="section-title">
            {{ __('messages.all_cities') }}
        </h2>
    </div>

    @forelse($cities as $city)

        <div class="activity-item" style="margin-bottom: 14px;">

            <div class="activity-icon">
                🌍
            </div>

            <div class="activity-body" style="width: 100%;">

                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">

                    <div>
                        <h4 style="margin:0;">
                            {{ $city->translated_name }}
                        </h4>

                        <p style="margin-top:8px;">
                            EN: {{ $city->name_en }}
                            •
                            AR: {{ $city->name_ar }}
                        </p>

                        <p style="margin-top:8px;">
                            {{ __('messages.related_business_accounts') }}:
                            {{ $city->business_accounts_count }}
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.cities.show', $city) }}"
                        class="btn btn-outline"
                    >
                        {{ __('messages.view_details') }}
                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="empty-state">

            <div class="empty-state-icon">
                🌍
            </div>

            <h3>
                {{ __('messages.cities') }}
            </h3>

        </div>

    @endforelse

</x-card>

@endsection
