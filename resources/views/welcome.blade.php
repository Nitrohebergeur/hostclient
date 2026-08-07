<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('hostclient.company_name', 'HostClient') }} — Hébergement web professionnel</title>
    <meta name="description" content="Hébergement web, VPS et serveurs dédiés avec support 24/7, infrastructure fiable et tarifs transparents.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<!-- Header -->
<header class="hc-header">
    <div class="hc-container hc-header-inner">
        <a href="{{ route('home') }}" class="hc-brand">
            <div class="hc-brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12 L12 3 L21 12 M5 10 V21 H19 V10"/>
                </svg>
            </div>
            <span>{{ config('hostclient.company_name', 'HostClient') }}</span>
        </a>

        <nav class="hc-nav">
            <a href="#hosting">Hébergement</a>
            <a href="#vps">VPS</a>
            <a href="#dedicated">Dédiés</a>
            <a href="#support">Support</a>
        </nav>

        <div class="hc-header-actions">
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

<!-- Hero -->
<section class="hc-hero">
    <div class="hc-container">
        <div class="hc-hero-eyebrow">
            <span class="hc-hero-dot"></span>
            Infrastructure disponible · 99,9% SLA
        </div>
        <h1 class="hc-hero-title">
            L'hébergement web<br>
            <span class="hc-hero-accent">taillé pour la production</span>
        </h1>
        <p class="hc-hero-subtitle">
            Serveurs NVMe, réseau anti-DDoS et support d'experts 24/7.
            Commencez en quelques minutes, scalez quand vous voulez.
        </p>
        <div class="hc-hero-cta">
            <a href="{{ route('register') }}" class="hc-btn hc-btn-primary hc-btn-lg">Créer un compte</a>
            <a href="#hosting" class="hc-btn hc-btn-secondary hc-btn-lg">Voir les offres</a>
        </div>
        <div class="hc-hero-trust">
            <div class="hc-hero-trust-item">
                <strong>15 000+</strong>
                <span>clients en production</span>
            </div>
            <div class="hc-hero-trust-item">
                <strong>99,98%</strong>
                <span>uptime mesuré</span>
            </div>
            <div class="hc-hero-trust-item">
                <strong>< 2 min</strong>
                <span>déploiement</span>
            </div>
        </div>
    </div>
</section>

<!-- Hosting -->
<section id="hosting" class="hc-section">
    <div class="hc-container">
        <div class="hc-section-head">
            <h2 class="hc-section-title">Hébergement mutualisé</h2>
            <p class="hc-section-subtitle">Pour sites WordPress, e-commerce et applications web</p>
        </div>

        <div class="hc-grid hc-grid-3">
            @php
                $sharedPlans = [
                    ['name' => 'Starter', 'price' => '4,99', 'disk' => '10 Go SSD', 'bandwidth' => '100 Go', 'sites' => '1 site'],
                    ['name' => 'Business', 'price' => '9,99', 'disk' => '50 Go SSD', 'bandwidth' => 'Illimitée', 'sites' => '5 sites', 'featured' => true],
                    ['name' => 'Premium', 'price' => '19,99', 'disk' => '200 Go SSD', 'bandwidth' => 'Illimitée', 'sites' => 'Sites illimités'],
                ];
            @endphp
            @foreach($sharedPlans as $plan)
                <div class="hc-pricing-card {{ ($plan['featured'] ?? false) ? 'hc-pricing-card-featured' : '' }}">
                    @if($plan['featured'] ?? false)
                        <div class="hc-pricing-flag">Populaire</div>
                    @endif
                    <h3 class="hc-pricing-name">{{ $plan['name'] }}</h3>
                    <div class="hc-pricing-price">
                        <span class="hc-pricing-currency">€</span>
                        <span class="hc-pricing-amount">{{ $plan['price'] }}</span>
                        <span class="hc-pricing-period">/mois HT</span>
                    </div>
                    <ul class="hc-pricing-features">
                        <li>✓ {{ $plan['disk'] }} NVMe</li>
                        <li>✓ Bande passante {{ $plan['bandwidth'] }}</li>
                        <li>✓ {{ $plan['sites'] }}</li>
                        <li>✓ SSL Let's Encrypt inclus</li>
                        <li>✓ Sauvegardes quotidiennes</li>
                        <li>✓ Support 24/7</li>
                    </ul>
                    <a href="{{ route('register') }}" class="hc-btn {{ ($plan['featured'] ?? false) ? 'hc-btn-primary' : 'hc-btn-secondary' }}" style="width: 100%;">
                        Choisir
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- VPS -->
<section id="vps" class="hc-section hc-section-alt">
    <div class="hc-container">
        <div class="hc-section-head">
            <h2 class="hc-section-title">Serveurs VPS</h2>
            <p class="hc-section-subtitle">Ressources dédiées, accès root, snapshot en un clic</p>
        </div>

        <div class="hc-grid hc-grid-3">
            @php
                $vpsPlans = [
                    ['name' => 'VPS-1', 'cpu' => '2 vCPU', 'ram' => '2 Go', 'disk' => '40 Go NVMe', 'price' => '9,99'],
                    ['name' => 'VPS-2', 'cpu' => '4 vCPU', 'ram' => '4 Go', 'disk' => '80 Go NVMe', 'price' => '19,99', 'featured' => true],
                    ['name' => 'VPS-4', 'cpu' => '8 vCPU', 'ram' => '8 Go', 'disk' => '160 Go NVMe', 'price' => '39,99'],
                ];
            @endphp
            @foreach($vpsPlans as $plan)
                <div class="hc-pricing-card {{ ($plan['featured'] ?? false) ? 'hc-pricing-card-featured' : '' }}">
                    @if($plan['featured'] ?? false)
                        <div class="hc-pricing-flag">Recommandé</div>
                    @endif
                    <h3 class="hc-pricing-name">{{ $plan['name'] }}</h3>
                    <div class="hc-pricing-price">
                        <span class="hc-pricing-currency">€</span>
                        <span class="hc-pricing-amount">{{ $plan['price'] }}</span>
                        <span class="hc-pricing-period">/mois HT</span>
                    </div>
                    <ul class="hc-pricing-features">
                        <li>✓ {{ $plan['cpu'] }} Xeon</li>
                        <li>✓ {{ $plan['ram'] }} DDR4 ECC</li>
                        <li>✓ {{ $plan['disk'] }}</li>
                        <li>✓ IPv4 dédié + /64 IPv6</li>
                        <li>✓ Snapshots quotidiens</li>
                        <li>✓ Anti-DDoS 10 Gbps</li>
                    </ul>
                    <a href="{{ route('register') }}" class="hc-btn {{ ($plan['featured'] ?? false) ? 'hc-btn-primary' : 'hc-btn-secondary' }}" style="width: 100%;">
                        Commander
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Dedicated -->
<section id="dedicated" class="hc-section">
    <div class="hc-container">
        <div class="hc-section-head">
            <h2 class="hc-section-title">Serveurs dédiés</h2>
            <p class="hc-section-subtitle">Performance brute, hardware haute disponibilité</p>
        </div>

        <div class="hc-grid hc-grid-3">
            <div class="hc-feature-card">
                <div class="hc-feature-icon">⚡</div>
                <h3>Processeurs dernière gen</h3>
                <p>Intel Xeon E / AMD EPYC, fréquence turbo jusqu'à 4,5 GHz.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🔒</div>
                <h3>Hardware de confiance</h3>
                <p>RAM ECC, alimentation redondante, RAID matériel.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🌐</div>
                <h3>Bande passante 1 Gbps</h3>
                <p>Trafic illimité, peering de qualité sur les principaux IX.</p>
            </div>
        </div>
        <div class="hc-section-cta">
            <a href="{{ route('register') }}" class="hc-btn hc-btn-primary hc-btn-lg">Demander un devis</a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="hc-section hc-section-alt">
    <div class="hc-container">
        <div class="hc-section-head">
            <h2 class="hc-section-title">Pourquoi nous choisir</h2>
            <p class="hc-section-subtitle">L'infrastructure et le service que votre projet mérite</p>
        </div>

        <div class="hc-grid hc-grid-4">
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🛡️</div>
                <h3>Anti-DDoS inclus</h3>
                <p>Protection multicouche jusqu'à 1,5 Tbps, automatique.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">💾</div>
                <h3>Sauvegardes auto</h3>
                <p>Snapshots quotidiens, restauration en un clic, 30 jours de rétention.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🎧</div>
                <h3>Support 24/7</h3>
                <p>Experts francophones joignables par ticket, chat et téléphone.</p>
            </div>
            <div class="hc-feature-card">
                <div class="hc-feature-icon">🇪🇺</div>
                <h3>Datacenters EU</h3>
                <p>Serveurs en France et Allemagne, conformité RGPD.</p>
            </div>
        </div>
    </div>
