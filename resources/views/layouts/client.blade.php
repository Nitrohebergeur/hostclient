<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('hostclient.company_name', 'HostClient') }} — @yield('title', 'Espace client')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
</head>
<body>

<div style="display: flex; min-height: 100vh; background: var(--hc-bg);">

    {{-- Sidebar --}}
    <aside style="width: 260px; background: var(--hc-bg-elevated); border-right: 1px solid var(--hc-border); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; flex-shrink: 0;" class="hc-sidebar">
        {{-- Logo --}}
        <div style="padding: var(--hc-space-5) var(--hc-space-6); border-bottom: 1px solid var(--hc-border);">
            <a href="{{ route('client.dashboard') }}" class="hc-brand">
                <div class="hc-brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12 L12 3 L21 12 M5 10 V21 H19 V10"/>
                    </svg>
                </div>
                <span style="font-size: var(--hc-text-base);">{{ config('hostclient.company_name', 'HostClient') }}</span>
            </a>
        </div>

        {{-- Navigation --}}
        <nav style="flex: 1; padding: var(--hc-space-4); overflow-y: auto;">
            <a href="{{ route('client.dashboard') }}" class="hc-nav-link {{ request()->routeIs('client.dashboard') ? 'hc-nav-link-active' : '' }}">
                <i data-lucide="layout-dashboard" style="width: 18px; height: 18px;"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="{{ route('client.services.index') }}" class="hc-nav-link {{ request()->routeIs('client.services.*') ? 'hc-nav-link-active' : '' }}">
                <i data-lucide="server" style="width: 18px; height: 18px;"></i>
                <span>Mes services</span>
            </a>
            <a href="{{ route('client.orders.index') }}" class="hc-nav-link {{ request()->routeIs('client.orders.*') ? 'hc-nav-link-active' : '' }}">
                <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                <span>Commandes</span>
            </a>
            <a href="{{ route('client.invoices.index') }}" class="hc-nav-link {{ request()->routeIs('client.invoices.*') ? 'hc-nav-link-active' : '' }}">
                <i data-lucide="file-text" style="width: 18px; height: 18px;"></i>
                <span>Factures</span>
            </a>
            <a href="{{ route('client.tickets.index') }}" class="hc-nav-link {{ request()->routeIs('client.tickets.*') ? 'hc-nav-link-active' : '' }}">
                <i data-lucide="message-circle" style="width: 18px; height: 18px;"></i>
                <span>Support</span>
            </a>

            <div style="margin-top: var(--hc-space-6); padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <a href="{{ route('store.index') }}" class="hc-nav-link">
                    <i data-lucide="shopping-cart" style="width: 18px; height: 18px;"></i>
                    <span>Boutique</span>
                </a>
                <a href="{{ route('client.api-keys.index') }}" class="hc-nav-link {{ request()->routeIs('client.api-keys.*') ? 'hc-nav-link-active' : '' }}">
                    <i data-lucide="key" style="width: 18px; height: 18px;"></i>
                    <span>Clés API</span>
                </a>
            </div>
        </nav>

        {{-- User block --}}
        <div style="padding: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
            <div style="display: flex; align-items: center; gap: var(--hc-space-3); padding: var(--hc-space-3); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <div style="width: 36px; height: 36px; background: var(--hc-primary); color: var(--hc-text-inverse); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: var(--hc-text-sm); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </div>
                    <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
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
            <div>
                <h1 style="font-size: var(--hc-text-xl); font-weight: 600; margin: 0;">@yield('title', 'Espace client')</h1>
                @hasSection('subtitle')
                    <p style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin: 0;">@yield('subtitle')</p>
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                {{-- Balance pill --}}
                <div style="display: flex; align-items: center; gap: var(--hc-space-2); padding: var(--hc-space-2) var(--hc-space-3); background: var(--hc-gray-50); border-radius: var(--hc-radius-full); font-size: var(--hc-text-sm);">
                    <i data-lucide="wallet" style="width: 16px; height: 16px; color: var(--hc-text-muted);"></i>
                    <span style="font-weight: 600;">{{ number_format(auth()->user()->balance ?? 0, 2) }} €</span>
                </div>

                {{-- User menu --}}
                <div x-data="{ open: false }" style="position: relative;">
                    <button @click="open = !open" style="background: transparent; border: none; padding: var(--hc-space-2); border-radius: var(--hc-radius); cursor: pointer; display: flex; align-items: center; gap: var(--hc-space-2);">
                        <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                        <span style="font-size: var(--hc-text-sm); font-weight: 500;">Mon compte</span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition style="position: absolute; right: 0; top: 100%; margin-top: var(--hc-space-2); background: var(--hc-bg-elevated); border: 1px solid var(--hc-border); border-radius: var(--hc-radius); box-shadow: var(--hc-shadow-lg); min-width: 220px; overflow: hidden;">
                        <a href="{{ route('client.profile.edit') }}" style="display: flex; align-items: center; gap: var(--hc-space-3); padding: var(--hc-space-3) var(--hc-space-4); color: var(--hc-text); text-decoration: none; font-size: var(--hc-text-sm);">
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                            Mon profil
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <div style="border-top: 1px solid var(--hc-border);"></div>
                            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: var(--hc-space-3); padding: var(--hc-space-3) var(--hc-space-4); color: var(--hc-primary); text-decoration: none; font-size: var(--hc-text-sm); font-weight: 500;">
                                <i data-lucide="shield" style="width: 16px; height: 16px;"></i>
                                Administration
                            </a>
                        @endif
                        <div style="border-top: 1px solid var(--hc-border);"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="display: flex; align-items: center; gap: var(--hc-space-3); padding: var(--hc-space-3) var(--hc-space-4); color: var(--hc-danger); background: transparent; border: none; width: 100%; text-align: left; cursor: pointer; font-size: var(--hc-text-sm);">
                                <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error') || session('warning'))
            <div style="padding: var(--hc-space-4) var(--hc-space-8);">
                @if(session('success')) <x-alert type="success">{{ session('success') }}</x-alert> @endif
                @if(session('error')) <x-alert type="danger">{{ session('error') }}</x-alert> @endif
                @if(session('warning')) <x-alert type="warning">{{ session('warning') }}</x-alert> @endif
            </div>
        @endif

        {{-- Page content --}}
        <main style="padding: var(--hc-space-8); flex: 1;">
            @yield('content')
        </main>
    </div>
</div>

@livewireScripts
<script>lucide.createIcons();</script>
<style>
.hc-nav-link {
    display: flex;
    align-items: center;
    gap: var(--hc-space-3);
    padding: var(--hc-space-3);
    color: var(--hc-text-muted);
    text-decoration: none;
    border-radius: var(--hc-radius);
    font-size: var(--hc-text-sm);
    font-weight: 500;
    margin-bottom: 2px;
    transition: background var(--hc-transition), color var(--hc-transition);
}
.hc-nav-link:hover {
    background: var(--hc-gray-50);
    color: var(--hc-text);
}
.hc-nav-link-active {
    background: var(--hc-primary-50);
    color: var(--hc-primary);
}
.hc-nav-link-active:hover {
    background: var(--hc-primary-100);
    color: var(--hc-primary-dark);
}
@media (max-width: 768px) {
    .hc-sidebar { display: none !important; }
}
</style>
</body>
</html>