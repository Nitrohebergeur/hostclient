@extends('layouts.app')

@section('title', 'Boutique — ' . config('hostclient.company_name', 'HostClient'))

@section('content')
<div class="hc-container" style="padding-top: var(--hc-space-12); padding-bottom: var(--hc-space-16);">

    {{-- Header --}}
    <div class="hc-section-head">
        <h1 class="hc-section-title">Nos services</h1>
        <p class="hc-section-subtitle">Découvrez notre gamme complète de services d'hébergement, conçus pour répondre à tous vos besoins.</p>
    </div>

    {{-- Produits populaires --}}
    @if($featured->count())
        <section style="margin-bottom: var(--hc-space-16);">
            <h2 style="font-size: var(--hc-text-2xl); font-weight: 600; margin-bottom: var(--hc-space-6);">Produits populaires</h2>

            <div class="hc-grid hc-grid-3">
                @foreach($featured as $product)
                    <x-card padding="false">
                        <div style="padding: var(--hc-space-6);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-3);">
                                <x-badge variant="success">Populaire</x-badge>
                                <span style="font-size: var(--hc-text-sm); color: var(--hc-text-muted);">{{ $product->category->name }}</span>
                            </div>

                            <h3 style="font-size: var(--hc-text-xl); font-weight: 600; margin-bottom: var(--hc-space-2);">{{ $product->name }}</h3>
                            <p style="color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">{{ Str::limit($product->description, 120) }}</p>

                            <div style="display: flex; align-items: baseline; gap: var(--hc-space-2); margin-bottom: var(--hc-space-6);">
                                <span style="font-size: var(--hc-text-3xl); font-weight: 800;">{{ number_format($product->price, 2) }}€</span>
                                <span style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">/ {{ $product->billing_cycle }}</span>
                            </div>

                            <x-button :href="route('store.product', [$product->category, $product])" variant="primary" style="width: 100%;">
                                Voir les détails
                            </x-button>
                        </div>
                    </x-card>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Catégories --}}
    <section>
        <h2 style="font-size: var(--hc-text-2xl); font-weight: 600; margin-bottom: var(--hc-space-6);">Toutes les catégories</h2>

        @foreach($categories as $category)
            @if($category->products->count())
                <div style="margin-bottom: var(--hc-space-12);">
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: var(--hc-space-4);">
                        <div>
                            <h3 style="font-size: var(--hc-text-xl); font-weight: 600;">{{ $category->name }}</h3>
                            @if($category->description)
                                <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">{{ $category->description }}</p>
                            @endif
                        </div>
                        <a href="{{ route('store.category', $category) }}" style="font-size: var(--hc-text-sm); color: var(--hc-primary); font-weight: 500;">
                            Voir tout →
                        </a>
                    </div>

                    <div class="hc-grid hc-grid-3">
                        @foreach($category->products->take(6) as $product)
                            <x-card padding="false">
                                <div style="padding: var(--hc-space-5);">
                                    @if(!$product->isInStock())
                                        <x-badge variant="danger">Rupture</x-badge>
                                    @endif

                                    <h4 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-2); margin-top: var(--hc-space-2);">{{ $product->name }}</h4>
                                    <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-bottom: var(--hc-space-4);">{{ Str::limit($product->description, 80) }}</p>

                                    <div style="display: flex; align-items: baseline; gap: var(--hc-space-1); margin-bottom: var(--hc-space-4);">
                                        <span style="font-size: var(--hc-text-2xl); font-weight: 700;">{{ number_format($product->price, 2) }}€</span>
                                        <span style="color: var(--hc-text-muted); font-size: var(--hc-text-xs);">/ {{ $product->billing_cycle }}</span>
                                    </div>

                                    <x-button :href="route('store.product', [$category, $product])" variant="secondary" size="sm" style="width: 100%;">
                                        Voir les détails
                                    </x-button>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </section>

    {{-- Empty state --}}
    @if($categories->every(fn($c) => $c->products->count() === 0))
        <x-empty-state
            title="Aucun produit disponible"
            description="Nous préparons de nouveaux services pour vous. Revenez bientôt !"
            icon="📦"
        />
    @endif
</div>
@endsection