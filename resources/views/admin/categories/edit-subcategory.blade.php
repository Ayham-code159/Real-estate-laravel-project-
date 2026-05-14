@extends('layouts.app')

@section('title', 'Edit Subcategory')

@section('content')
    <x-page-title
    :title="__('messages.edit_subcategory')"

    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.show', $subcategory->category) }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
            </a>

            <a href="{{ route('admin.categories.fields.create', $subcategory) }}" class="btn btn-primary">
                ＋ {{ __('messages.add_field') }}
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div>
                <h2 class="section-title">{{ $subcategory->name }}</h2>

            </div>

            <span class="badge {{ $subcategory->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.categories.subcategories.update', $subcategory) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.english_name') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $subcategory->name_en) }}"
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
                        value="{{ old('name_ar', $subcategory->name_ar) }}"
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
                    >{{ old('description_en', $subcategory->description_en) }}</textarea>
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
                    >{{ old('description_ar', $subcategory->description_ar) }}</textarea>
                    @error('description_ar')
                        <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="is_active" class="form-input">
                    <option value="1" {{ old('is_active', $subcategory->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="0" {{ old('is_active', $subcategory->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.edit_subcategory') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ __('messages.dynamic_fields') }}
</h2>

            </div>

            <a href="{{ route('admin.categories.fields.create', $subcategory) }}" class="btn btn-primary">
                ＋ {{ __('messages.add_field') }}

            </a>
        </div>

        @forelse($subcategory->fields()->orderBy('sort_order')->get() as $field)
            <div class="activity-item" style="margin-bottom: 12px;">
                <div class="activity-icon">📝</div>

                <div class="activity-body" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <div>
                            <h4 style="margin: 0 0 6px;">
                                {{ $field->label_en }}
                                <span class="text-muted">({{ $field->field_key }})</span>
                            </h4>

                            <p style="margin: 0;">
                                Type: <strong>{{ $field->field_type }}</strong> •
                                Required: <strong>{{ $field->is_required ? 'Yes' : 'No' }}</strong>
                            </p>

                            <p style="margin-top: 6px;">
                                @if($field->field_type === 'number')
                                    Range:
                                    {{ $field->min_value ?? '∞' }}
                                    -
                                    {{ $field->max_value ?? '∞' }}
                                @elseif($field->field_type === 'text')
                                    Text rule: {{ $field->text_rule ?? 'none' }}
                                @elseif($field->field_type === 'select')
                                    Options:
                                    {{ is_array($field->options) ? implode(', ', $field->options) : 'N/A' }}
                                @else
                                    No extra rules
                                @endif
                            </p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('admin.categories.fields.edit', $field) }}" class="btn btn-outline">
                                ✏️ {{ __('messages.edit') }}
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.fields.delete', $field) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this field?');">
                                @csrf
                                @method('DELETE')

                                <x-button type="submit" variant="danger">
                                    🗑 {{ __('messages.delete') }}
                                </x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <h3>No dynamic fields yet</h3>
                <p>
                    Add fields if this subcategory needs special information.
                    If no fields are added, users can still create simple items under it.
                </p>
            </div>
        @endforelse
    </x-card>

    <x-card class="subtle-panel">
        <h2 class="section-title">{{ __('messages.danger_zone') }}
</h2>
       

        <form method="POST"
              action="{{ route('admin.categories.subcategories.delete', $subcategory) }}"
              onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>{{ __('messages.delete_subcategory') }}</span>
            </x-button>
        </form>
    </x-card>
@endsection
