@extends('layouts.admin')
@section('title', 'Serveurs')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Serveurs de Provisionnement</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pterodactyl, cPanel, Plesk, Proxmox, Docker…</p>
        </div>
        <a href="{{ route('admin.servers.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un Serveur
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid gap-4">
        @forelse($servers as $server)
        <div class="card hover:shadow-md transition-shadow">
            <div class="card-body">
                <div class="flex items-start justify-between gap-4">
                    <!-- Statut & nom -->
                    <div class="flex items-start gap-4">
                        <div class="mt-1">
                            @if($server->status === 'online')
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                                    <span class="text-xs font-medium text-green-600 dark:text-green-400">En ligne</span>
                                </span>
                            @elseif($server->status === 'maintenance')
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                    <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Maintenance</span>
                                </span>
                            @else
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-xs font-medium text-red-600 dark:text-red-400">Hors ligne</span>
                                </span>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $server->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $server->getTypeLabel() }} — {{ $server->hostname }}:{{ $server->port }}
                                @if(!$server->use_ssl)<span class="text-yellow-500 ml-1 text-xs">(HTTP)</span>@endif
                            </p>
                            @if($server->notes)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $server->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Compteurs -->
                    <div class="flex items-center gap-6 text-center flex-shrink-0">
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $server->services_count }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Services</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $server->current_accounts }}
                                @if($server->max_accounts)<span class="text-sm text-gray-400">/ {{ $server->max_accounts }}</span>@endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Comptes</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="{{ route('admin.servers.test', $server) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" title="Tester la connexion">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Tester
                            </button>
                        </form>
                        <a href="{{ route('admin.servers.edit', $server) }}" class="btn btn-sm btn-secondary">Modifier</a>
                        <form action="{{ route('admin.servers.destroy', $server) }}" method="POST" onsubmit="return confirm('Supprimer ce serveur ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                        </form>
                    </div>
                </div>

                @if($server->last_checked_at)
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                    Dernière vérification : {{ $server->last_checked_at->diffForHumans() }}
                    @if($server->last_check_data && isset($server->last_check_data['message']))
                        — {{ $server->last_check_data['message'] }}
                    @endif
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-16">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Aucun serveur configuré.</p>
                <a href="{{ route('admin.servers.create') }}" class="btn btn-primary">Ajouter votre premier serveur</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
