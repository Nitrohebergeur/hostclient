<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('hostclient.company_name', 'HostClient') }} Admin — @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
    @stack('styles')
</head>
<body>

<div style="display: flex; min-height: 100vh; background: var(--hc-bg);">

    {{-- Sidebar --}}
    <aside style="width: 260px; background: var(--hc-gray-900); color: var(--hc-gray-300); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; flex-shrink: 0;" class="hc-sidebar">

        {{-- Logo --}}
        <div style="padding: var(--hc-space-5) var(--hc-space-6); border-bottom: 1px solid var(--hc-gray-800);">
            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: var(--hc-space-3); text-decoration: none; color: var(--hc-text-inverse); font-weight: 700; font-size: var(--hc-text-base);">
                <div style="width: 36px; height: 36px; background: var(--hc-primary); border-radius: var(--hc-radius); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="shield" style="width: 20px; height: 20px; color: white;"></i>
                </div>
                <span>{{ config('hostclient.company_name', 'HostClient') }}<br><span style="font-size: var(--hc-text-xs); font-weight: 500; color: var(--hc-gray-400);">Console admin</span></span>
            </a>
        </div>

        {{-- Nav --}}
        <nav style="flex: 1; padding: var(--hc-space-4); overflow-y: auto;">
            @php
                $navGroups = [
                    ['label' => null, 'items' => [
                        ['route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Tableau de bord'],
                    ]],
                    ['label' => 'Clients & Ventes', 'items' => [
                        ['route' => 'admin.clients.index', 'icon' => 'users', 'label' => 'Clients'],
                        ['route' => 'admin.orders.index', 'icon' => 'shopping-bag', 'label' => 'Commandes'],
                        ['route' => 'admin.invoices.index', 'icon' => 'file-text', 'label' => 'Factures'],
                        ['route' => 'admin.transactions.index', 'icon' => 'credit-card', 'label' => 'Transactions'],
                    ]],
                    ['label' => 'Catalogue', 'items' => [
                        ['route' => 'admin.products.index', 'icon' => 'package', 'label' => 'Produits'],
                        ['route' => 'admin.categories.index', 'icon' => 'folder', 'label' => 'Catégories'],
                        ['route' => 'admin.services.index', 'icon' => 'server', 'label' => 'Services'],
                        ['route' => 'admin.coupons.index', 'icon' => 'ticket', 'label' => 'Coupons'],
                    ]],
                    ['label' => 'Support', 'items' => [
                        ['route' => 'admin.tickets.index', 'icon' => 'message-circle', 'label' => 'Tickets'],
                    ]],
                    ['label' => 'Configuration', 'items' => [
                        ['route' => 'admin.payment-gateways.index', 'icon' => 'wallet', 'label' => 'Paiement'],
                        ['route' => 'admin.modules.index', 'icon' => 'puzzle', 'label' => 'Modules'],
                        ['route' => 'admin.settings.index', 'icon' => 'settings', 'label' => 'Paramètres'],
                        ['route' => 'admin.users.index', 'icon' => 'user-cog', 'label' => 'Utilisateurs'],
                        ['route' => 'admin.roles.index', 'icon' => 'key', 'label' => 'Rôles & permissions'],
                        ['route' => 'admin.activity.index', 'icon' => 'activity', 'label' => 'Journal d\'activité'],
                        ['route' => 'admin.homepage.edit', 'icon' => 'layout', 'label' => 'Page d\'accueil'],
                    ]],
                ];
            @endphp

            @foreach($navGroups as $group)
                @if($group['label'])
                    <div style="padding: var(--hc-space-3) var(--hc-space-3) var(--hc-space-2); font-size: var(--hc-text-xs); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--hc-gray-500);">
                        {{ $group['label'] }}
                    </div>
                @endif
                @foreach($group['items'] as $item)
                    @php
                        $isActive = request()->routeIs(
                            $item['route'],
                            str_replace('.index', '.*', $item['route'])
                        );
                    @endphp
                    <a href="{{ route($item['route']) }}" class="hc-nav-link-admin {{ $isActive ? 'hc-nav-link-admin-active' : '' }}">
                        <i data-lucide="{{ $item['icon'] }}" style="width: 18px; height: 18px;"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        {{-- User --}}
        <div style="padding: var(--hc-space-4); border-top: 1px solid var(--hc-gray-800);">
            <div style="display: flex; align-items: center; gap: var(--hc-space-3); padding: var(--hc-space-3); background: var(--hc-gray-800); border-radius: var(--hc-radius);">
                <div style="width: 36px; height: 36px; background: var(--hc-primary); color: var(--hc-text-inverse); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: var(--hc-text-sm); font-weight: 600; color: var(--hc-text-inverse); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </div>
                    <div style="font-size: var(--hc-text-xs); color: var(--hc-gray-400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->email }}
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div style="flex: 1; display: flex; flex-direction: column; min-width: 0;">

        {{-- Topbar --}}
        <header style="background: var(--hc-bg-elevated); border-bottom: 1px solid var(--hc-border); padding: var(--hc-space-4) var(--hc-space-8); display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;">
            <h1 style="font-size: var(--hc-text-xl); font-weight: 600; margin: 0;">@yield('title', 'Tableau de bord')</h1>

            <div style="display: flex; align-items: center; gap: var(--hc-space-2);">
                @if(Route::has('client.dashboard'))
                    <a href="{{ route('client.dashboard') }}" class="hc-btn hc-btn-ghost hc-btn-sm" title="Voir en tant que client">
                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                        Vue client
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="hc-btn hc-btn-ghost hc-btn-sm">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success') || session('error') || session('warning'))
            <div style="padding: var(--hc-space-4) var(--hc-space-8);">
                @if(session('success')) <x-alert type="success">{{ session('success') }}</x-alert> @endif
                @if(session('error')) <x-alert type="danger">{{ session('error') }}</x-alert> @endif
                @if(session('warning')) <x-alert type="warning">{{ session('warning') }}</x-alert> @endif
            </div>
        @endif

        {{-- Content --}}
        <main style="padding: var(--hc-space-8); flex: 1;">
            @yield('content')
        </main>
    </div>
</div>

@livewireScripts
<script>lucide.createIcons();</script>
<style>
.hc-nav-link-admin {
    display: flex;
    align-items: center;
    gap: var(--hc-space-3);
    padding: var(--hc-space-3);
    color: var(--hc-gray-300);
    text-decoration: none;
    border-radius: var(--hc-radius);
    font-size: var(--hc-text-sm);
    font-weight: 500;
    margin-bottom: 2px;
    transition: background var(--hc-transition), color var(--hc-transition);
}
.hc-nav-link-admin:hover {
    background: var(--hc-gray-800);
    color: var(--hc-text-inverse);
}
.hc-nav-link-admin-active {
    background: var(--hc-primary);
    color: var(--hc-text-inverse);
}
.hc-nav-link-admin-active:hover {
    background: var(--hc-primary-dark);
}
@media (max-width: 1024px) {
    .hc-sidebar { display: none !important; }
}
</style>
@stack('scripts')
</body>
</html>