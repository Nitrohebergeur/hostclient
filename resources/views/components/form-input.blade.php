@props([
    'label' => null,
    'name' => '',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => '',
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="hc-label">
            {{ $label }}
            @if($required)<span style="color: var(--hc-danger);">*</span>@endif
        </label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'hc-input']) }}
    >
    @error($name)
        <p style="color: var(--hc-danger); font-size: var(--hc-text-xs); margin-top: var(--hc-space-1);">{{ $message }}</p>
    @enderror
</div>
