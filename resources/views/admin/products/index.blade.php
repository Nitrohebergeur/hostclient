@extends('layouts.admin')
@section('title', 'Produits')
@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Produits & Offres</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez votre catalogue de services</p>
        </div>
        <a href="/admin/products/create" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouveau produit
        </a>
    </div>

    <!-- Category Tabs -->
    <div x-data="{ cat: 'all' }" class="space-y-4">
        <div class="flex gap-2 flex-wrap">
            @foreach(['all' => 'Tous (24)', 'hosting' => 'Hébergement (8)', 'vps' => 'VPS (5)', 'dedicated' => 'Dédié (3)', 'game' => 'Jeux (4)', 'domain' => 'Domaines (4)'] as $k => $l)
            <button @click="cat = '{{ $k }}'"
                :class="cat === '{{ $k }}' ? 'bg-primary-600 text-white shadow' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                {{ $l }}
            </button>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach([
                ['Hébergement Starter',   'hosting', 'primary',   '4,99 €',  '20 Go SSD · 1 site · SSL gratuit',           true,  145],
                ['Hébergement Pro',       'hosting', 'primary',   '9,99 €',  '50 Go SSD · 5 sites · SSL · Backups',        true,  98],
                ['Hébergement Premium',   'hosting', 'primary',   '19,99 €', '100 Go SSD · Illimité · SSL · Backups · CDN', true,  67],
                ['VPS Cloud Starter',     'vps',     'secondary', '14,99 €', '1 vCPU · 2 Go RAM · 40 Go NVMe',             true,  54],
                ['VPS Cloud Standard',    'vps',     'secondary', '29,99 €', '2 vCPU · 4 Go RAM · 80 Go NVMe',             true,  38],
                ['VPS Cloud Pro',         'vps',     'secondary', '59,99 €', '4 vCPU · 8 Go RAM · 160 Go NVMe',            true,  22],
                ['Minecraft Standard',    'game',    'success',   '9,99 €',  '4 Go RAM · 20 joueurs · Plugins',            true,  31],
                ['Minecraft Premium',     'game',    'success',   '19,99 €', '8 Go RAM · 50 joueurs · Plugins · Backup',   true,  18],
                ['Domaine .fr',           'domain',  'warning',   '7,99 €',  'Renouvellement annuel · DNS inclus',         true,  12],
            ] as [$name, $type, $color, $price, $specs, $active, $sold])
            <div class="card hover:shadow-md transition-shadow group">
                <div class="card-body">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                @if($type === 'hosting')
                                    <svg class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                @elseif($type === 'vps')
                                    <svg class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                                @elseif($type === 'game')
                                    <svg class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $name }}</h4>
                                <p class="text-xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $price }}<span class="text-xs font-normal text-gray-500 dark:text-gray-400">/mois</span></p>
                            </div>
                        </div>
                        <div x-data="{ on: {{ $active ? 'true' : 'false' }} }" @click="on = !on" class="relative cursor-pointer flex-shrink-0">
                            <div :class="on ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'" class="w-9 h-5 rounded-full transition-colors"></div>
                            <div :class="on ? 'translate-x-4' : 'translate-x-0.5'" class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"></div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">{{ $specs }}</p>

                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                        <span>{{ $sold }} services vendus</span>
                        <span>Module: {{ $type === 'hosting' ? 'cPanel' : ($type === 'vps' ? 'Proxmox' : ($type === 'game' ? 'Pterodactyl' : 'WHMCS')) }}</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="/admin/products/1/edit" class="btn btn-secondary btn-sm flex-1">Modifier</a>
                        <button class="btn btn-ghost btn-sm text-danger-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
