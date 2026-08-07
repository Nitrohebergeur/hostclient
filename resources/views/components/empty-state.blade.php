@props([
    'title' => 'Aucune donnée',
    'description' => '',
    'icon' => '📭',
])

<div class="text-center py-12">
    <div style="font-size: 48px; margin-bottom: var(--hc-space-4);">{{ $icon }}</div>
    <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-2);">{{ $title }}</h3>
    @if($description)
        <p style="color: var(--hc-text-muted);">{{ $description }}</p>
    @endif
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
