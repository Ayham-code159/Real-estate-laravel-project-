@extends('layouts.app')

@section('title', 'Subcategory Details')

@section('content')
    <style>
        .custom-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .custom-pagination a,
        .custom-pagination span {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border: 1px solid #d8c8ff;
            background: rgba(255, 255, 255, 0.85);
            color: var(--primary);
            transition: 0.22s ease;
        }

        .custom-pagination a:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 0 20px rgba(111, 60, 195, 0.35);
            transform: translateY(-2px);
        }

        .custom-pagination .active-page {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            color: white;
            box-shadow: 0 14px 30px rgba(111, 60, 195, 0.25);
        }
    </style>

    <x-page-title
        :title="__('messages.subcategory_details')"
        subtitle="View and manage this subcategory dynamic fields."
    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.show', $subcategory->category) }}" class="btn btn-outline">
                ← Back
            </a>

            <a href="{{ route('admin.categories.fields.create', $subcategory) }}" class="btn btn-primary">
                ＋ Add Field
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

        <div class="info-list" style="margin-top: 20px;">
            <div class="info-row">
                <div class="info-label">{{ __('messages.english_name') }}
</div>
                <div class="info-value">{{ $subcategory->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.arabic_name') }}
</div>
                <div class="info-value">{{ $subcategory->name_ar ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.description') }}</div>
                <div class="info-value">{{ $subcategory->description ?? 'No description' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.fields_count') }}</div>
                <div class="info-value">{{ $fields->total() }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ __('messages.dynamic_fields') }}</h2>
                <p class="section-subtitle">
                    These fields will later appear when users create items under this subcategory.
                </p>
            </div>

            <a href="{{ route('admin.categories.fields.create', $subcategory) }}" class="btn btn-primary">
                ＋ {{ __('messages.add_field') }}
            </a>
        </div>

        @forelse($fields as $field)
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
                                @elseif($field->field_type === 'date')
                                    Date range:
                                    {{ $field->min_date ? $field->min_date->format('Y-m-d') : '∞' }}
                                    -
                                    {{ $field->max_date ? $field->max_date->format('Y-m-d') : '∞' }}
                                @elseif($field->field_type === 'select')
                                    Choices:
                                    {{ is_array($field->options) ? implode(', ', $field->options) : 'N/A' }}
                                @else
                                    Yes / No field
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
                    If no fields are added, users can still create simple items under this subcategory.
                </p>
            </div>
        @endforelse

        @if($fields->hasPages())
            <div class="custom-pagination">
                @for($page = 1; $page <= $fields->lastPage(); $page++)
                    @if($page === $fields->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $fields->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </x-card>
@endsection
