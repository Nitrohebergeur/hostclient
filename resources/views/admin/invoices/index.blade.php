@extends('layouts.admin')

@section('title', 'Factures')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Factures</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez toutes les factures clients</p>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exporter CSV
            </button>
            <a href="/admin/invoices/create" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nouvelle Facture
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['Total ce mois',   '15 480 €',  'primary'],
            ['Payées',          '12 280 €',  'success'],
            ['En attente',      '2 940 €',   'warning'],
            ['En retard',       '260 €',     'danger'],
        ] as [$label, $amount, $color])
        <div class="card">
            <div class="card-body py-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400 mt-1">{{ $amount }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" placeholder="N° facture, client, email…" class="form-input flex-1 text-sm">
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous statuts</option>
                    <option>En attente</option>
                    <option>Payée</option>
                    <option>Annulée</option>
                    <option>Remboursée</option>
                </select>
                <input type="month" class="form-input w-full sm:w-40 text-sm">
                <select class="form-input w-full sm:w-44 text-sm">
                    <option>Toutes passerelles</option>
                    <option>Stripe</option>
                    <option>PayPal</option>
                    <option>Mollie</option>
                    <option>Crypto</option>
                    <option>Virement</option>
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
                        <th><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></th>
                        <th>N° Facture</th>
                        <th>Client</th>
                        <th>Services</th>
                        <th>Montant TTC</th>
                        <th>Émise le</th>
                        <th>Échéance</th>
                        <th>Passerelle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['INV-2024-012','Jean Dupont',   'Hébergement Premium',      '29,99 €', '01/02/2024','15/02/2024','Stripe',  'paid'],
                        ['INV-2024-011','Marie Martin',  'VPS Cloud Standard',       '35,99 €', '28/01/2024','12/02/2024','PayPal',  'pending'],
                        ['INV-2024-010','Paul Robert',   'Minecraft Server + VPS',   '39,98 €', '25/01/2024','08/02/2024','Mollie',  'overdue'],
                        ['INV-2024-009','Sophie Laurent','Domaine .com — Annuel',    '15,59 €', '20/01/2024','03/02/2024','Stripe',  'paid'],
                        ['INV-2024-008','Luc Bernard',   'Hébergement Starter',      '5,99 €',  '15/01/2024','29/01/2024','Credit',  'cancelled'],
                        ['INV-2024-007','Emma Petit',    'VPS Cloud Pro',            '59,99 €', '10/01/2024','24/01/2024','Stripe',  'refunded'],
                    ] as [$num, $client, $desc, $amount, $issued, $due, $gw, $status])
                    <tr>
                        <td><input type="checkbox" class="w-4 h-4 rounded border-gray-300"></td>
                        <td><a href="/admin/invoices/1" class="font-mono text-primary-600 dark:text-primary-400 hover:underline text-sm">#{{ $num }}</a></td>
                        <td class="font-medium text-gray-900 dark:text-white text-sm">{{ $client }}</td>
                        <td class="text-gray-600 dark:text-gray-400 text-sm max-w-40 truncate">{{ $desc }}</td>
                        <td class="font-bold text-gray-900 dark:text-white">{{ $amount }}</td>
                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $issued }}</td>
                        <td class="text-sm {{ $status === 'overdue' ? 'text-danger-600 dark:text-danger-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">{{ $due }}</td>
                        <td>
                            <span class="text-xs font-medium px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $gw }}</span>
                        </td>
                        <td>
                            @if($status === 'paid')       <span class="badge badge-success">Payée</span>
                            @elseif($status === 'pending') <span class="badge badge-warning">En attente</span>
                            @elseif($status === 'overdue') <span class="badge badge-danger">En retard</span>
                            @elseif($status === 'cancelled') <span class="badge">Annulée</span>
                            @else                         <span class="badge badge-primary">Remboursée</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="/admin/invoices/1" class="btn btn-ghost btn-sm" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="/admin/invoices/1/pdf" class="btn btn-ghost btn-sm" title="PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                @if($status === 'pending' || $status === 'overdue')
                                <button class="btn btn-ghost btn-sm text-primary-600" title="Marquer payée">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">Affichage 1–6 sur 2 841 factures</p>
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
