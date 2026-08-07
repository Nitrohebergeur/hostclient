@extends('layouts.admin')
@section('title', 'Ajouter un Serveur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.servers.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter un Serveur</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.servers.store') }}" class="space-y-6">
        @csrf

        <!-- Type de serveur -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Type de serveur</h3></div>
            <div class="card-body">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3" id="server-type-selector">
                    @foreach($types as $value => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $value }}" class="sr-only peer" @checked(old('type') === $value) required>
                        <div class="border-2 rounded-xl p-3 text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                            <div class="text-2xl mb-1">
                                @switch($value)
                                    @case('pterodactyl') 🦕 @break
                                    @case('cpanel') 🔵 @break
                                    @case('plesk') 🟣 @break
                                    @case('proxmox') 🟠 @break
                                    @case('docker') 🐳 @break
                                    @case('directadmin') 🟤 @break
                                    @default 🔧 @break
                                @endswitch
                            </div>
                            <p class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ $label }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Connexion -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Connexion</h3></div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Nom du serveur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Ex: Panel Pterodactyl Principal">
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Hostname / IP <span class="text-red-500">*</span></label>
                        <input type="text" name="hostname" value="{{ old('hostname') }}" class="form-input" required placeholder="panel.example.com ou 192.168.1.1">
                    </div>
                    <div>
                        <label class="form-label">Port <span class="text-red-500">*</span></label>
                        <input type="number" name="port" value="{{ old('port', 443) }}" class="form-input" required>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="use_ssl" value="0">
                    <input type="checkbox" name="use_ssl" value="1" id="use_ssl" class="rounded" @checked(old('use_ssl', true))>
                    <label for="use_ssl" class="form-label mb-0">Utiliser SSL/HTTPS</label>
                </div>
            </div>
        </div>

        <!-- Authentification -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Authentification</h3></div>
            <div class="card-body space-y-4">
                <div class="alert alert-warning text-sm">
                    🔒 Les clés API et mots de passe sont chiffrés en base de données.
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Clé API</label>
                        <input type="password" name="api_key" value="{{ old('api_key') }}" class="form-input font-mono" autocomplete="off">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pterodactyl : Client API Key</p>
                    </div>
                    <div>
                        <label class="form-label">Secret API</label>
                        <input type="password" name="api_secret" value="{{ old('api_secret') }}" class="form-input font-mono" autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-input" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres -->
        <div class="card">
            <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Paramètres</h3></div>
            <div class="card-body space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Comptes max <span class="text-xs text-gray-400">(vide = illimité)</span></label>
                        <input type="number" name="max_accounts" value="{{ old('max_accounts') }}" min="0" class="form-input" placeholder="Illimité">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" @checked(old('is_active'))>
                    <label for="is_active" class="form-label mb-0">Activer ce serveur</label>
                </div>
                <div>
                    <label class="form-label">Notes internes</label>
                    <textarea name="notes" rows="2" class="form-input" placeholder="Informations internes…">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Ajouter le serveur</button>
        </div>
    </form>
</div>
@endsection
