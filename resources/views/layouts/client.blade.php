<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('hostclient.company_name', 'HostClient') }} — @yield('title', 'Espace client')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
</head>
<body style="background: #f4f5f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; min-height: 100vh; margin: 0;">

{{-- ═══════════════════ TOP HEADER ═══════════════════ --}}
<header style="background: #fff; border-bottom: 1px solid #e2e6ea; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <div style="display: flex; align-items: center; justify-content: space-between; height: 56px; padding: 0 24px;">

        {{-- Logo --}}
        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: #1a1f36; font-weight: 700; font-size: 15px; white-space: nowrap; flex-shrink: 0;">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #0066ff, #6366f1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" style="width: 18px; height: 18px;">
                    <path d="M3 12L12 3L21 12M5 10V21H19V10"/>
                </svg>
            </div>
            {{ config('hostclient.company_name', 'HostClient') }}
        </a>

        {{-- Top nav links --}}
        <nav style="display: flex; align-items: center; gap: 4px; flex: 1; margin: 0 32px; overflow-x: auto;">
            <a href="{{ route('store.index') }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; color: #5a6475; text-decoration: none; white-space: nowrap; {{ request()->routeIs('store.*') ? 'background:#eff5ff; color:#0066ff;' : '' }}"
               onmouseover="if(!this.style.background.includes('eff5ff')) this.style.background='#f3f4f6'" onmouseout="if(!this.style.background.includes('eff5ff')) this.style.background='transparent'">
                <i data-lucide="shopping-cart" style="width: 14px; height: 14px;"></i>
                Boutique
            </a>
        </nav>

        {{-- Right actions --}}
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            {{-- Cart --}}
            <a href="{{ route('store.cart') }}" style="position: relative; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; color: #6b7280; border-radius: 8px; text-decoration: none;" title="Panier">
                <i data-lucide="shopping-cart" style="width: 17px; height: 17px;"></i>
            </a>

            {{-- Dark mode --}}
            <button style="width: 34px; height: 34px; border: none; background: transparent; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                <i data-lucide="moon" style="width: 17px; height: 17px;"></i>
            </button>

            {{-- Flag --}}
            <div style="font-size: 18px; cursor: pointer; padding: 2px;">🇫🇷</div>

            {{-- User avatar + dropdown --}}
            <div x-data="{ open: false }" style="position: relative;">
                <button @click="open = !open"
                    style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 50%; cursor: pointer; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center; letter-spacing: 0.5px;">
                    {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                    style="position: absolute; right: 0; top: calc(100% + 8px); background: #fff; border: 1px solid #e2e6ea; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); min-width: 240px; overflow: hidden; z-index: 200;">

                    <div style="padding: 14px 16px 10px; background: #f8f9fa; border-bottom: 1px solid #e2e6ea;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Connecté en tant que</div>
                        <div style="font-weight: 700; font-size: 14px; color: #1a1f36;">{{ auth()->user()->email }}</div>
                    </div>

                    <div style="padding: 6px;">
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; background: #eff5ff; color: #0066ff; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 2px;">
                                <i data-lucide="shield" style="width: 15px; height: 15px;"></i>
                                Administration
                            </a>
                        @endif
                        <a href="{{ route('client.profile.edit') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #374151; text-decoration: none; font-size: 13px; font-weight: 500; margin-bottom: 2px;"
                            onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                            <i data-lucide="user" style="width: 15px; height: 15px; color: #6b7280;"></i>
                            Mon profil
                        </a>
                        <a href="{{ route('store.index') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #374151; text-decoration: none; font-size: 13px; font-weight: 500; margin-bottom: 2px;"
                            onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                            <i data-lucide="store" style="width: 15px; height: 15px; color: #6b7280;"></i>
                            Retour à la boutique
                        </a>
                        <div style="height: 1px; background: #e2e6ea; margin: 4px 0;"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: #ef4444; background: transparent; border: none; width: 100%; text-align: left; cursor: pointer; font-size: 13px; font-weight: 500;"
                                onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <i data-lucide="log-out" style="width: 15px; height: 15px;"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ CLIENT NAV TABS ═══ --}}
    <nav style="display: flex; align-items: center; gap: 2px; padding: 0 24px; height: 44px; border-top: 1px solid #f0f0f0; overflow-x: auto;">
        @php
            $clientNav = [
                ['route' => 'client.dashboard',       'icon' => 'layout-dashboard', 'label' => 'Espace client',        'match' => 'client.dashboard'],
                ['route' => 'client.services.index',  'icon' => 'server',           'label' => 'Mes services',         'match' => 'client.services.*'],
                ['route' => 'client.invoices.index',  'icon' => 'file-text',        'label' => 'Mes factures',         'match' => 'client.invoices.*'],
                ['route' => 'client.orders.index',    'icon' => 'package',          'label' => 'Mes commandes',        'match' => 'client.orders.*'],
                ['route' => 'client.tickets.index',   'icon' => 'message-circle',   'label' => 'Centre d\'aide',       'match' => 'client.tickets.*'],
                ['route' => 'client.profile.edit',    'icon' => 'user',             'label' => 'Mon profil',           'match' => 'client.profile.*'],
                ['route' => 'client.api-keys.index',  'icon' => 'key',              'label' => 'Mes moyens de paiement','match' => 'client.api-keys.*'],
            ];
        @endphp

        @foreach($clientNav as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
                style="display: inline-flex; align-items: center; gap: 6px; padding: 0 12px; height: 44px; font-size: 13px; font-weight: {{ $active ? '600' : '500' }}; color: {{ $active ? '#0066ff' : '#5a6475' }}; text-decoration: none; border-bottom: 2px solid {{ $active ? '#0066ff' : 'transparent' }}; white-space: nowrap; transition: color 0.15s;"
                onmouseover="if (!{{ $active ? 'true' : 'false' }}) { this.style.color='#1a1f36'; }"
                onmouseout="if (!{{ $active ? 'true' : 'false' }}) { this.style.color='#5a6475'; }">
                <i data-lucide="{{ $item['icon'] }}" style="width: 14px; height: 14px;"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</header>

{{-- ═══════════════════ PAGE CONTENT ═══════════════════ --}}
<div style="max-width: 1400px; margin: 0 auto; padding: 24px;">

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('warning'))
        <div style="margin-bottom: 16px;">
            @if(session('success'))
                <div style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: 13px; margin-bottom: 8px;">
                    <i data-lucide="check-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; font-size: 13px; margin-bottom: 8px;">
                    <i data-lucide="alert-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; color: #92400e; font-size: 13px; margin-bottom: 8px;">
                    <i data-lucide="alert-triangle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                    {{ session('warning') }}
                </div>
            @endif
        </div>
    @endif

    @yield('content')
