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
        return <<<'CSS'
/* Reset et Base */
* {
    box-sizing: border-box;
}

.homepage-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
    background: #ffffff;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    transition: background 0.3s ease, color 0.3s ease;
}

/* Top Bar */
.homepage-topbar {
    background: #000000;
    color: #ffffff;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.topbar-left .logo {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Theme Toggle */
.theme-toggle {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

/* Notification Button */
.notification-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.notification-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.notif-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
}

/* Profile Menu */
.profile-menu {
    position: relative;
}

.profile-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.profile-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ffffff;
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.profile-arrow {
    font-size: 0.7rem;
    transition: transform 0.3s ease;
}

.profile-btn:hover .profile-arrow {
    transform: translateY(2px);
}

/* Profile Dropdown */
.profile-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #ffffff;
    border: 2px solid #000000;
    border-radius: 8px;
    min-width: 280px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.profile-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f8f8f8;
    border-bottom: 2px solid #000000;
}

.dropdown-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #000000;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.dropdown-name {
    font-weight: 700;
    color: #000000;
    font-size: 1rem;
}

.dropdown-email {
    font-size: 0.85rem;
    color: #666666;
}

.dropdown-divider {
    height: 1px;
    background: #e0e0e0;
    margin: 0.5rem 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    color: #000000;
    text-decoration: none;
    transition: background 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 0.95rem;
}

.dropdown-item:hover {
    background: #f0f0f0;
}

.item-icon {
    font-size: 1.2rem;
}

.logout-btn {
    color: #ff4444;
    font-weight: 600;
}

/* Main Content Padding */
.homepage-container > *:not(.homepage-topbar) {
    padding-left: 2rem;
    padding-right: 2rem;
}

/* Header */
.homepage-header {
    text-align: center;
    padding: 3rem 2rem 2rem;
    border-bottom: 2px solid #000000;
    margin-bottom: 3rem;
}

.homepage-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #000000;
    margin: 0 0 0.5rem 0;
    letter-spacing: -1px;
}

.homepage-header .subtitle {
    font-size: 1.125rem;
    color: #666666;
    margin: 0 0 0.5rem 0;
}

.header-time {
    font-size: 0.95rem;
    color: #999999;
    font-weight: 500;
}

/* Stats Section */
.homepage-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #000000;
    color: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.stat-card h3 {
    font-size: 0.95rem;
    font-weight: 500;
    margin: 0 0 0.75rem 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #cccccc;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: #ffffff;
}

.stat-footer {
    font-size: 0.85rem;
    color: #aaaaaa;
    margin-top: 1rem;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.stat-card:hover .stat-footer {
    opacity: 1;
    transform: translateY(0);
}

/* Revenue Section */
.revenue-section {
    margin-bottom: 2rem;
}

.revenue-card {
    background: linear-gradient(135deg, #000000 0%, #333333 100%);
    color: #ffffff;
    padding: 2.5rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.revenue-card h2 {
    margin: 0 0 1rem 0;
    font-size: 1.5rem;
}

.revenue-amount {
    font-size: 3.5rem;
    font-weight: 700;
    margin: 0;
    color: #ffffff;
}

.revenue-subtitle {
    font-size: 1rem;
    color: #cccccc;
    margin: 0.5rem 0 0 0;
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
    border-radius: 12px;
}

.content-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000000;
    margin: 0 0 1.5rem 0;
    border-bottom: 2px solid #000000;
    padding-bottom: 0.75rem;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000000;
    color: #ffffff;
    padding: 1rem;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.action-btn:hover {
    background: #333333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Info List */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 1rem 0;
    border-bottom: 1px solid #dddddd;
    color: #333333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-list li:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #000000;
}

.info-value {
    color: #666666;
}

.status-online {
    color: #28a745;
    font-weight: 600;
}

/* Footer */
.homepage-footer {
    text-align: center;
    padding: 2rem;
    border-top: 2px solid #000000;
    margin-top: 3rem;
    background: #f8f8f8;
}

.homepage-footer p {
    color: #666666;
    font-size: 0.875rem;
    margin: 0;
}

/* Dark Theme */
.homepage-container.dark-theme {
    background: #1a1a1a;
    color: #ffffff;
}

.dark-theme .homepage-topbar {
    background: #000000;
    border-bottom: 2px solid #333333;
}

.dark-theme .homepage-header {
    border-bottom-color: #333333;
}

.dark-theme .homepage-header h1 {
    color: #ffffff;
}

.dark-theme .homepage-header .subtitle {
    color: #cccccc;
}

.dark-theme .stat-card {
    background: #2d2d2d;
    border: 2px solid #404040;
}

.dark-theme .revenue-card {
    background: linear-gradient(135deg, #2d2d2d 0%, #404040 100%);
}

.dark-theme .content-card {
    background: #2d2d2d;
    border-color: #404040;
}

.dark-theme .content-card h2 {
    color: #ffffff;
    border-bottom-color: #404040;
}

.dark-theme .action-btn {
    background: #404040;
    color: #ffffff;
}

.dark-theme .action-btn:hover {
    background: #505050;
}

.dark-theme .info-list li {
    border-bottom-color: #404040;
    color: #cccccc;
}

.dark-theme .info-label {
    color: #ffffff;
}

.dark-theme .info-value {
    color: #cccccc;
}

.dark-theme .homepage-footer {
    background: #2d2d2d;
    border-top-color: #404040;
}

.dark-theme .homepage-footer p {
    color: #cccccc;
}

.dark-theme .profile-dropdown {
    background: #2d2d2d;
    border-color: #404040;
}

.dark-theme .dropdown-header {
    background: #252525;
    border-bottom-color: #404040;
}

.dark-theme .dropdown-name {
    color: #ffffff;
}

.dark-theme .dropdown-item {
    color: #ffffff;
}

.dark-theme .dropdown-item:hover {
    background: #404040;
}

.dark-theme .dropdown-divider {
    background: #404040;
}

/* Responsive */
@media (max-width: 768px) {
    .homepage-container > *:not(.homepage-topbar) {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .homepage-topbar {
        padding: 1rem;
    }
    
    .profile-name {
        display: none;
    }
    
    .homepage-header h1 {
        font-size: 1.75rem;
    }
    
    .homepage-stats {
        grid-template-columns: 1fr;
    }
    
    .homepage-content {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .revenue-amount {
        font-size: 2.5rem;
    }
}
CSS;
    }
}

