@extends('layouts.app')

@section('title', 'Create Dynamic Field')

@section('content')
    <style>
        .switch-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .switch-wrapper input {
            display: none;
        }

        .switch-slider {
            width: 54px;
            height: 30px;
            border-radius: 999px;
            background: #d7dbe8;
            position: relative;
            transition: 0.22s ease;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.04);
        }

        .switch-slider::after {
            content: "";
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: white;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: 0.22s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
        }

        .switch-wrapper input:checked + .switch-slider {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            box-shadow: 0 0 20px rgba(111, 60, 195, 0.24);
        }

        .switch-wrapper input:checked + .switch-slider::after {
            transform: translateX(24px);
        }
    </style>

    <x-page-title
        :title="__('messages.create_dynamic_field')"
        subtitle="Add a custom field for this subcategory."
    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.show', $subcategory->category) }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div>
                <h2 class="section-title">{{ $subcategory->name }}</h2>
                <p class="section-subtitle">
                    Parent category: <strong>{{ $subcategory->category->name }}</strong>
                </p>
            </div>

            <span class="badge {{ $subcategory->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <form id="fieldForm" method="POST" action="{{ route('admin.categories.fields.store', $subcategory) }}">
            @csrf

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.field_name_en') }}
</label>
                    <input
                        type="text"
                        name="label_en"
                        class="form-input"
                        value="{{ old('label_en') }}"
                        placeholder="Example: Fuel Type"
                    >
                    <p class="text-muted" style="font-size: 13px; margin-top: 8px;">
                        The system will automatically create an internal code from this name. Example: Fuel Type becomes fuel_type.
                    </p>

                    @error('label_en')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.display_order') }}
</label>
                    <input
                        type="number"
                        name="sort_order"
                        class="form-input"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                    >
                    <p class="text-muted" style="font-size: 13px; margin-top: 8px;">
                        Lower numbers appear first. Example: 0 appears before 1.
                    </p>

                    @error('sort_order')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.field_name_ar') }}
</label>
                    <input
                        type="text"
                        name="label_ar"
                        class="form-input"
                        value="{{ old('label_ar') }}"
                        placeholder="مثال: نوع الوقود"
                    >

                    @error('label_ar')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.required_field') }}
</label>

                    <input type="hidden" name="is_required" value="0">

                    <label class="switch-wrapper">
                        <input
                            type="checkbox"
                            name="is_required"
                            value="1"
                            {{ old('is_required', '0') == '1' ? 'checked' : '' }}
                        >
                        <span class="switch-slider"></span>
                        <span style="font-weight: 800;">Users must fill this field</span>
                    </label>

                    <p class="text-muted" style="font-size: 13px; margin-top: 8px;">
                        Turn this on if the item cannot be created without this field.
                    </p>

                    @error('is_required')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.field_type') }}
</label>
                <select id="fieldType" name="field_type" class="form-input">
                    <option value="text" {{ old('field_type') === 'text' ? 'selected' : '' }}>{{ __('messages.text') }}
</option>
                    <option value="number" {{ old('field_type') === 'number' ? 'selected' : '' }}>{{ __('messages.number') }}
</option>
                    <option value="select" {{ old('field_type') === 'select' ? 'selected' : '' }}>{{ __('messages.select_options') }}
</option>
                    <option value="boolean" {{ old('field_type') === 'boolean' ? 'selected' : '' }}>{{ __('messages.yes_no') }}
</option>
                    <option value="date" {{ old('field_type') === 'date' ? 'selected' : '' }}>{{ __('messages.date') }}</option>
                </select>

                @error('field_type')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror
            </div>

            <div id="numberRules" class="grid grid-2" style="display: none;">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.minimum_number') }}
</label>
                    <input
                        type="number"
                        step="0.01"
                        id="minValue"
                        name="min_value"
                        class="form-input"
                        value="{{ old('min_value') }}"
                        placeholder="Example: 2020"
                    >

                    @error('min_value')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.maximum_number') }}
