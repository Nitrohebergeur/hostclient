@props([
    'header' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'hc-card']) }}>
    @if($header)
        <div class="hc-card-header">{{ $header }}</div>
    @endif
    <div class="{{ $padding ? 'hc-card-body' : '' }}">
        {{ $slot }}
    </div>
</div>
