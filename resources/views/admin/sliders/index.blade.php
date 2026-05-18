@extends('layouts.app')

@section('title', 'Sliders')

@section('content')
    <style>
        .sliders-page {
            max-width: 1320px;
            margin: 0 auto;
        }

        .slider-filter-card {
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(216, 200, 255, 0.65);
            border-radius: 22px;
            padding: 18px;
            margin-bottom: 24px;
            box-shadow: 0 14px 35px rgba(111, 60, 195, 0.06);
        }

        .slider-shell {
            background: rgba(248, 245, 255, 0.78);
            border: 1px solid rgba(216, 200, 255, 0.55);
            border-radius: 26px;
            padding: 22px;
            box-shadow: 0 18px 45px rgba(111, 60, 195, 0.08);
        }

        .slider-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .slider-card {
            overflow: hidden;
            position: relative;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255,255,255,0.94), rgba(249,246,255,0.94));
            border: 1px solid rgba(216, 200, 255, 0.76);
            box-shadow: 0 10px 26px rgba(25, 18, 50, 0.07);
            transition: 0.22s ease;
        }

        .slider-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 42px rgba(111, 60, 195, 0.14);
            border-color: rgba(139, 92, 246, 0.45);
        }

        .slider-image-wrap {
            height: 150px;
            margin: 10px 10px 0;
            overflow: hidden;
            border-radius: 16px;
            background: #eeeaf8;
            position: relative;
        }

        .slider-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: none;
        }

        .slider-image.active {
            display: block;
        }

        .slider-card:hover .slider-image {
            display: none;
        }

        .slider-card:hover .slider-image.main-image {
            display: block;
        }

        .slider-top-layer {
            position: absolute;
            inset: 16px 16px auto 16px;
            z-index: 3;
            display: flex;
            justify-content: space-between;
            align-items: center;
            pointer-events: none;
        }

        .slider-top-layer > * {
            pointer-events: auto;
        }

        .priority-pill {
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        }

        .priority-normal {
            color: #6F3CC3;
        }

        .priority-high {
            color: #D97706;
        }

        .priority-top {
            color: #DC2626;
        }

        .slider-body {
            padding: 14px 16px 16px;
        }

        .slider-title {
            margin: 0 0 7px;
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -0.01em;
            line-height: 1.25;
        }

        .slider-meta {
            margin: 0 0 10px;
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .slider-price {
            font-weight: 900;
            font-size: 15px;
            margin-bottom: 12px;
            line-height: 1.45;
        }

        .slider-price .syp {
            font-size: 13px;
            font-weight: 800;
            color: var(--text-muted);
        }

        .slider-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .slider-actions .btn,
        .slider-actions button {
            padding: 10px 13px;
            border-radius: 12px;
            font-size: 13px;
        }

        .mini-switch {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .mini-switch input {
            display: none;
        }

        .mini-slider {
            width: 40px;
            height: 22px;
            border-radius: 999px;
            background: #cfd3df;
            position: relative;
            transition: 0.22s ease;
            box-shadow: 0 8px 18px rgba(0,0,0,0.13);
        }

        .mini-slider::after {
            content: "";
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: white;
            position: absolute;
            top: 2.5px;
            left: 3px;
            transition: 0.22s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.18);
        }

        .mini-switch input:checked + .mini-slider {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            box-shadow: 0 0 18px rgba(111, 60, 195, 0.28);
        }

        .mini-switch input:checked + .mini-slider::after {
            transform: translateX(17px);
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 26px;
            flex-wrap: wrap;
        }

        .custom-pagination a,
        .custom-pagination span {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border: 1px solid #d8c8ff;
            background: rgba(255, 255, 255, 0.9);
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

        @media (max-width: 1300px) {
            .slider-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1000px) {
            .slider-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .slider-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="sliders-page">
        <x-page-title
            title="Sliders"
            subtitle="Manage and control approved item sliders."
        />

        <div class="slider-filter-card">
            <form method="GET" action="{{ route('admin.sliders.index') }}">
                <div style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
                    <div style="min-width: 260px; flex: 1; max-width: 360px;">
                        <label class="form-label">Category Filter</label>
                        <select name="category_id" class="form-input">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-button type="submit" variant="primary">
                        🔍 Filter
                    </x-button>

                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="slider-shell">
            @if($sliders->count())
                <div class="slider-grid">
                    @foreach($sliders as $slider)
                        @php
                            $item = $slider->item;
                            $photos = [];

                            if ($item?->main_photo_url) {
                                $photos[] = [
                                    'url' => $item->main_photo_url,
                                    'is_main' => true,
                                ];
                            }

                            foreach ($item?->sub_photo_urls ?? [] as $photo) {
                                $photos[] = [
                                    'url' => $photo['url'],
                                    'is_main' => false,
                                ];
                            }

                            $priorityClass = match ($slider->priority) {
                                'top' => 'priority-top',
                                'high' => 'priority-high',
                                default => 'priority-normal',
                            };
                        @endphp

                        <div class="slider-card" data-slider-card>
                            <div class="slider-top-layer">
                                <span class="priority-pill {{ $priorityClass }}">
                                    {{ $slider->priority_label }}
                                </span>

                                <form method="POST" action="{{ route('admin.sliders.toggle-active', $slider) }}">
                                    @csrf
                                    @method('PUT')

                                    <label class="mini-switch" title="Activate / Deactivate">
                                        <input type="checkbox" onchange="this.form.submit()" {{ $slider->is_active ? 'checked' : '' }}>
                                        <span class="mini-slider"></span>
                                    </label>
                                </form>
                            </div>

                            <div class="slider-image-wrap">
                                @if(count($photos))
                                    @foreach($photos as $index => $photo)
                                        <img
                                            src="{{ $photo['url'] }}"
                                            class="slider-image {{ $index === 0 ? 'active' : '' }} {{ $photo['is_main'] ? 'main-image' : '' }}"
                                            data-slider-image
                                            alt="{{ $item->title }}"
                                        >
                                    @endforeach
                                @else
                                    <div style="height: 150px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-weight: 800;">
                                        No Photo
                                    </div>
                                @endif
                            </div>

                            <div class="slider-body">
                                <h3 class="slider-title">{{ $item->title }}</h3>

                                <p class="slider-meta">
                                    {{ $item->businessAccount?->business_name ?? 'N/A' }}
                                </p>

                                <div class="slider-price">
                                    ${{ number_format((float) $item->price_usd, 2) }}
                                    <br>
                                    <span class="syp">
                                        {{ number_format((float) $item->price_syp, 2) }} SYP
                                    </span>
                                </div>

                                <div class="slider-actions">
                                    <a href="{{ route('admin.sliders.show', $slider) }}" class="btn btn-primary">
                                        👁 View Details
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.sliders.destroy', $slider) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this slider?');">
                                        @csrf
                                        @method('DELETE')

                                        <x-button type="submit" variant="danger">
                                            🗑 Delete
                                        </x-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">🎞️</div>
                    <h3>No sliders yet</h3>
                    <p>Approved items will automatically appear here.</p>
                </div>
            @endif

            @if($sliders->hasPages())
                <div class="custom-pagination">
                    @for($page = 1; $page <= $sliders->lastPage(); $page++)
                        @if($page === $sliders->currentPage())
                            <span class="active-page">{{ $page }}</span>
                        @else
                            <a href="{{ $sliders->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor
                </div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-slider-card]').forEach(card => {
            const images = card.querySelectorAll('[data-slider-image]');

            if (images.length <= 1) {
                return;
            }

            let index = 0;

            setInterval(() => {
                if (card.matches(':hover')) {
                    images.forEach(img => img.classList.remove('active'));

                    const mainImage = card.querySelector('.main-image');
                    if (mainImage) {
                        mainImage.classList.add('active');
                    }

                    return;
                }

                images[index].classList.remove('active');
                index = (index + 1) % images.length;
                images[index].classList.add('active');
            }, 2200);
        });
    </script>
@endsection
