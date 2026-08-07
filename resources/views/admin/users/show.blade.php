@extends('layouts.admin')
@section('title', 'Profil Utilisateur')
@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="/admin/users" class="hover:text-primary-600 dark:hover:text-primary-400">Utilisateurs</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">Jean Dupont</span>
    </nav>

    <!-- Header Card -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center gap-6 justify-between">
                <div class="flex items-center gap-5">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=Jean+Dupont&background=0ea5e9&color=fff&size=80" class="w-20 h-20 rounded-2xl shadow">
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-success-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Jean Dupont</h2>
                            <span class="badge badge-success">Actif</span>
                            <span class="badge badge-primary">Client</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">jean.dupont@exemple.com</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">🇫🇷 France · Membre depuis le 15 janvier 2024 · ID #1042</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="/admin/users/1/edit" class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier
                    </a>
                    <button class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Se connecter en tant que
                    </button>
                    <button class="btn btn-warning">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Suspendre
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['12', 'Services actifs', 'primary', 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01'],
            ['489,88 €', 'Total dépensé', 'success', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['24', 'Factures totales', 'warning', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['2', 'Tickets ouverts', 'secondary', 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
        ] as [$val, $label, $color, $path])
        <div class="card">
            <div class="card-body py-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $val }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Left -->
        <div class="space-y-4">
            <!-- Infos -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations</h3></div>
                <div class="card-body space-y-3 text-sm">
                    @foreach([
                        ['Téléphone', '+33 6 12 34 56 78'],
                        ['Entreprise', 'Non renseigné'],
                        ['Adresse', '456 Rue du Client, 69000 Lyon'],
                        ['Pays', '🇫🇷 France'],
                        ['Langue', '🇫🇷 Français'],
                        ['Devise', '€ EUR'],
                        ['TVA', 'Non renseigné'],
                        ['Solde crédit', '15,00 €'],
                        ['2FA', '❌ Désactivé'],
                    ] as [$k, $v])
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $k }}</span>
                        <span class="text-gray-900 dark:text-white font-medium text-right">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Notes Admin</h3></div>
                <div class="card-body">
                    <textarea class="form-input text-sm" rows="4" placeholder="Ajouter une note interne..."></textarea>
                    <button class="btn btn-secondary btn-sm mt-2">Sauvegarder</button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Actions Rapides</h3></div>
                <div class="card-body space-y-2">
                    <button class="btn btn-secondary w-full justify-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Envoyer un email
                    </button>
                    <button class="btn btn-secondary w-full justify-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ajouter un crédit
                    </button>
                    <button class="btn btn-secondary w-full justify-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Réinitialiser mot de passe
                    </button>
                    <button class="btn btn-secondary w-full justify-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Créer une facture manuelle
                    </button>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                        <button class="btn btn-danger w-full justify-start gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer le compte
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right (Tabs) -->
        <div class="lg:col-span-2">
            <div x-data="{ tab: 'services' }">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px overflow-x-auto">
                        @foreach(['services' => 'Services (12)', 'invoices' => 'Factures (24)', 'tickets' => 'Tickets (18)', 'activity' => 'Activité'] as $k => $l)
                        <button @click="tab = '{{ $k }}'" :class="tab === '{{ $k }}' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-5 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">{{ $l }}</button>
                        @endforeach
                    </nav>
                </div>

                <!-- Services Tab -->
                <div x-show="tab === 'services'" class="mt-4">
                    <div class="card">
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead><tr><th>Service</th><th>Type</th><th>Prix</th><th>Renouvellement</th><th>Statut</th></tr></thead>
                                <tbody>
                                    @foreach([
                                        ['Hébergement Premium', 'monsite.com', 'Hébergement', '19,99 €/mois', '15/03/2024', 'active'],
                                        ['VPS Cloud Standard', '192.168.1.1', 'VPS', '29,99 €/mois', '20/03/2024', 'active'],
                                        ['Minecraft — Survie', 'play.server.com', 'Jeux', '9,99 €/mois', '01/03/2024', 'suspended'],
                                        ['Domaine .com', 'monsite.com', 'Domaine', '12,99 €/an', '05/03/2024', 'active'],
                                    ] as [$name, $host, $type, $price, $renew, $status])
                                    <tr>
                                        <td>
                                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $host }}</p>
                                        </td>
                                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $type }}</td>
                                        <td class="text-sm font-semibold text-gray-900 dark:text-white">{{ $price }}</td>
                                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $renew }}</td>
                                        <td>
                                            @if($status === 'active') <span class="badge badge-success">Actif</span>
                                            @else <span class="badge badge-warning">Suspendu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div x-show="tab === 'invoices'" class="mt-4">
                    <div class="card">
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead><tr><th>N° Facture</th><th>Date</th><th>Montant</th><th>Passerelle</th><th>Statut</th></tr></thead>
                                <tbody>
                                    @foreach([
                                        ['INV-2024-003','01/02/2024','29,99 €','Stripe','pending'],
                                        ['INV-2024-002','15/01/2024','19,99 €','Stripe','paid'],
                                        ['INV-2024-001','01/01/2024','29,99 €','PayPal','paid'],
                                    ] as [$num, $date, $amount, $gw, $status])
                                    <tr>
                                        <td><a href="/admin/invoices/1" class="text-primary-600 dark:text-primary-400 font-mono text-sm hover:underline">#{{ $num }}</a></td>
                                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $date }}</td>
                                        <td class="font-semibold text-gray-900 dark:text-white">{{ $amount }}</td>
                                        <td><span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $gw }}</span></td>
                                        <td>
                                            @if($status === 'paid') <span class="badge badge-success">Payée</span>
                                            @else <span class="badge badge-warning">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tickets Tab -->
                <div x-show="tab === 'tickets'" class="mt-4">
                    <div class="card divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach([
                            ['1234','Problème FTP','Support Technique','open','2 min'],
                            ['1233','Question facturation','Facturation','in_progress','1h'],
                            ['1200','Erreur 500','Support Technique','closed','3 jours'],
                        ] as [$id, $subj, $cat, $status, $time])
                        <a href="/admin/tickets/{{ $id }}" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ $id }} — {{ $subj }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cat }} · {{ $time }}</p>
                            </div>
                            @if($status === 'open') <span class="badge badge-warning">Ouvert</span>
                            @elseif($status === 'in_progress') <span class="badge badge-primary">En cours</span>
                            @else <span class="badge badge-success">Fermé</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Activity Tab -->
                <div x-show="tab === 'activity'" class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="space-y-4">
                                @foreach([
                                    ['Connexion depuis 82.65.100.10 (Paris, FR)', 'Il y a 2h', 'primary'],
                                    ['Paiement de 19,99 € via Stripe', 'Il y a 1 jour', 'success'],
                                    ['Ticket #1234 ouvert', 'Il y a 1 jour', 'warning'],
                                    ['Service Hébergement Premium activé', 'Il y a 15 jours', 'success'],
                                    ['Compte créé', 'Il y a 23 jours', 'primary'],
                                ] as [$desc, $time, $color])
                                <div class="flex gap-3 items-start">
                                    <span class="flex-shrink-0 mt-2 w-2 h-2 rounded-full bg-{{ $color }}-500"></span>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $desc }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $time }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
