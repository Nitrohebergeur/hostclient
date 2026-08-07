@props([
    'header' => null,
    'padding' => true,
    'actions' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'hc-card']) }}>
    @if($header || trim($actions ?? '') !== '' || isset($actionsSlot))
        <div class="hc-card-header">
            <div>
                @if($header)
                    <h3>{{ $header }}</h3>
                @endif
                @if($subtitle)
                    <p>{{ $subtitle }}</p>
                @endif
            </div>
            @if(trim($actions ?? '') !== '')
                <div class="hc-card-header-actions">
                    {!! $actions !!}
                </div>
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'hc-card-body' : '' }}">
        {{ $slot }}
    </div>
</div>