@extends('layouts.app')

@section('title', 'Category Details')

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
        :title="__('messages.category_details')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
                ← {{ __('messages.back') }}
            </a>

            <a href="{{ route('admin.categories.subcategories.create', $category) }}" class="btn btn-primary">
                ＋ {{ __('messages.add_subcategory') }}
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div>
                <h2 class="section-title">{{ $category->name }}</h2>
                <p class="section-subtitle">{{ $category->description ?? 'No description' }}</p>
            </div>

            <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="info-list" style="margin-top: 20px;">
            <div class="info-row">
                <div class="info-label"> {{ __('messages.english_name') }}
            </div>
                <div class="info-value">{{ $category->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.arabic_name') }}
            </div>
                <div class="info-value">{{ $category->name_ar ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.subcategories_count') }}</div>
                <div class="info-value">{{ $subcategories->total() }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.subcategories') }}</h2>
          
        </div>

        @forelse($subcategories as $subcategory)
            <div class="activity-item" style="margin-bottom: 14px;">
                <div class="activity-icon">🧩</div>

                <div class="activity-body" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <div>
                            <h4 style="margin: 0 0 6px;">{{ $subcategory->name }}</h4>
                            <p style="margin: 0;">{{ $subcategory->description ?? 'No description' }}</p>
                            <p style="margin-top: 6px;">Fields: {{ $subcategory->fields_count }}</p>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span class="badge {{ $subcategory->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <a href="{{ route('admin.categories.subcategories.show', $subcategory) }}" class="btn btn-primary">
                                👁 {{ __('messages.view_details') }}
                            </a>

                            <a href="{{ route('admin.categories.subcategories.edit', $subcategory) }}" class="btn btn-outline">
                                ✏️ {{ __('messages.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No subcategories yet</h3>
                <p>This category can still be used directly by users when creating items.</p>
            </div>
        @endforelse

        @if($subcategories->hasPages())
            <div class="custom-pagination">
                @for($page = 1; $page <= $subcategories->lastPage(); $page++)
                    @if($page === $subcategories->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $subcategories->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </x-card>
@endsection
