@props([
    'type' => 'info', // success, danger, warning, info
])

@if (session($type) || (isset($slot) && trim($slot) !== ''))
    <div {{ $attributes->merge(['class' => 'hc-alert hc-alert-' . $type]) }} role="alert">
        <div class="flex-1">
            {{ $slot ?: session($type) }}
        </div>
    </div>
@endif
