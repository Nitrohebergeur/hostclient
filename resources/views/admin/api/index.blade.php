@extends('layouts.admin')
@section('title', 'API')
@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Documentation API</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">API REST complète avec authentification Bearer & OAuth2</p>
    </div>

    <!-- API Stats -->
    <div class="grid sm:grid-cols-4 gap-4">
        @foreach([
            ['48 291', 'Requêtes (24h)', 'primary'],
            ['99.8%', 'Disponibilité', 'success'],
            ['42 ms', 'Latence moy.', 'warning'],
            ['12', 'Clés actives', 'secondary'],
        ] as [$val, $label, $color])
        <div class="card">
            <div class="card-body py-4">
                <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $val }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Endpoints -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">Endpoints Disponibles</h3>
                    <a href="/api/documentation" target="_blank" class="btn btn-secondary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Swagger UI
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach([
                            ['GET',    '/api/v1/services',           'Lister les services',         200],
                            ['POST',   '/api/v1/services',           'Créer un service',            201],
                            ['GET',    '/api/v1/services/{id}',      'Détail d\'un service',        200],
                            ['DELETE', '/api/v1/services/{id}',      'Résilier un service',         200],
                            ['GET',    '/api/v1/invoices',           'Lister les factures',         200],
                            ['POST',   '/api/v1/invoices',           'Créer une facture',           201],
                            ['GET',    '/api/v1/tickets',            'Lister les tickets',          200],
                            ['POST',   '/api/v1/tickets',            'Ouvrir un ticket',            201],
                            ['GET',    '/api/v1/users/{id}',         'Profil utilisateur',          200],
                            ['GET',    '/api/v1/products',           'Catalogue produits',          200],
                        ] as [$method, $path, $desc, $status])
                        <div class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <span class="text-xs font-bold font-mono w-14 flex-shrink-0
                                {{ $method === 'GET' ? 'text-success-600 dark:text-success-400' :
                                   ($method === 'POST' ? 'text-primary-600 dark:text-primary-400' :
                                   ($method === 'DELETE' ? 'text-danger-600 dark:text-danger-400' : 'text-warning-600 dark:text-warning-400')) }}">
                                {{ $method }}
                            </span>
                            <code class="flex-1 text-xs text-gray-900 dark:text-gray-100 font-mono">{{ $path }}</code>
                            <span class="text-xs text-gray-500 dark:text-gray-400 hidden md:block">{{ $desc }}</span>
                            <span class="text-xs font-semibold {{ $status === 200 ? 'text-success-600 dark:text-success-400' : 'text-primary-600 dark:text-primary-400' }}">{{ $status }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- API Keys -->
        <div class="space-y-4">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">Clés API Admin</h3>
                    <button class="btn btn-primary btn-sm">Nouvelle</button>
                </div>
                <div class="card-body space-y-3">
                    @foreach([
                        ['Intégration CRM', 'hc_admin_live_xxxx…', true],
                        ['Monitoring', 'hc_admin_live_yyyy…', true],
                        ['Staging', 'hc_admin_test_zzzz…', false],
                    ] as [$name, $key, $active])
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $name }}</span>
                            <span class="w-2 h-2 rounded-full {{ $active ? 'bg-success-500' : 'bg-gray-400' }}"></span>
                        </div>
                        <code class="text-xs text-gray-600 dark:text-gray-400 font-mono block mb-2">{{ $key }}</code>
                        <div class="flex gap-1">
                            <button class="btn btn-ghost btn-sm text-xs">Copier</button>
                            <button class="btn btn-ghost btn-sm text-xs text-danger-600">Révoquer</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Example -->
            <div class="card">
                <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Exemple d'utilisation</h3></div>
                <div class="card-body">
                    <pre class="bg-gray-950 text-green-400 text-xs p-3 rounded-lg overflow-x-auto leading-relaxed"><code>curl -X GET \
  https://api.hostclient.io/v1/services \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"

# Réponse
{
  "data": [...],
  "meta": {
    "total": 324,
    "page": 1
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
