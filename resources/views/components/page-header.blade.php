@props([
    'title' => null,
    'actions' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'hc-card']) }} style="margin-bottom: var(--hc-space-6);">
    @if($title || $actions)
        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-5) var(--hc-space-6); border-bottom: 1px solid var(--hc-border); gap: var(--hc-space-4); flex-wrap: wrap;">
            @if($title)
                <h2 style="font-size: var(--hc-text-lg); font-weight: 600; margin: 0;">{{ $title }}</h2>
            @endif
            @if($actions)
                <div style="display: flex; gap: var(--hc-space-2);">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'hc-card-body' : '' }}">
        {{ $slot }}
    </div>
</div>