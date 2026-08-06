<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class HomePageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultHtml = <<<'HTML'
<div class="homepage-container">
    <header class="homepage-header">
        <h1>Bienvenue sur votre Panel Admin</h1>
        <p class="subtitle">Gérez votre plateforme en toute simplicité</p>
    </header>

    <section class="homepage-stats">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <h3>Clients</h3>
            <p class="stat-number">{{ $stats['total_clients'] ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🚀</div>
            <h3>Services Actifs</h3>
            <p class="stat-number">{{ $stats['active_services'] ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <h3>Commandes en attente</h3>
            <p class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎫</div>
            <h3>Tickets Ouverts</h3>
            <p class="stat-number">{{ $stats['open_tickets'] ?? 0 }}</p>
        </div>
    </section>

    <section class="homepage-content">
        <div class="content-card">
            <h2>Actions Rapides</h2>
            <div class="quick-actions">
                <a href="/admin/clients" class="action-btn">Gérer les Clients</a>
                <a href="/admin/services" class="action-btn">Voir les Services</a>
                <a href="/admin/orders" class="action-btn">Commandes</a>
                <a href="/admin/tickets" class="action-btn">Support</a>
            </div>
        </div>

        <div class="content-card">
            <h2>Informations Système</h2>
            <p>Personnalisez cette section avec vos propres informations.</p>
            <ul class="info-list">
                <li>Version: 1.0.0</li>
                <li>Statut: En ligne</li>
                <li>Dernière mise à jour: Aujourd'hui</li>
            </ul>
        </div>
    </section>

    <footer class="homepage-footer">
        <p>Personnalisez cette page dans Paramètres > Page d'Accueil</p>
    </footer>
</div>
HTML;

        $defaultCss = <<<'CSS'
/* Reset et Base */
.homepage-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    background: #ffffff;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Header */
.homepage-header {
    text-align: center;
    padding: 3rem 0;
    border-bottom: 2px solid #000000;
    margin-bottom: 3rem;
}

.homepage-header h1 {
    font-size: 3rem;
    font-weight: 700;
    color: #000000;
    margin: 0 0 1rem 0;
    letter-spacing: -1px;
}

.homepage-header .subtitle {
    font-size: 1.25rem;
    color: #666666;
    margin: 0;
}

/* Stats Section */
.homepage-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: #000000;
    color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.stat-card h3 {
    font-size: 1rem;
    font-weight: 500;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #cccccc;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: #ffffff;
}

/* Content Section */
.homepage-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.content-card {
    background: #f8f8f8;
    border: 2px solid #000000;
    padding: 2rem;
    border-radius: 8px;
}

.content-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000000;
    margin: 0 0 1.5rem 0;
    border-bottom: 2px solid #000000;
    padding-bottom: 0.5rem;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    gap: 1rem;
}

.action-btn {
    display: block;
    background: #000000;
    color: #ffffff;
    padding: 1rem;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    border-radius: 4px;
    transition: background 0.2s, transform 0.2s;
}

.action-btn:hover {
    background: #333333;
    transform: translateX(4px);
}

/* Info List */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #dddddd;
    color: #333333;
}

.info-list li:last-child {
    border-bottom: none;
}

/* Footer */
.homepage-footer {
    text-align: center;
    padding: 2rem 0;
    border-top: 2px solid #000000;
    margin-top: 3rem;
}

.homepage-footer p {
    color: #666666;
    font-size: 0.875rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .homepage-container {
        padding: 1rem;
    }
    
    .homepage-header h1 {
        font-size: 2rem;
    }
    
    .homepage-stats {
        grid-template-columns: 1fr;
    }
    
    .homepage-content {
        grid-template-columns: 1fr;
    }
}
CSS;

        Setting::set('homepage_html', $defaultHtml, 'string', 'homepage');
        Setting::set('homepage_css', $defaultCss, 'string', 'homepage');

        $this->command->info('Homepage settings created successfully!');
    }
}
