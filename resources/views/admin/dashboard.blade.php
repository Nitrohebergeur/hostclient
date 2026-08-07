@extends('layouts.admin')

@section('title', 'Vue d\'ensemble')

@section('content')
<div class="space-y-6">

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['Revenus du mois',  '12 480 €',  '+18%',  true,  'primary',   'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Nouveaux Clients', '48',         '+12%',  true,  'success',   'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
            ['Services Actifs',  '324',        '+5%',   true,  'secondary', 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01'],
            ['Tickets Ouverts',  '7',          '-30%',  false, 'warning',   'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
        ] as [$label, $value, $change, $up, $color, $path])
        <div class="card hover:shadow-md transition-shadow" data-aos="fade-up">
            <div class="card-body">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
                        <p class="text-sm mt-2 flex items-center gap-1 {{ $up ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $up ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                            </svg>
                            {{ $change }} vs mois dernier
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Revenue Chart -->
        <div class="lg:col-span-2 card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white">Revenus mensuels</h3>
                <select class="form-input w-36 text-sm py-1.5">
                    <option>12 derniers mois</option>
                    <option>6 derniers mois</option>
                    <option>Cette année</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        <!-- Service Distribution -->
        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <h3 class="font-bold text-gray-900 dark:text-white">Répartition Services</h3>
            </div>
            <div class="card-body">
                <canvas id="servicesChart" height="200"></canvas>
                <div class="mt-4 space-y-2">
                    @foreach([
                        ['Hébergement Web', '45%', 'primary'],
                        ['VPS',             '28%', 'secondary'],
                        ['Domaines',        '15%', 'success'],
                        ['Serveurs Jeux',   '8%',  'warning'],
                        ['Autres',          '4%',  'gray'],
                    ] as [$label, $pct, $color])
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-{{ $color }}-500"></span>
                            <span class="text-gray-700 dark:text-gray-300">{{ $label }}</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $pct }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Recent Orders -->
        <div class="card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white">Dernières Commandes</h3>
                <a href="/admin/orders" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">Voir tout</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead><tr><th>Client</th><th>Produit</th><th>Montant</th><th>Statut</th></tr></thead>
                    <tbody>
                        @foreach([
                            ['Jean D.', 'Hébergement Premium',  '19,99 €', 'active'],
                            ['Marie M.','VPS Cloud Standard',   '29,99 €', 'pending'],
                            ['Paul R.', 'Minecraft Server',     '9,99 €',  'active'],
                            ['Sophie L.','Domaine .com',        '12,99 €', 'active'],
                            ['Luc B.',  'Hébergement Starter',  '4,99 €',  'suspended'],
                        ] as [$name, $product, $amount, $status])
                        <tr>
                            <td class="font-medium text-gray-900 dark:text-white">{{ $name }}</td>
                            <td class="text-gray-600 dark:text-gray-400 text-sm">{{ $product }}</td>
                            <td class="font-semibold text-gray-900 dark:text-white">{{ $amount }}</td>
                            <td>
                                @if($status === 'active')   <span class="badge badge-success">Actif</span>
                                @elseif($status === 'pending') <span class="badge badge-warning">En attente</span>
                                @else <span class="badge badge-danger">Suspendu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white">Tickets Récents</h3>
                <a href="/admin/tickets" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach([
                        ['1234','Problème connexion FTP',    'high',   'open',        'Jean D.',   '2 min'],
                        ['1233','Question facturation',      'normal', 'in_progress', 'Marie M.',  '1h'],
                        ['1232','Mise à niveau VPS',         'normal', 'waiting',     'Paul R.',   '3h'],
                        ['1231','Erreur 500 sur site',       'high',   'open',        'Sophie L.', '5h'],
                    ] as [$id, $subj, $prio, $status, $client, $time])
                    <a href="/admin/tickets/{{ $id }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $prio === 'high' ? 'bg-danger-500 animate-pulse' : 'bg-warning-400' }}"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">#{{ $id }} — {{ $subj }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $client }} · {{ $time }}</p>
                        </div>
                        @if($status === 'open')         <span class="badge badge-warning text-xs">Ouvert</span>
                        @elseif($status === 'in_progress') <span class="badge badge-primary text-xs">En cours</span>
                        @else                           <span class="badge badge-danger text-xs">En attente</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- System Status -->
    <div class="card" data-aos="fade-up" data-aos-delay="500">
        <div class="card-header">
            <h3 class="font-bold text-gray-900 dark:text-white">Statut du Système</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    ['Queue Workers',   'En ligne',  'success', '3 workers actifs'],
                    ['Cache Redis',     'En ligne',  'success', '45 Mo utilisés'],
                    ['Base de données', 'En ligne',  'success', '12 ms latence'],
                    ['Espace Disque',   'Attention', 'warning', '78% utilisé'],
                ] as [$name, $status, $color, $info])
                <div class="flex items-center gap-3 p-4 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 rounded-xl border border-{{ $color }}-200 dark:border-{{ $color }}-800">
                    <span class="flex-shrink-0 w-3 h-3 rounded-full bg-{{ $color }}-500 {{ $color === 'success' ? 'animate-pulse-slow' : '' }}"></span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $name }}</p>
                        <p class="text-xs text-{{ $color }}-700 dark:text-{{ $color }}-300">{{ $status }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $info }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: ['Août','Sep','Oct','Nov','Déc','Jan','Fév','Mar','Avr','Mai','Jun','Jul'],
            datasets: [
                {
                    label: 'Revenus (€)',
                    data: [8200,9100,8700,10200,11500,9800,10400,11200,10800,11900,12100,12480],
                    backgroundColor: 'rgba(14,165,233,0.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Objectif (€)',
                    data: [9000,9000,9000,10000,10000,10000,11000,11000,11000,12000,12000,12000],
                    type: 'line',
                    borderColor: 'rgba(217,70,239,0.7)',
                    borderDash: [5,5],
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: false,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: textColor, font: { family: 'Inter' } } } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, callback: v => v.toLocaleString('fr-FR') + ' €' } }
            }
        }
    });

    // Services Donut
    new Chart(document.getElementById('servicesChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hébergement','VPS','Domaines','Jeux','Autres'],
            datasets: [{
                data: [45,28,15,8,4],
                backgroundColor: ['#0ea5e9','#d946ef','#22c55e','#f59e0b','#94a3b8'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
