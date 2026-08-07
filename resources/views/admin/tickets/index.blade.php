@extends('layouts.admin')

@section('title', 'Tickets Support')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tickets Support</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez toutes les demandes de support client</p>
        </div>
        <div class="flex gap-3">
            <select class="form-input w-48 text-sm">
                <option>Assigner à moi</option>
                <option>Tous les agents</option>
                <option>Non assignés</option>
            </select>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['Ouverts',     '7',  'warning', true],
            ['En cours',    '12', 'primary', false],
            ['En attente',  '3',  'danger',  false],
            ['Fermés (7j)', '48', 'success', false],
        ] as [$label, $count, $color, $urgent])
        <div class="card {{ $urgent ? 'border-2 border-warning-400 dark:border-warning-600' : '' }}">
            <div class="card-body py-4 flex items-center gap-4">
                <p class="text-3xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $count }}</p>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</p>
                    @if($urgent) <p class="text-xs text-warning-600 dark:text-warning-400">Nécessite attention</p> @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" placeholder="Rechercher un ticket…" class="form-input flex-1 text-sm">
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Tous statuts</option>
                    <option>Ouvert</option>
                    <option>En cours</option>
                    <option>En attente</option>
                    <option>Fermé</option>
                </select>
                <select class="form-input w-full sm:w-36 text-sm">
                    <option>Priorités</option>
                    <option>Urgente</option>
                    <option>Haute</option>
                    <option>Normale</option>
                    <option>Basse</option>
                </select>
                <select class="form-input w-full sm:w-44 text-sm">
                    <option>Toutes catégories</option>
                    <option>Facturation</option>
                    <option>Support Technique</option>
                    <option>Ventes</option>
                    <option>Abus</option>
                </select>
                <select class="form-input w-full sm:w-40 text-sm">
                    <option>Tous agents</option>
                    <option>Non assignés</option>
                    <option>À moi</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Ticket List -->
    <div class="card">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach([
                ['1234', 'Problème connexion FTP',         'Jean Dupont',   'Support Tech', 'high',   'open',        'Non assigné',  '2 min',  false],
                ['1233', 'Question sur ma facture',        'Marie Martin',  'Facturation',  'normal', 'in_progress', 'Alex Martin',  '1h',     true],
                ['1232', 'Demande mise à niveau VPS',      'Paul Robert',   'Ventes',       'normal', 'waiting',     'Sarah Dupuis', '3h',     false],
                ['1231', 'Erreur 500 sur monsite.com',     'Sophie Laurent','Support Tech', 'high',   'open',        'Non assigné',  '5h',     false],
                ['1230', 'Migration vers nouveau serveur', 'Emma Petit',    'Support Tech', 'urgent', 'in_progress', 'Alex Martin',  '8h',     true],
                ['1229', 'Problème envoi email SMTP',      'Luc Bernard',   'Support Tech', 'normal', 'open',        'Non assigné',  '1j',     false],
            ] as [$id, $subj, $client, $cat, $prio, $status, $agent, $time, $replied])
            <a href="/admin/tickets/{{ $id }}" class="flex items-start sm:items-center gap-3 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">

                <!-- Priority dot -->
                <span class="flex-shrink-0 mt-2 sm:mt-0 w-3 h-3 rounded-full
                    {{ $prio === 'urgent' ? 'bg-danger-600 animate-pulse ring-2 ring-danger-300' : ($prio === 'high' ? 'bg-danger-400 animate-pulse' : 'bg-warning-400') }}">
                </span>

                <!-- Main info -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400">
                            #{{ $id }} — {{ $subj }}
                        </p>
                        @if(!$replied)
                            <span class="badge badge-primary text-xs w-fit">Sans réponse</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $client }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $cat }}
                        </span>
                        <span>{{ $agent }}</span>
                        <span>{{ $time }}</span>
                    </div>
                </div>

                <!-- Status + actions -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($status === 'open')         <span class="badge badge-warning">Ouvert</span>
                    @elseif($status === 'in_progress') <span class="badge badge-primary">En cours</span>
                    @else                           <span class="badge badge-danger">En attente</span>
                    @endif
                    <div x-data="dropdown" class="relative" @click.stop>
                        <button @click="toggle" class="btn btn-ghost btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </button>
                        <div x-show="open" @click.away="close" x-transition class="absolute right-0 z-10 mt-1 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                            <a href="/admin/tickets/{{ $id }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Ouvrir</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Assigner</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Changer priorité</a>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <a href="#" class="block px-4 py-2 text-sm text-success-600 hover:bg-gray-100 dark:hover:bg-gray-700">Fermer</a>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="card-footer flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">6 tickets affichés sur 22</p>
            <div class="flex gap-1">
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
