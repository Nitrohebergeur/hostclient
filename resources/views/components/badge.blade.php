@props([
    'variant' => 'neutral', // success, danger, warning, info, neutral
])

<span {{ $attributes->merge(['class' => 'hc-badge hc-badge-' . $variant]) }}>
    {{ $slot }}
</span>
