@extends('layouts.app')

@section('title', $product->name . ' — ' . config('hostclient.company_name', 'HostClient'))

@section('content')
<div class="hc-container" style="padding-top: var(--hc-space-8); padding-bottom: var(--hc-space-16);">

    {{-- Breadcrumb --}}
    <nav style="margin-bottom: var(--hc-space-6); font-size: var(--hc-text-sm);">
        <a href="{{ route('store.index') }}" style="color: var(--hc-text-muted);">Boutique</a>
        <span style="color: var(--hc-text-subtle); margin: 0 var(--hc-space-2);">/</span>
        <a href="{{ route('store.category', $category) }}" style="color: var(--hc-text-muted);">{{ $category->name }}</a>
        <span style="color: var(--hc-text-subtle); margin: 0 var(--hc-space-2);">/</span>
        <span style="color: var(--hc-text); font-weight: 500;">{{ $product->name }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-8);">
        {{-- Détails --}}
        <div>
            <x-card>
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: var(--hc-space-4);">
                    <div>
                        <h1 style="font-size: var(--hc-text-3xl); font-weight: 700; margin-bottom: var(--hc-space-2);">{{ $product->name }}</h1>
                        <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm);">{{ $category->name }}</p>
                    </div>
                    @if(!$product->isInStock())
                        <x-badge variant="danger">En rupture</x-badge>
                    @elseif($product->is_featured)
                        <x-badge variant="success">Populaire</x-badge>
                    @endif
                </div>

                <p style="font-size: var(--hc-text-base); color: var(--hc-text-muted); line-height: 1.6; margin-bottom: var(--hc-space-6);">
                    {{ $product->description }}
                </p>

                @if($product->features && is_array($product->features) && count($product->features) > 0)
                    <h3 style="font-size: var(--hc-text-lg); font-weight: 600; margin-bottom: var(--hc-space-3);">Caractéristiques</h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($product->features as $feature)
                            <li style="padding: var(--hc-space-2) 0; display: flex; align-items: center; gap: var(--hc-space-3);">
                                <span style="color: var(--hc-success);">✓</span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>

        {{-- Sidebar commande --}}
        <div>
            <x-card>
                <div style="margin-bottom: var(--hc-space-6);">
                    <div style="font-size: var(--hc-text-4xl); font-weight: 800; line-height: 1;">
                        {{ number_format($product->price, 2) }} €
                    </div>
                    <div style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-top: var(--hc-space-1);">
                        HT / {{ $product->billing_cycle }}
                    </div>
                    @if($product->setup_fee > 0)
                        <div style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-top: var(--hc-space-2);">
                            + {{ number_format($product->setup_fee, 2) }} € de frais d'installation
                        </div>
                    @endif
                </div>

                <form action="{{ route('store.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <x-form-select
                        label="Cycle de facturation"
                        name="billing_cycle"
                        :options="['monthly' => 'Mensuel', 'yearly' => 'Annuel']"
                        required
                    />

                    <x-form-input
                        label="Quantité"
                        name="quantity"
                        type="number"
                        :value="1"
                        placeholder="1"
                    />

                    <x-button type="submit" variant="primary" size="lg" style="width: 100%;">
                        Ajouter au panier
                    </x-button>
                </form>

                <div style="margin-top: var(--hc-space-6); padding-top: var(--hc-space-6); border-top: 1px solid var(--hc-border); font-size: var(--hc-text-sm); color: var(--hc-text-muted);">
                    <p style="margin-bottom: var(--hc-space-2);">✓ Activation immédiate</p>
                    <p style="margin-bottom: var(--hc-space-2);">✓ Support 24/7 inclus</p>
                    <p>✓ Migration gratuite sur demande</p>
                </div>
            </x-card>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .hc-container > div[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection