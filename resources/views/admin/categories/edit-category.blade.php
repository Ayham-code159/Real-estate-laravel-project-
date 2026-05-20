@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    <x-page-title
        :title="__('messages.edit_category')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
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
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
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
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
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
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
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
                        <div class="alert alert-danger" style="margin-top: 10px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.status') }}</label>

                <select name="is_active" class="form-input">
                    <option
                        value="1"
                        {{ old('is_active', $category->is_active ? '1' : '0') == '1' ? 'selected' : '' }}
                    >
                        {{ __('messages.active') }}
                    </option>

                    <option
                        value="0"
                        {{ old('is_active', $category->is_active ? '1' : '0') == '0' ? 'selected' : '' }}
                    >
                        {{ __('messages.inactive') }}
                    </option>
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

    <x-card class="subtle-panel" style="border: 1px solid rgba(220,38,38,0.45);">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title" style="color: #dc2626;">
                {{ __('messages.danger_zone') }}
            </h2>

            <p class="section-subtitle">
                {{ __('messages.delete_this_category_with_its_subcategories') }}
            </p>
        </div>

        <div class="info-row" style="border-top: 1px solid rgba(220,38,38,0.25);">
            <div>
                <div class="info-label" style="color: #dc2626;">
                    {{ __('messages.delete_category') }}
                </div>

                <div class="text-muted">
                    {{ __('messages.deleting_this_category_will_also_delete_all_related_subcategories') }}
                </div>
            </div>

            <button
                type="button"
                class="btn btn-danger"
                onclick="openDeleteModal()"
            >
                {{ __('messages.delete_category') }}
            </button>
        </div>
    </x-card>

    <div
        id="deleteModal"
        style="
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.72);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        "
    >
        <div
            style="
                width: 520px;
                max-width: 92%;
                background: #05070d;
                border: 1px solid #30363d;
                border-radius: 16px;
                overflow: hidden;
                color: #f0f6fc;
            "
        >
            <div
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 16px 18px;
                    border-bottom: 1px solid #30363d;
                "
            >
                <strong>
                    Delete {{ $category->name_en }}
                </strong>

                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    style="
                        background: #21262d;
                        border: 0;
                        color: #f0f6fc;
                        border-radius: 8px;
                        width: 34px;
                        height: 34px;
                        cursor: pointer;
                    "
                >
                    ×
                </button>
            </div>

            <div
                style="
                    padding: 28px 18px;
                    text-align: center;
                    border-bottom: 1px solid #30363d;
                "
            >
                <div style="font-size: 34px; margin-bottom: 12px;">
                    📂
                </div>

                <h2 style="margin: 0 0 10px;">
                    {{ $category->name_en }}
                </h2>

                <p style="color: #8b949e; margin: 0;">
                    This action cannot be undone.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('admin.categories.delete', $category) }}"
                style="padding: 18px;"
            >
                @csrf
                @method('DELETE')

                <label
                    style="
                        display: block;
                        font-weight: 800;
                        margin-bottom: 8px;
                    "
                >
                    To confirm, type "{{ $category->name_en }}" in the box below
                </label>

                <input
                    id="deleteConfirmInput"
                    type="text"
                    autocomplete="off"
                    style="
                        width: 100%;
                        box-sizing: border-box;
                        background: #010409;
                        color: #f0f6fc;
                        border: 1px solid #f85149;
                        border-radius: 8px;
                        padding: 12px;
                        margin-bottom: 12px;
                    "
                    oninput="checkDeleteConfirmation()"
                >

                <button
                    id="deleteConfirmButton"
                    type="submit"
                    disabled
                    style="
                        width: 100%;
                        padding: 12px;
                        border-radius: 8px;
                        border: 0;
                        background: #21262d;
                        color: #f85149;
                        font-weight: 900;
                        cursor: not-allowed;
                    "
                >
                    Delete this category
                </button>
            </form>
        </div>
    </div>

    <script>
        const requiredCategoryName = @json($category->name_en);

        function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';

            document.getElementById('deleteConfirmInput').value = '';

            checkDeleteConfirmation();
        }

        function checkDeleteConfirmation() {
            const input = document.getElementById('deleteConfirmInput');
            const button = document.getElementById('deleteConfirmButton');

            if (input.value === requiredCategoryName) {
                button.disabled = false;
                button.style.background = '#da3633';
                button.style.color = '#ffffff';
                button.style.cursor = 'pointer';
            } else {
                button.disabled = true;
                button.style.background = '#21262d';
                button.style.color = '#f85149';
                button.style.cursor = 'not-allowed';
            }
        }
    </script>
@endsection
