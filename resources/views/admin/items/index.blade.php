@extends('layouts.app')

@section('title', 'Items')

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
        title="Items"
        subtitle="Review user items and manage their moderation status."
    />

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Items</h2>
            <p class="section-subtitle">
                Showing 10 items per page.
            </p>
        </div>

        @forelse($items as $item)
            <div class="card" style="margin-bottom: 18px; background: rgba(255,255,255,0.72);">
                <div class="card-body">
                    <div class="grid grid-4" style="align-items: center;">
                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Title</div>
                            <div style="font-weight: 800;">{{ $item->title }}</div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Owner / Business</div>
                            <div style="font-weight: 800;">
                                {{ $item->businessAccount?->user?->full_name ?? 'N/A' }}
                            </div>
                            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">
                                {{ $item->businessAccount?->business_name ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted" style="font-size: 13px; margin-bottom: 6px;">Price</div>
                            <div style="font-weight: 800;">
                                ${{ number_format((float) $item->price_usd, 2) }}
                            </div>
                            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">
                                {{ number_format((float) $item->price_syp, 2) }} SYP
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: flex-end;">
                            <span class="badge {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>

                            <a href="{{ route('admin.items.show', $item) }}" class="btn btn-primary">
                                👁 View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>No items yet</h3>
                <p>Items created by users will appear here.</p>
            </div>
        @endforelse

        @if($items->hasPages())
            <div class="custom-pagination">
                @for($page = 1; $page <= $items->lastPage(); $page++)
                    @if($page === $items->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $items->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </x-card>
@endsection