</label>
                    <input
                        type="number"
                        step="0.01"
                        id="maxValue"
                        name="max_value"
                        class="form-input"
                        value="{{ old('max_value') }}"
                        placeholder="Example: 2025"
                    >

                    @error('max_value')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="dateRules" class="grid grid-2" style="display: none;">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.minimum_date') }}
</label>
                    <input
                        type="date"
                        id="minDate"
                        name="min_date"
                        class="form-input"
                        value="{{ old('min_date') }}"
                    >

                    @error('min_date')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.maximum_date') }}
</label>
                    <input
                        type="date"
                        id="maxDate"
                        name="max_date"
                        class="form-input"
                        value="{{ old('max_date') }}"
                    >

                    @error('max_date')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="textRules" class="form-group" style="display: none;">
                <label class="form-label">{{ __('messages.text_rule') }}
</label>
                <select id="textRule" name="text_rule" class="form-input">
                    <option value="none" {{ old('text_rule', 'none') === 'none' ? 'selected' : '' }}>No special rule</option>
                    <option value="letters_only" {{ old('text_rule') === 'letters_only' ? 'selected' : '' }}>Letters only</option>
                    <option value="letters_spaces_only" {{ old('text_rule') === 'letters_spaces_only' ? 'selected' : '' }}>Letters and spaces only</option>
                    <option value="alpha_numeric" {{ old('text_rule') === 'alpha_numeric' ? 'selected' : '' }}>Letters and numbers</option>
                </select>

                @error('text_rule')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror
            </div>

            <div id="selectRules" class="form-group" style="display: none;">
                <label class="form-label">{{ __('messages.choices') }}</label>
                <textarea
                    id="optionsText"
                    class="form-input"
                    rows="6"
                    placeholder="Write one choice per line. Example:&#10;Petrol&#10;Diesel&#10;Electric"
                >{{ old('options') ? implode("\n", old('options')) : '' }}</textarea>

                <div id="optionsHiddenInputs"></div>

                @error('options')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror

                @error('options.*')
                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.add_field') }}
</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <script>
        const fieldType = document.getElementById('fieldType');
        const numberRules = document.getElementById('numberRules');
        const dateRules = document.getElementById('dateRules');
        const textRules = document.getElementById('textRules');
        const selectRules = document.getElementById('selectRules');

        const minValue = document.getElementById('minValue');
        const maxValue = document.getElementById('maxValue');
        const minDate = document.getElementById('minDate');
        const maxDate = document.getElementById('maxDate');
        const textRule = document.getElementById('textRule');

        const optionsText = document.getElementById('optionsText');
        const optionsHiddenInputs = document.getElementById('optionsHiddenInputs');
        const fieldForm = document.getElementById('fieldForm');

        function updateFieldRuleVisibility() {
            const type = fieldType.value;

            numberRules.style.display = type === 'number' ? 'grid' : 'none';
            dateRules.style.display = type === 'date' ? 'grid' : 'none';
            textRules.style.display = type === 'text' ? 'block' : 'none';
            selectRules.style.display = type === 'select' ? 'block' : 'none';

            minValue.disabled = type !== 'number';
            maxValue.disabled = type !== 'number';
            minDate.disabled = type !== 'date';
            maxDate.disabled = type !== 'date';
            textRule.disabled = type !== 'text';
        }

        fieldForm.addEventListener('submit', function () {
            optionsHiddenInputs.innerHTML = '';

            if (fieldType.value === 'select') {
                const options = optionsText.value
                    .split('\n')
                    .map(option => option.trim())
                    .filter(option => option.length > 0);

                options.forEach(option => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'options[]';
                    input.value = option;
                    optionsHiddenInputs.appendChild(input);
                });
            }
        });

        fieldType.addEventListener('change', updateFieldRuleVisibility);
        updateFieldRuleVisibility();
    </script>
@endsection
