@extends('layouts.admin')
@section('title', 'Services')
@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Services</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Vue d'ensemble de tous les services clients</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exporter
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        @foreach([
            ['324','Actifs','success'],
            ['12','En attente','warning'],
            ['58','Suspendus','danger'],
            ['8','Résiliés','gray'],
            ['402','Total','primary'],
        ] as [$count, $label, $color])
        <div class="card">
            <div class="card-body py-4 text-center">
                <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $count }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" placeholder="Rechercher client, domaine, IP…" class="form-input flex-1 text-sm">
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous statuts</option>
                    <option>Actif</option>
                    <option>En attente</option>
                    <option>Suspendu</option>
                    <option>Résilié</option>
                </select>
                <select class="form-input w-full sm:w-44 text-sm">
                    <option>Tous types</option>
                    <option>Hébergement Web</option>
                    <option>VPS</option>
                    <option>Dédié</option>
                    <option>Serveur de Jeux</option>
                    <option>Domaine</option>
                </select>
                <select class="form-input w-full sm:w-40 text-sm">
                    <option>Tous modules</option>
                    <option>cPanel</option>
                    <option>Pterodactyl</option>
                    <option>Proxmox</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="w-4 h-4 rounded"></th>
                        <th>Client</th>
                        <th>Service / Produit</th>
                        <th>Identifiant</th>
                        <th>Module</th>
                        <th>Prix</th>
                        <th>Renouvellement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['Jean Dupont',   'Hébergement Premium', 'monsite.com',      'cPanel',       '19,99 €/mois', '15/03/2024', 'active'],
                        ['Marie Martin',  'VPS Cloud Standard',  '192.168.1.10',     'Proxmox',      '29,99 €/mois', '20/03/2024', 'active'],
                        ['Paul Robert',   'Minecraft — Survie',  'play.server.com',  'Pterodactyl',  '9,99 €/mois',  '01/03/2024', 'suspended'],
                        ['Sophie Laurent','Domaine .com',        'sophie-site.com',  'WHMCS',        '12,99 €/an',   '10/06/2024', 'active'],
                        ['Luc Bernard',   'Hébergement Starter', 'luc-dev.fr',       'cPanel',       '4,99 €/mois',  '05/03/2024', 'pending'],
                        ['Emma Petit',    'VPS Cloud Pro',       '10.0.0.5',         'Proxmox',      '59,99 €/mois', '25/03/2024', 'active'],
                    ] as [$client, $product, $ident, $module, $price, $renew, $status])
                    <tr>
                        <td><input type="checkbox" class="w-4 h-4 rounded"></td>
                        <td>
                            <a href="/admin/users/1" class="font-medium text-primary-600 dark:text-primary-400 text-sm hover:underline">{{ $client }}</a>
                        </td>
                        <td class="text-sm text-gray-900 dark:text-white font-medium">{{ $product }}</td>
                        <td class="font-mono text-xs text-gray-600 dark:text-gray-400">{{ $ident }}</td>
                        <td><span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $module }}</span></td>
                        <td class="font-semibold text-sm text-gray-900 dark:text-white">{{ $price }}</td>
                        <td class="text-sm {{ $status === 'active' && strtotime($renew) < strtotime('+7 days') ? 'text-warning-600 dark:text-warning-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">{{ $renew }}</td>
                        <td>
                            @if($status === 'active')    <span class="badge badge-success">Actif</span>
                            @elseif($status === 'pending') <span class="badge badge-warning">En attente</span>
                            @elseif($status === 'suspended') <span class="badge badge-danger">Suspendu</span>
                            @else <span class="badge">Résilié</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="/admin/services/1" class="btn btn-ghost btn-sm" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <div x-data="dropdown" class="relative">
                                    <button @click="toggle" class="btn btn-ghost btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="close" x-transition class="absolute right-0 z-10 mt-1 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Gérer le panneau</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Forcer renouvellement</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Changer offre</a>
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        @if($status === 'active')
                                            <a href="#" class="block px-4 py-2 text-sm text-warning-600 hover:bg-gray-100 dark:hover:bg-gray-700">Suspendre</a>
                                        @else
                                            <a href="#" class="block px-4 py-2 text-sm text-success-600 hover:bg-gray-100 dark:hover:bg-gray-700">Activer</a>
                                        @endif
                                        <a href="#" class="block px-4 py-2 text-sm text-danger-600 hover:bg-gray-100 dark:hover:bg-gray-700">Résilier</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">Affichage 1–6 sur 402 services</p>
            <div class="flex items-center gap-1">
                <button class="btn btn-secondary btn-sm" disabled>←</button>
                <span class="px-3 py-1.5 bg-primary-50 dark:bg-primary-900/20 text-primary-600 rounded text-sm font-medium">1</span>
                <button class="btn btn-ghost btn-sm text-sm">2</button>
                <button class="btn btn-ghost btn-sm text-sm">3</button>
                <button class="btn btn-secondary btn-sm">→</button>
            </div>
        </div>
    </div>
</div>
@endsection
