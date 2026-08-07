<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('hostclient.company_name', 'HostClient') }}</title>
    <meta name="description" content="Plateforme de gestion de services d'hébergement et facturation.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        <div class="hc-topbar-actions">
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

<!-- Hero minimal -->
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
                <a href="{{ route('login') }}" class="hc-btn hc-btn-primary hc-btn-lg">Se connecter</a>
                <a href="{{ route('register') }}" class="hc-btn hc-btn-secondary hc-btn-lg">Créer un compte</a>
            @else
                <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : 'client.dashboard') }}" class="hc-btn hc-btn-primary hc-btn-lg">
                    Accéder à mon espace
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Quick info row -->
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
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--hc-space-4);">
            <div style="color: var(--hc-gray-400); font-size: var(--hc-text-sm);">
                © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}. Tous droits réservés.
            </div>
            <div style="display: flex; gap: var(--hc-space-6); font-size: var(--hc-text-sm);">
                <a href="{{ route('login') }}" style="color: var(--hc-gray-400); text-decoration: none;">Connexion</a>
                <a href="{{ route('register') }}" style="color: var(--hc-gray-400); text-decoration: none;">Inscription</a>
                @auth
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : 'client.dashboard') }}" style="color: var(--hc-gray-400); text-decoration: none;">Mon espace</a>
                @endauth
            </div>
        </div>
    </div>
</footer>

</body>
</html>