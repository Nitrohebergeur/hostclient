<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('hostclient.company_name', 'HostClient') }}</title>
    <meta name="description" content="Plateforme de gestion de services d'hébergement et facturation.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Offres section ── */
        .hc-offers-section {
            padding: 4rem 0;
            background: var(--hc-bg-secondary, #f8f9fa);
        }
        .hc-offers-section .hc-section-head {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .hc-offers-section .hc-section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .hc-offers-section .hc-section-subtitle {
            color: var(--hc-text-muted, #6b7280);
            font-size: 1rem;
        }
        .hc-pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .hc-pricing-card {
            background: white;
            border: 1px solid var(--hc-border, #e5e7eb);
            border-radius: 0.75rem;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .hc-pricing-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .hc-pricing-card.featured {
            border-color: var(--hc-primary, #2563eb);
            box-shadow: 0 0 0 2px var(--hc-primary, #2563eb);
        }
        .hc-pricing-badge {
            display: inline-block;
            background: var(--hc-primary, #2563eb);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .hc-pricing-name {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }
        .hc-pricing-desc {
            color: var(--hc-text-muted, #6b7280);
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            flex: 1;
        }
        .hc-pricing-price {
            display: flex;
            align-items: baseline;
            gap: 0.3rem;
            margin-bottom: 1.25rem;
        }
        .hc-pricing-price .amount {
            font-size: 2rem;
            font-weight: 800;
        }
        .hc-pricing-price .cycle {
            font-size: 0.85rem;
            color: var(--hc-text-muted, #6b7280);
        }
        .hc-pricing-cta {
            display: block;
            text-align: center;
            background: var(--hc-primary, #2563eb);
            color: white;
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.9rem;
            transition: opacity 0.15s;
        }
        .hc-pricing-cta:hover { opacity: 0.88; }
        .hc-pricing-cta.secondary {
            background: var(--hc-bg-secondary, #f3f4f6);
            color: var(--hc-text, #111827);
        }
        .hc-cat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--hc-text-muted, #9ca3af);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1rem;
        }
        .hc-cat-block {
            margin-bottom: 3rem;
        }
        .hc-cat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.25rem;
        }
        .hc-cat-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .hc-cat-header a {
            font-size: 0.875rem;
            color: var(--hc-primary, #2563eb);
            font-weight: 500;
            text-decoration: none;
        }
        .hc-empty-offers {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--hc-text-muted, #6b7280);
        }
        .hc-empty-offers .icon { font-size: 3rem; margin-bottom: 1rem; }
        .hc-empty-offers p { font-size: 1rem; }
    </style>
</head>
<body>

<!-- Topbar -->
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

        <nav style="display:flex; align-items:center; gap:1.5rem;">
            <a href="{{ route('store.index') }}" style="font-size:0.9rem; color:var(--hc-text-muted,#6b7280); text-decoration:none; font-weight:500;">Boutique</a>
            @auth
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('client.dashboard') }}" class="hc-btn hc-btn-primary">
                    Mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="hc-btn hc-btn-ghost">Connexion</a>
                <a href="{{ route('register') }}" class="hc-btn hc-btn-primary">S'inscrire</a>
            @endauth
        </nav>
    </div>
</header>

<!-- Hero -->
<section class="hc-hero">
    <div class="hc-container">
        <div class="hc-hero-eyebrow">
            <span class="hc-hero-dot"></span>
            {{ config('hostclient.company_name', 'HostClient') }} · Espace client
        </div>
        <h1 class="hc-hero-title">
            Gérez vos services<br>
            <span class="hc-hero-accent">depuis un seul endroit</span>
        </h1>
        <p class="hc-hero-subtitle">
            Accédez à votre tableau de bord, vos factures et votre support en quelques clics.
        </p>
        <div class="hc-hero-cta">
            @guest
                <a href="{{ route('store.index') }}" class="hc-btn hc-btn-primary hc-btn-lg">Voir les offres</a>
                <a href="{{ route('login') }}" class="hc-btn hc-btn-secondary hc-btn-lg">Se connecter</a>
            @else
                <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : 'client.dashboard') }}" class="hc-btn hc-btn-primary hc-btn-lg">
                    Accéder à mon espace
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Offres / Produits -->
<section class="hc-offers-section">
    <div class="hc-container">
        <div class="hc-section-head">
            <h2 class="hc-section-title">Nos offres</h2>
            <p class="hc-section-subtitle">Choisissez le service qui correspond à vos besoins.</p>
        </div>

        @php
            $hasProducts = isset($featured) && $featured->count() > 0;
            $hasCategories = isset($categories) && $categories->filter(fn($c) => $c->products->count() > 0)->count() > 0;
        @endphp

        @if($hasProducts)
            {{-- Produits populaires --}}
            <div class="hc-cat-block">
                <div class="hc-cat-header">
                    <h3>⭐ Produits populaires</h3>
                    <a href="{{ route('store.index') }}">Voir tout →</a>
                </div>
                <div class="hc-pricing-grid">
                    @foreach($featured as $product)
                        <div class="hc-pricing-card featured">
                            <span class="hc-pricing-badge">Populaire</span>
                            <div class="hc-pricing-name">{{ $product->name }}</div>
                            <div class="hc-pricing-desc">{{ Str::limit($product->description, 100) }}</div>
                            <div class="hc-pricing-price">
                                <span class="amount">{{ number_format($product->price, 2) }}€</span>
                                <span class="cycle">/ {{ $product->billing_cycle }}</span>
                            </div>
                            <a href="{{ route('store.product', [$product->category, $product]) }}" class="hc-pricing-cta">
                                Commander
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($hasCategories)
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div class="hc-cat-block">
                        <div class="hc-cat-header">
                            <h3>{{ $category->name }}</h3>
                            <a href="{{ route('store.category', $category) }}">Voir tout →</a>
                        </div>
                        @if($category->description)
                            <p style="color:var(--hc-text-muted,#6b7280); font-size:0.875rem; margin-top:-0.75rem; margin-bottom:1rem;">
                                {{ $category->description }}
                            </p>
                        @endif
                        <div class="hc-pricing-grid">
                            @foreach($category->products as $product)
                                <div class="hc-pricing-card">
                                    @if(!$product->isInStock())
                                        <span class="hc-pricing-badge" style="background:#ef4444;">Rupture</span>
                                    @endif
                                    <div class="hc-pricing-name">{{ $product->name }}</div>
                                    <div class="hc-pricing-desc">{{ Str::limit($product->description, 100) }}</div>
                                    <div class="hc-pricing-price">
                                        <span class="amount">{{ number_format($product->price, 2) }}€</span>
                                        <span class="cycle">/ {{ $product->billing_cycle }}</span>
                                    </div>
                                    <a href="{{ route('store.product', [$category, $product]) }}" class="hc-pricing-cta secondary">
                                        Voir les détails
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        @if(!$hasProducts && !$hasCategories)
            <div class="hc-empty-offers">
                <div class="icon">📦</div>
                <p>Aucune offre disponible pour le moment.<br>Revenez bientôt !</p>
            </div>
        @endif

        <div style="text-align:center; margin-top:2rem;">
            <a href="{{ route('store.index') }}" class="hc-btn hc-btn-primary hc-btn-lg">
                Voir toutes les offres
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="hc-section">
    <div class="hc-container">
        <div class="hc-grid hc-grid-3">
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🔐</div>
                <h3>Connexion sécurisée</h3>
                <p>Authentification chiffrée, gestion de vos identifiants et de votre compte.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">📊</div>
                <h3>Tableau de bord</h3>
                <p>Suivez vos services actifs, vos factures et vos tickets en un coup d'œil.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🎧</div>
                <h3>Support 24/7</h3>
                <p>Une question ? Notre équipe est joignable par ticket à tout moment.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="hc-footer">
    <div class="hc-container">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div style="color:var(--hc-gray-400,#9ca3af); font-size:0.875rem;">
                © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}. Tous droits réservés.
            </div>
            <div style="display:flex; gap:1.5rem; font-size:0.875rem;">
                <a href="{{ route('store.index') }}" style="color:var(--hc-gray-400,#9ca3af); text-decoration:none;">Boutique</a>
                <a href="{{ route('login') }}" style="color:var(--hc-gray-400,#9ca3af); text-decoration:none;">Connexion</a>
                <a href="{{ route('register') }}" style="color:var(--hc-gray-400,#9ca3af); text-decoration:none;">Inscription</a>
                @auth
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : 'client.dashboard') }}" style="color:var(--hc-gray-400,#9ca3af); text-decoration:none;">Mon espace</a>
                @endauth
            </div>
        </div>
    </div>
</footer>

</body>
</html>
