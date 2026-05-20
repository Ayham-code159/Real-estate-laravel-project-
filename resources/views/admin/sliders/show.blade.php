@extends('layouts.app')

@section('title', 'Slider Details')

@section('content')
    <x-page-title
        title="{{ __('messages.slider_details') }}"

    >
        <x-slot:actions>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
            </a>

            <a href="{{ route('admin.items.show', $slider->item) }}" class="btn btn-primary">
                📦 {{ __('messages.show_item') }}
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div>
                <h2 class="section-title">{{ $slider->item->title }}</h2>
                <p class="section-subtitle">
                    {{ $slider->item->businessAccount?->business_name ?? 'N/A' }}
                </p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <span class="badge {{ $slider->priority_badge_class }}">
                    {{ $slider->priority_label }}
                </span>

                <span class="badge {{ $slider->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $slider->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="info-list" style="margin-top: 20px;">
            <div class="info-row">
                <div class="info-label">{{ __('messages.category') }}</div>
                <div class="info-value">{{ $slider->item->category?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.subcategory') }}</div>
                <div class="info-value">{{ $slider->item->subcategory?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.price_usd') }}</div>
                <div class="info-value">${{ number_format((float) $slider->item->price_usd, 2) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.price_syp') }}</div>
                <div class="info-value">{{ number_format((float) $slider->item->price_syp, 2) }} SYP</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.click_count') }}</div>
                <div class="info-value">{{ $slider->click_count }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <h2 class="section-title">{{ __('messages.photos') }}</h2>

        @if($slider->item->main_photo_url)
            <div style="margin-top: 16px;">
                <h3 style="margin: 0 0 14px;">{{ __('messages.main_photo') }}</h3>
                <img
                    src="{{ $slider->item->main_photo_url }}"
                    alt="Main Photo"
                    style="width: 100%; max-height: 420px; object-fit: cover; border-radius: 22px;"
                >
            </div>
        @endif

        @if(!empty($slider->item->sub_photo_urls))
            <div style="margin-top: 24px;">
                <h3 style="margin: 0 0 14px;">{{ __('messages.sub_photos') }}</h3>

                <div class="grid grid-3">
                    @foreach($slider->item->sub_photo_urls as $photo)
                        <a href="{{ $photo['url'] }}" target="_blank" class="card" style="overflow: hidden;">
                            <img
                                src="{{ $photo['url'] }}"
                                alt="Sub Photo"
                                style="width: 100%; height: 220px; object-fit: cover; display: block;"
                            >
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <h2 class="section-title">{{ __('messages.slider_settings') }}</h2>

        <form method="POST" action="{{ route('admin.sliders.update', $slider) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.priority') }}</label>
                    <select name="priority" class="form-input">
                        @foreach(\App\Models\ItemSlider::priorities() as $value => $label)
                            <option value="{{ $value }}" {{ $slider->priority === $value ? 'selected' : '' }}>
                                {{ __('messages.slider_priority_' . strtolower($label)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">{{ __('messages.active') }}?</label>
                    <select name="is_active" class="form-input">
                        <option value="1" {{ $slider->is_active ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="0" {{ ! $slider->is_active ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top: 18px;">
                <label class="form-label">{{ __('messages.admin_note') }}</label>
                <textarea name="admin_note" rows="4" class="form-input">{{ old('admin_note', $slider->admin_note) }}</textarea>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    💾 {{ __('messages.update_slider') }}
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <h2 class="section-title">{{ __('messages.danger_zone') }}</h2>


        <form method="POST"
              action="{{ route('admin.sliders.destroy', $slider) }}"
              onsubmit="return confirm('Are you sure you want to delete this slider?');">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                🗑 {{ __('messages.delete_slider') }}
            </x-button>
        </form>
    </x-card>
@endsection
