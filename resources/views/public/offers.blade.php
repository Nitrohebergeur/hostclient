<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos offres — {{ config('hostclient.company_name', 'HostClient') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<header class="hc-topbar">
    <div class="hc-container hc-topbar-inner">
        <a href="{{ route('home') }}" class="hc-topbar-brand">
            <div class="hc-brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12 L12 3 L21 12 M5 10 V21 H19 V10"/>
                </svg>
            </div>
            <span>{{ config('hostclient.company_name', 'HostClient') }}</span>
        </a>
        <div class="hc-topbar-actions">
            <a href="{{ route('home') }}" class="hc-btn hc-btn-ghost">Accueil</a>
            @auth
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" class="hc-btn hc-btn-primary">
                    Mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="hc-btn hc-btn-ghost">Connexion</a>
                <a href="{{ route('register') }}" class="hc-btn hc-btn-primary">S'inscrire</a>
            @endauth
        </div>
    </div>
</header>

<section class="hc-section">
    <div class="hc-container">
        <div class="hc-section-head">
            <h1 class="hc-section-title">Nos offres</h1>
            <p class="hc-section-subtitle">Découvrez l'ensemble de nos services disponibles à la commande.</p>
        </div>

        @if($featured->count() > 0)
            <h2 style="font-size: var(--hc-text-xl); font-weight: 600; margin-bottom: var(--hc-space-4);">Offres en vedette</h2>
            <div class="hc-grid hc-grid-3" style="margin-bottom: var(--hc-space-12);">
                @foreach($featured as $product)
                    <div class="hc-pricing-card">
                        <x-badge variant="success" style="position: absolute; top: var(--hc-space-4); left: var(--hc-space-4);">Populaire</x-badge>
                        <h3 class="hc-pricing-name">{{ $product->name }}</h3>
                        <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-bottom: var(--hc-space-4); min-height: 40px;">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        <div class="hc-pricing-price">
                            <span class="hc-pricing-amount">{{ number_format($product->price, 2) }} €</span>
                            <span class="hc-pricing-period">HT / {{ $product->billing_cycle }}</span>
                        </div>
                        <a href="{{ route('store.product', [$product->category, $product]) }}" class="hc-btn hc-btn-primary" style="width: 100%;">
                            Voir l'offre
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @foreach($categories as $category)
            @if($category->products->count() > 0)
                <div style="margin-bottom: var(--hc-space-12);">
                    <h2 style="font-size: var(--hc-text-xl); font-weight: 600; margin-bottom: var(--hc-space-2);">{{ $category->name }}</h2>
                    @if($category->description)
                        <p style="color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">{{ $category->description }}</p>
                    @endif

                    <div class="hc-grid hc-grid-3">
                        @foreach($category->products as $product)
                            <div class="hc-pricing-card">
                                <h3 class="hc-pricing-name">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-bottom: var(--hc-space-4); min-height: 40px;">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                @endif
                                <div class="hc-pricing-price">
                                    <span class="hc-pricing-amount">{{ number_format($product->price, 2) }} €</span>
                                    <span class="hc-pricing-period">HT / {{ $product->billing_cycle }}</span>
                                </div>
                                <a href="{{ route('store.product', [$category, $product]) }}" class="hc-btn hc-btn-secondary" style="width: 100%;">
                                    Voir l'offre
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($categories->every(fn($c) => $c->products->count() === 0))
            <x-card>
                <x-empty-state
                    title="Aucune offre disponible"
                    description="Les offres seront publiées prochainement. Revenez bientôt !"
                    icon="📦"
                />
            </x-card>
        @endif
    </div>
</section>

<footer class="hc-footer">
    <div class="hc-container">
        <div style="color: var(--hc-gray-400); font-size: var(--hc-text-sm); text-align: center;">
            © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}. Tous droits réservés.
        </div>
    </div>
</footer>

</body>
</html>