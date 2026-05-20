@extends('layouts.app')

@section('title', 'Create Subcategory')

@section('content')
    <x-page-title
        :title="__('messages.create_subcategory')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div>
                <h2 class="section-title">{{ $category->name }}</h2>
                <p class="section-subtitle">
                </p>
            </div>

            <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <form method="POST" action="{{ route('admin.categories.subcategories.store', $category) }}">
            @csrf

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.english_name') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en') }}"
                        placeholder="Example: Cars"
                    >
                    @error('name_en')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.arabic_name') }}</label>
                    <input
                        type="text"
                        name="name_ar"
                        class="form-input"
                        value="{{ old('name_ar') }}"
                        placeholder="مثال: سيارات"
                    >
                    @error('name_ar')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.english_description') }}</label>
                    <textarea
                        name="description_en"
                        class="form-input"
                        rows="5"
                        placeholder="Optional description"
                    >{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.arabic_description') }}</label>
                    <textarea
                        name="description_ar"
                        class="form-input"
                        rows="5"
                        placeholder="وصف اختياري"
                    >{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="is_active" class="form-input">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.create_subcategory') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
