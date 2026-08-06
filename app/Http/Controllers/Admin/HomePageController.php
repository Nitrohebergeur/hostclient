<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function edit()
    {
        $html = Setting::get('homepage_html', $this->getDefaultHtml());
        $css = Setting::get('homepage_css', $this->getDefaultCss());

        return view('admin.homepage.edit', compact('html', 'css'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'html' => 'required|string',
            'css' => 'required|string',
        ]);

        Setting::set('homepage_html', $validated['html'], 'string', 'homepage');
        Setting::set('homepage_css', $validated['css'], 'string', 'homepage');

        return back()->with('success', 'Page d\'accueil mise à jour avec succès.');
    }

    public function preview()
    {
        $html = Setting::get('homepage_html', $this->getDefaultHtml());
        $css = Setting::get('homepage_css', $this->getDefaultCss());

        return view('admin.homepage.preview', compact('html', 'css'));
    }

    protected function getDefaultHtml(): string
    {
        return <<<'HTML'
<div class="homepage-container" id="homepageContainer">
    <!-- Top Bar avec profil et paramètres -->
    <div class="homepage-topbar">
        <div class="topbar-left">
            <h2 class="logo">🏢 Admin Panel</h2>
        </div>
        <div class="topbar-right">
            <!-- Theme Toggle -->
            <button class="theme-toggle" id="themeToggle" title="Changer le thème">
                <span class="theme-icon">🌙</span>
            </button>
            
            <!-- Notifications -->
            <button class="notification-btn" title="Notifications">
                <span class="notif-icon">🔔</span>
                <span class="notif-badge">{{ $stats['open_tickets'] ?? 0 }}</span>
            </button>
            
            <!-- Profile Menu -->
            <div class="profile-menu">
                <button class="profile-btn" id="profileBtn">
                    <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="profile-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="profile-arrow">▼</span>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                        <div>
                            <div class="dropdown-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="dropdown-email">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="/admin/profile" class="dropdown-item">
                        <span class="item-icon">👤</span>
                        Mon Profil
                    </a>
                    <a href="/admin/settings" class="dropdown-item">
                        <span class="item-icon">⚙️</span>
                        Paramètres
                    </a>
                    <a href="/admin/homepage/edit" class="dropdown-item">
                        <span class="item-icon">🎨</span>
                        Personnaliser la page
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="/logout" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item logout-btn">
                            <span class="item-icon">🚪</span>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <header class="homepage-header">
        <h1>Bienvenue, {{ auth()->user()->name ?? 'Admin' }}! 👋</h1>
        <p class="subtitle">Voici un aperçu de votre plateforme</p>
        <div class="header-time">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY, HH:mm') }}</div>
    </header>

    <!-- Stats Cards avec animations -->
    <section class="homepage-stats">
        <div class="stat-card" onclick="window.location.href='/admin/clients'">
            <div class="stat-icon">👥</div>
            <h3>Clients</h3>
            <p class="stat-number">{{ $stats['total_clients'] ?? 0 }}</p>
            <div class="stat-footer">Voir tous les clients →</div>
        </div>
        <div class="stat-card" onclick="window.location.href='/admin/services'">
            <div class="stat-icon">🚀</div>
            <h3>Services Actifs</h3>
            <p class="stat-number">{{ $stats['active_services'] ?? 0 }}</p>
            <div class="stat-footer">Gérer les services →</div>
        </div>
        <div class="stat-card" onclick="window.location.href='/admin/orders'">
            <div class="stat-icon">📋</div>
            <h3>Commandes en attente</h3>
            <p class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</p>
            <div class="stat-footer">Voir les commandes →</div>
        </div>
        <div class="stat-card" onclick="window.location.href='/admin/tickets'">
            <div class="stat-icon">🎫</div>
            <h3>Tickets Ouverts</h3>
            <p class="stat-number">{{ $stats['open_tickets'] ?? 0 }}</p>
            <div class="stat-footer">Support client →</div>
        </div>
    </section>

    <!-- Revenue Card -->
    <section class="revenue-section">
        <div class="revenue-card">
            <h2>💰 Revenu Mensuel</h2>
            <p class="revenue-amount">{{ number_format($stats['monthly_revenue'] ?? 0, 2) }} €</p>
            <p class="revenue-subtitle">Ce mois-ci</p>
        </div>
    </section>

    <!-- Actions et Informations -->
    <section class="homepage-content">
        <div class="content-card">
            <h2>⚡ Actions Rapides</h2>
            <div class="quick-actions">
                <a href="/admin/clients/create" class="action-btn">➕ Nouveau Client</a>
                <a href="/admin/orders" class="action-btn">📦 Gérer Commandes</a>
                <a href="/admin/invoices" class="action-btn">💳 Factures</a>
                <a href="/admin/tickets" class="action-btn">🎫 Support</a>
                <a href="/admin/settings" class="action-btn">⚙️ Paramètres</a>
                <a href="/admin/users" class="action-btn">👥 Utilisateurs</a>
            </div>
        </div>

        <div class="content-card">
            <h2>📊 Informations Système</h2>
            <ul class="info-list">
                <li>
                    <span class="info-label">Version:</span>
                    <span class="info-value">1.0.0</span>
                </li>
                <li>
                    <span class="info-label">Statut:</span>
                    <span class="info-value status-online">🟢 En ligne</span>
                </li>
                <li>
                    <span class="info-label">Factures impayées:</span>
                    <span class="info-value">{{ $stats['unpaid_invoices'] ?? 0 }}</span>
                </li>
                <li>
                    <span class="info-label">Inscriptions aujourd'hui:</span>
                    <span class="info-value">{{ $stats['today_signups'] ?? 0 }}</span>
                </li>
            </ul>
        </div>
    </section>

    <footer class="homepage-footer">
        <p>💡 Astuce: Cliquez sur "Personnaliser la page" dans votre profil pour modifier cette page</p>
    </footer>
</div>

<script>
// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const container = document.getElementById('homepageContainer');
const themeIcon = themeToggle.querySelector('.theme-icon');

// Charger le thème sauvegardé
const savedTheme = localStorage.getItem('adminTheme') || 'light';
if (savedTheme === 'dark') {
    container.classList.add('dark-theme');
    themeIcon.textContent = '☀️';
}

themeToggle.addEventListener('click', () => {
    container.classList.toggle('dark-theme');
    const isDark = container.classList.contains('dark-theme');
    themeIcon.textContent = isDark ? '☀️' : '🌙';
    localStorage.setItem('adminTheme', isDark ? 'dark' : 'light');
});

// Profile Dropdown
const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

profileBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    profileDropdown.classList.toggle('show');
});

document.addEventListener('click', () => {
    profileDropdown.classList.remove('show');
});

profileDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
});
</script>
HTML;
    }

    protected function getDefaultCss(): string
    {
        return file_get_contents(__DIR__ . '/homepage_default.css');
    }
}
