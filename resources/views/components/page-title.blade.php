@props([
    'title',
    'subtitle' => null,
])

<div class="page-title">
    <div class="page-title-top">
        <h1>{{ $title }}</h1>

        <div class="section-actions">
            {{ $actions ?? '' }}
        </div>
    </div>

    @if($subtitle)
        <p>{{ $subtitle }}</p>
    @endif
</div>
