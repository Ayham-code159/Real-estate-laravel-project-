@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    <x-page-title
        :title="__('messages.edit_category')"

    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
                ← Back
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.english_name') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $category->name_en) }}"
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
                        value="{{ old('name_ar', $category->name_ar) }}"
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
                    >{{ old('description_en', $category->description_en) }}</textarea>
                    @error('description_en')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.english_description') }}</label>
                    <textarea
                        name="description_ar"
                        class="form-input"
                        rows="5"
                    >{{ old('description_ar', $category->description_ar) }}</textarea>
                    @error('description_ar')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="is_active" class="form-input">
                    <option value="1" {{ old('is_active', $category->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="0" {{ old('is_active', $category->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.edit_category') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <h2 class="section-title">{{ __('messages.danger_zone') }}
</h2>
        <p class="section-subtitle" style="margin-bottom: 18px;">
            {{ __('messages.delete_this_category_with_its_subcategories') }}
        </p>

        <form method="POST"
              action="{{ route('admin.categories.delete', $category) }}"
              onsubmit="return confirm('Are you sure you want to delete this category?');">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_category') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
