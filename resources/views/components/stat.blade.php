@props([
    'label' => '',
    'value' => '',
    'delta' => null,
    'deltaType' => 'neutral', // positive, negative, neutral
    'icon' => null,
    'color' => 'primary', // primary | success | warning | danger | info
    'subtext' => null,
])

@php
    $colorVar = match($color) {
        'success' => 'var(--hc-success)',
        'warning' => 'var(--hc-warning)',
        'danger' => 'var(--hc-danger)',
        'info' => 'var(--hc-info)',
        default => 'var(--hc-primary)',
    };
    $colorBg = match($color) {
        'success' => 'var(--hc-success-50)',
        'warning' => 'var(--hc-warning-50)',
        'danger' => 'var(--hc-danger-50)',
        'info' => 'var(--hc-info-50)',
        default => 'var(--hc-primary-50)',
    };
@endphp

<div class="hc-stat" style="position: relative;">
    @if($icon)
        <div style="position: absolute; top: var(--hc-space-4); right: var(--hc-space-4); width: 40px; height: 40px; background: {{ $colorBg }}; color: {{ $colorVar }}; border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
            <i data-lucide="{{ $icon }}" style="width: 20px; height: 20px;"></i>
        </div>
    @endif
    <div class="hc-stat-label">{{ $label }}</div>
    <div class="hc-stat-value">{{ $value }}</div>
    @if($subtext)
        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: var(--hc-space-1);">{{ $subtext }}</div>
    @endif
    @if($delta)
        <div class="hc-stat-delta" style="display: inline-flex; align-items: center; gap: 4px; margin-top: var(--hc-space-2); font-size: var(--hc-text-xs); font-weight: 600; color: {{ $deltaType === 'positive' ? 'var(--hc-success)' : ($deltaType === 'negative' ? 'var(--hc-danger)' : 'var(--hc-text-muted)') }};">
            @if($deltaType === 'positive')
                <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
            @elseif($deltaType === 'negative')
                <i data-lucide="trending-down" style="width: 12px; height: 12px;"></i>
            @endif
            {{ $delta }}
        </div>
    @endif
</div>