</div>

@livewireScripts
<script>lucide.createIcons();</script>

<style>
/* ClientXMS Client styles */
.ctx-stat { background: #fff; border: 1px solid #e2e6ea; border-radius: 10px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; transition: box-shadow 0.2s; }
.ctx-stat:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.ctx-stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; margin-bottom: 4px; }
.ctx-stat-value { font-size: 28px; font-weight: 700; color: #1a1f36; line-height: 1; }
.ctx-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.ctx-card { background: #fff; border: 1px solid #e2e6ea; border-radius: 10px; overflow: hidden; }
.ctx-card-header { padding: 14px 20px; border-bottom: 1px solid #f0f0f0; font-size: 14px; font-weight: 600; color: #1a1f36; display: flex; align-items: center; justify-content: space-between; }
.ctx-card-body { padding: 20px; }

.ctx-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.ctx-table thead { background: #f8f9fa; }
.ctx-table th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e2e6ea; white-space: nowrap; }
.ctx-table td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; color: #374151; vertical-align: middle; }
.ctx-table tbody tr:last-child td { border-bottom: none; }
.ctx-table tbody tr:hover { background: #f8f9fa; }

.ctx-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.ctx-badge-success { background: #d1fae5; color: #065f46; }
.ctx-badge-danger  { background: #fee2e2; color: #991b1b; }
.ctx-badge-warning { background: #fef3c7; color: #92400e; }
.ctx-badge-info    { background: #dbeafe; color: #1e40af; }
.ctx-badge-neutral { background: #f3f4f6; color: #6b7280; }

.ctx-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; border: 1px solid transparent; transition: all 0.15s; white-space: nowrap; }
.ctx-btn-primary { background: #0066ff; color: #fff; border-color: #0066ff; }
.ctx-btn-primary:hover { background: #0052cc; border-color: #0052cc; }
.ctx-btn-secondary { background: #fff; color: #374151; border-color: #d1d5db; }
.ctx-btn-secondary:hover { background: #f9fafb; }
.ctx-btn-ghost { background: transparent; color: #6b7280; border-color: transparent; }
.ctx-btn-ghost:hover { background: #f3f4f6; color: #374151; }
.ctx-btn-sm { padding: 5px 10px; font-size: 12px; }
.ctx-btn-green { background: #10b981; color: #fff; border-color: #10b981; }
.ctx-btn-green:hover { background: #059669; }

.ctx-input, .ctx-select, .ctx-textarea { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; background: #fff; color: #1a1f36; outline: none; box-sizing: border-box; transition: border-color 0.15s; }
.ctx-input:focus, .ctx-select:focus, .ctx-textarea:focus { border-color: #0066ff; box-shadow: 0 0 0 3px rgba(0,102,255,0.1); }
.ctx-label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; }

.ctx-page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
.ctx-page-title { font-size: 16px; font-weight: 700; color: #1a1f36; margin: 0 0 2px 0; }
.ctx-page-subtitle { font-size: 13px; color: #6b7280; margin: 0; }
.ctx-page-actions { display: flex; gap: 8px; }

.ctx-filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; padding: 16px 20px; }

.ctx-avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0; }

.ctx-empty { padding: 48px 24px; text-align: center; }
.ctx-empty-icon { font-size: 40px; margin-bottom: 12px; opacity: 0.6; }
.ctx-empty h3 { font-size: 16px; font-weight: 600; color: #1a1f36; margin: 0 0 6px; }
.ctx-empty p { font-size: 13px; color: #6b7280; margin: 0 0 16px; }

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #f1f5f9; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>
</body>
</html>
