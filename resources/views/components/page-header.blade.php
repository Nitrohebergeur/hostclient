@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'padding' => true,
    'breadcrumb' => null,
])

<div {{ $attributes->merge(['class' => 'hc-page-header']) }}>
    @if($breadcrumb)
        <div class="hc-breadcrumb">
            {{ $breadcrumb }}
        </div>
    @endif
    <div class="hc-page-header-row">
        <div class="hc-page-header-title">
            @if($title)
                <h2>{{ $title }}</h2>
                @if($subtitle)
                    <p>{{ $subtitle }}</p>
                @endif
            @endif
        </div>
        @if($actions)
            <div class="hc-page-header-actions">
                {{ $actions }}
            </div>
        @endif
    </div>

    <div class="{{ $padding ? 'hc-card-body' : '' }}">
        {{ $slot }}
    </div>
</div>