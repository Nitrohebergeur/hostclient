@extends('layouts.app')

@section('title', 'Panier — ' . config('hostclient.company_name', 'HostClient'))

@section('content')
<div class="hc-container" style="padding-top: var(--hc-space-12); padding-bottom: var(--hc-space-16);">

    <h1 style="font-size: var(--hc-text-3xl); font-weight: 700; margin-bottom: var(--hc-space-8);">Mon panier</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif

    @if(empty($cart))
        <x-empty-state
            title="Votre panier est vide"
            description="Découvrez nos services et ajoutez-les à votre panier."
            icon="🛒"
        >
            <x-button :href="route('store.index')" variant="primary">Continuer les achats</x-button>
        </x-empty-state>
    @else
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-8);">
            {{-- Articles --}}
            <div>
                <x-card :header="'Articles (' . count($cart) . ')'">
                    @foreach($cart as $key => $item)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--hc-space-4) 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--hc-border);' : '' }}">
                            <div style="flex: 1;">
                                <h3 style="font-size: var(--hc-text-base); font-weight: 600;">{{ $item['name'] }}</h3>
                                <p style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin-top: var(--hc-space-1);">
                                    {{ ucfirst($item['billing_cycle']) }} · Quantité : {{ $item['quantity'] }}
                                </p>
                                @if($item['setup_fee'] > 0)
                                    <p style="font-size: var(--hc-text-xs); color: var(--hc-text-subtle); margin-top: var(--hc-space-1);">
                                        + {{ number_format($item['setup_fee'] * $item['quantity'], 2) }} € frais d'installation
                                    </p>
                                @endif
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: var(--hc-text-lg); font-weight: 700;">
                                    {{ number_format($item['price'] * $item['quantity'], 2) }} €
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div style="margin-top: var(--hc-space-6); padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                        <a href="{{ route('store.index') }}" style="color: var(--hc-primary); font-size: var(--hc-text-sm); font-weight: 500;">
                            ← Continuer les achats
                        </a>
                    </div>
                </x-card>
            </div>

            {{-- Résumé --}}
            <div>
                <x-card header="Résumé">
                    <div style="display: flex; flex-direction: column; gap: var(--hc-space-3); margin-bottom: var(--hc-space-6);">
                        <div style="display: flex; justify-content: space-between; font-size: var(--hc-text-sm);">
                            <span style="color: var(--hc-text-muted);">Sous-total</span>
                            <span>{{ number_format($subtotal, 2) }} €</span>
                        </div>

                        @if($setupFee > 0)
                            <div style="display: flex; justify-content: space-between; font-size: var(--hc-text-sm);">
                                <span style="color: var(--hc-text-muted);">Frais d'installation</span>
                                <span>{{ number_format($setupFee, 2) }} €</span>
                            </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; font-size: var(--hc-text-sm);">
                            <span style="color: var(--hc-text-muted);">TVA ({{ config('hostclient.tax_rate', 20) }}%)</span>
                            <span>{{ number_format($tax, 2) }} €</span>
                        </div>

                        <div style="border-top: 1px solid var(--hc-border); padding-top: var(--hc-space-3); display: flex; justify-content: space-between;">
                            <span style="font-weight: 600;">Total</span>
                            <span style="font-weight: 700; font-size: var(--hc-text-lg);">{{ number_format($total, 2) }} €</span>
                        </div>
                    </div>

                    @auth
                        <form action="{{ route('store.checkout') }}" method="POST">
                            @csrf

                            {{-- Moyens de paiement --}}
                            <div style="margin-bottom: var(--hc-space-5);">
                                <label class="hc-label">Mode de paiement</label>
                                <div style="display: flex; flex-direction: column; gap: var(--hc-space-2);">
                                    @foreach($gateways as $gateway)
                                        <label style="display: flex; align-items: center; padding: var(--hc-space-3); border: 1px solid var(--hc-border); border-radius: var(--hc-radius); cursor: pointer; transition: border-color var(--hc-transition);">
                                            <input type="radio" name="payment_method" value="{{ $gateway->slug }}" required style="margin-right: var(--hc-space-3);">
                                            <span style="font-weight: 500;">{{ $gateway->name }}</span>
                                        </label>
                                    @endforeach

                                    @if(auth()->user()->balance >= $total)
                                        <label style="display: flex; align-items: center; padding: var(--hc-space-3); border: 1px solid var(--hc-border); border-radius: var(--hc-radius); cursor: pointer;">
                                            <input type="radio" name="payment_method" value="balance" style="margin-right: var(--hc-space-3);">
                                            <div>
                                                <div style="font-weight: 500;">Solde du compte</div>
                                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                                    Disponible : {{ number_format(auth()->user()->balance, 2) }} €
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                </div>
                            </div>

                            {{-- Code promo --}}
                            <x-form-input
                                label="Code promo (optionnel)"
                                name="coupon_code"
                                placeholder="Entrez votre code"
                            />

                            <x-button type="submit" variant="primary" size="lg" style="width: 100%;">
                                Procéder au paiement
                            </x-button>

                            <p style="text-align: center; font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: var(--hc-space-4);">
                                En validant, vous acceptez nos conditions d'utilisation.
                            </p>
                        </form>
                    @else
                        <div style="text-align: center;">
                            <p style="color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">Connectez-vous pour finaliser votre commande</p>
                            <x-button :href="route('login')" variant="primary" style="width: 100%; margin-bottom: var(--hc-space-2);">
                                Se connecter
                            </x-button>
                            <x-button :href="route('register')" variant="secondary" style="width: 100%;">
                                Créer un compte
                            </x-button>
                        </div>
                    @endauth
                </x-card>

                <p style="text-align: center; font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin-top: var(--hc-space-4);">
                    🔒 Paiement 100% sécurisé
                </p>
            </div>
        </div>
    @endif
</div>

<style>
@media (max-width: 768px) {
    .hc-container > div[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection