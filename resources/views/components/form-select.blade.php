@props([
    'label' => null,
    'name' => '',
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="hc-label">
            {{ $label }}
            @if($required)<span style="color: var(--hc-danger);">*</span>@endif
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'hc-select']) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $key => $option)
            <option value="{{ $key }}" @selected(old($name, $value) == $key)>{{ $option }}</option>
        @endforeach
    </select>
    @error($name)
        <p style="color: var(--hc-danger); font-size: var(--hc-text-xs); margin-top: var(--hc-space-1);">{{ $message }}</p>
    @enderror
</div>
