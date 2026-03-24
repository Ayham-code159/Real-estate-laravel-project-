@props([
    'label' => '',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-input']) }}
    >
</div>
