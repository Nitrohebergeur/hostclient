@props([
    'label' => '',
    'value' => '',
    'delta' => null,
    'deltaType' => 'neutral', // positive, negative, neutral
])

<div class="hc-stat">
    <div class="hc-stat-label">{{ $label }}</div>
    <div class="hc-stat-value">{{ $value }}</div>
    @if($delta)
        <div class="hc-stat-delta" style="color: {{ $deltaType === 'positive' ? 'var(--hc-success)' : ($deltaType === 'negative' ? 'var(--hc-danger)' : 'var(--hc-text-muted)') }};">
            {{ $delta }}
        </div>
    @endif
</div>