</section>

<!-- Support -->
<section id="support" class="hc-section">
    <div class="hc-container">
        <div class="hc-card hc-card-cta">
            <div>
                <h2 style="font-size: var(--hc-text-3xl); font-weight: 700; margin-bottom: var(--hc-space-3);">
                    Une question ? On vous répond.
                </h2>
                <p style="color: var(--hc-text-muted); font-size: var(--hc-text-lg);">
                    Tickets traités en moins de 15 min en heures ouvrées, assistance 24/7 pour les incidents critiques.
                </p>
            </div>
            <a href="{{ route('register') }}" class="hc-btn hc-btn-primary hc-btn-lg">Démarrer maintenant</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="hc-footer">
    <div class="hc-container">
        <div class="hc-footer-grid">
            <div>
                <div class="hc-brand hc-brand-light">
                    <div class="hc-brand-mark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12 L12 3 L21 12 M5 10 V21 H19 V10"/>
                        </svg>
                    </div>
                    <span>{{ config('hostclient.company_name', 'HostClient') }}</span>
                </div>
                <p style="color: var(--hc-gray-400); margin-top: var(--hc-space-3); font-size: var(--hc-text-sm);">
                    Hébergement web professionnel avec support 24/7.
                </p>
            </div>
            <div>
                <h4 class="hc-footer-title">Services</h4>
                <ul class="hc-footer-list">
                    <li><a href="#hosting">Hébergement web</a></li>
                    <li><a href="#vps">Serveurs VPS</a></li>
                    <li><a href="#dedicated">Serveurs dédiés</a></li>
                </ul>
            </div>
            <div>
                <h4 class="hc-footer-title">Support</h4>
                <ul class="hc-footer-list">
                    <li><a href="#support">Documentation</a></li>
                    <li><a href="#support">Statut des services</a></li>
                    <li><a href="#support">Nous contacter</a></li>
                </ul>
            </div>
            <div>
                <h4 class="hc-footer-title">Légal</h4>
                <ul class="hc-footer-list">
                    <li><a href="#">CGV</a></li>
                    <li><a href="#">Mentions légales</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div class="hc-footer-bottom">
            © {{ date('Y') }} {{ config('hostclient.company_name', 'HostClient') }}. Tous droits réservés.
        </div>
    </div>
</footer>

</body>
</html>
