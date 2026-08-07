@props([
    'header' => null,
    'padding' => true,
    'actions' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'hc-card']) }}>
    @if($header || $actions)
        <div class="hc-card-header" style="display: flex; align-items: center; justify-content: space-between; gap: var(--hc-space-3);">
            <div>
                @if($header)
                    <h3 style="margin: 0; font-size: var(--hc-text-base); font-weight: 600;">{{ $header }}</h3>
                @endif
                @if($subtitle)
                    <p style="margin: 2px 0 0; font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $subtitle }}</p>
                @endif
            </div>
            @if($actions)
                <div style="display: flex; align-items: center; gap: var(--hc-space-2);">
                    {!! $actions !!}
                </div>
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'hc-card-body' : '' }}">
        {{ $slot }}
    </div>
</div>