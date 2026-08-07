@extends('layouts.admin')
@section('title', 'Sauvegardes')
@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Sauvegardes</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez les sauvegardes de votre système</p>
        </div>
        <button class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Lancer une sauvegarde
        </button>
    </div>

    <!-- Status Cards -->
    <div class="grid sm:grid-cols-3 gap-4">
        @foreach([
            ['Dernière sauvegarde', 'Il y a 2 heures', 'Complète — 845 Mo', 'success'],
            ['Prochaine sauvegarde', 'Dans 22 heures', 'Automatique — Quotidienne', 'primary'],
            ['Stockage utilisé', '12,4 Go / 50 Go', '25% — 37,6 Go disponibles', 'warning'],
        ] as [$title, $value, $sub, $color])
        <div class="card border-l-4 border-{{ $color }}-500">
            <div class="card-body">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
                <p class="text-xs text-{{ $color }}-600 dark:text-{{ $color }}-400 mt-1">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Backup Config -->
    <div class="card">
        <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Configuration</h3></div>
        <div class="card-body">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="form-label">Fréquence automatique</label>
                    <select class="form-input">
                        <option>Quotidienne</option>
                        <option>Hebdomadaire</option>
                        <option>Bi-hebdomadaire</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Rétention (jours)</label>
                    <input type="number" value="30" class="form-input">
                </div>
                <div>
                    <label class="form-label">Stockage destination</label>
                    <select class="form-input">
                        <option>Local (disque serveur)</option>
                        <option>Amazon S3</option>
                        <option>Backblaze B2</option>
                        <option>FTP externe</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                @foreach(['Sauvegarder la base de données', 'Sauvegarder les fichiers uploadés', 'Chiffrer les sauvegardes', 'Envoyer une notification par email'] as $opt)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" checked class="w-4 h-4 text-primary-600 rounded">
                    <span class="text-sm text-gray-900 dark:text-white">{{ $opt }}</span>
                </label>
                @endforeach
            </div>
            <button class="btn btn-primary mt-4">Enregistrer la configuration</button>
        </div>
    </div>

    <!-- Backup List -->
    <div class="card">
        <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Historique des Sauvegardes</h3></div>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead><tr><th>Date</th><th>Type</th><th>Taille</th><th>Durée</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach([
                        ['07/08/2026 12:00', 'Automatique', '845 Mo', '4m 32s', 'success'],
                        ['06/08/2026 12:00', 'Automatique', '841 Mo', '4m 18s', 'success'],
                        ['05/08/2026 14:30', 'Manuelle',    '840 Mo', '4m 25s', 'success'],
                        ['05/08/2026 12:00', 'Automatique', '838 Mo', '4m 10s', 'failed'],
                        ['04/08/2026 12:00', 'Automatique', '835 Mo', '4m 05s', 'success'],
                    ] as [$date, $type, $size, $duration, $status])
                    <tr>
                        <td class="text-sm font-medium text-gray-900 dark:text-white">{{ $date }}</td>
                        <td><span class="badge {{ $type === 'Manuelle' ? 'badge-primary' : 'badge-success' }}">{{ $type }}</span></td>
                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $size }}</td>
                        <td class="text-sm text-gray-600 dark:text-gray-400">{{ $duration }}</td>
                        <td>
                            @if($status === 'success') <span class="badge badge-success">Succès</span>
                            @else <span class="badge badge-danger">Échec</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                @if($status === 'success')
                                <button class="btn btn-ghost btn-sm" title="Télécharger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </button>
                                <button class="btn btn-ghost btn-sm text-warning-600" title="Restaurer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </button>
                                @endif
                                <button class="btn btn-ghost btn-sm text-danger-600" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
