@extends('layouts.app')

@section('title', 'Categories')

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
        :title="__('messages.categories')"

    >
        <x-slot:actions>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <span>＋</span>
                <span>{{ __('messages.add_categories') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.all_categories') }}</h2>

        </div>

        @forelse($categories as $category)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
                        <div>
                            <h3 style="margin: 0 0 6px; font-size: 24px;">
                                {{ $category->name }}
                            </h3>

                            <p class="text-muted" style="margin: 0;">
                            </p>
                        </div>

                        <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $category->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </div>

                    <div class="grid grid-4" style="margin-top: 20px;">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.english_name') }}</div>
                            <div style="font-weight: 800;">{{ $category->name_en }}</div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.arabic_name') }}</div>
                            <div style="font-weight: 800;">{{ $category->name_ar ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">{{ __('messages.subcategories') }}</div>
                            <div style="font-weight: 800;">{{ $category->subcategories_count }}</div>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: end;">
                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-primary">
                                👁 {{ __('messages.view_details') }}
                            </a>

                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline">
                                ✏️ {{ __('messages.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🗂️</div>
                <h3>No categories yet</h3>
                <p>Create your first category to start building the new item system.</p>
            </div>
        @endforelse

        @if($categories->hasPages())
            <div class="custom-pagination">
                @for($page = 1; $page <= $categories->lastPage(); $page++)
                    @if($page === $categories->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $categories->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </x-card>
@endsection
