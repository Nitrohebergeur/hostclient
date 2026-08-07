@extends('layouts.admin')
@section('title', 'Créer un Produit')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau Produit</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf

        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Infos générales -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Informations générales</h3></div>
                    <div class="card-body space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Slug (optionnel)</label>
                                <input type="text" name="slug" value="{{ old('slug') }}" class="form-input" placeholder="Généré automatiquement">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                        </div>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                                <select name="category_id" class="form-input" required>
                                    <option value="">Choisir...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Type <span class="text-red-500">*</span></label>
                                <select name="type" class="form-input" required>
                                    @foreach(['hosting' => 'Hébergement', 'vps' => 'VPS', 'dedicated' => 'Dédié', 'game' => 'Jeu', 'domain' => 'Domaine', 'custom' => 'Personnalisé'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('type') == $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Module</label>
                                <select name="module" class="form-input">
                                    <option value="">Aucun</option>
                                    @foreach(['cpanel' => 'cPanel/WHM', 'plesk' => 'Plesk', 'directadmin' => 'DirectAdmin', 'pterodactyl' => 'Pterodactyl', 'proxmox' => 'Proxmox', 'docker' => 'Docker', 'virtualizor' => 'Virtualizor', 'solusvm' => 'SolusVM', 'custom' => 'Custom'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('module') == $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarification -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Tarification</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Laissez à 0 pour désactiver un cycle</p>
                    </div>
                    <div class="card-body space-y-4">

                        <!-- Facturation horaire -->
                        <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center justify-between mb-3">
                                <label class="font-medium text-purple-800 dark:text-purple-300">⏱ Facturation Horaire</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="allow_hourly_billing" value="0">
                                    <input type="checkbox" name="allow_hourly_billing" value="1" id="allow_hourly" class="sr-only peer" @checked(old('allow_hourly_billing'))>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:bg-purple-600 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div>
                                <label class="form-label">Prix à l'heure (€)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                    <input type="number" name="price_hourly" value="{{ old('price_hourly', '0.0000') }}" step="0.0001" min="0" class="form-input pl-7">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ex: 0.0149 = 0.0149 €/h ≈ 10.87 €/mois</p>
                            </div>
                        </div>

                        <!-- Cycles standards -->
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach([
                                ['price_monthly', 'Mensuel', '/mois'],
                                ['price_quarterly', 'Trimestriel', '/3 mois'],
                                ['price_semiannually', 'Semestriel', '/6 mois'],
                                ['price_annually', 'Annuel', '/an'],
                                ['price_biennially', 'Biennal', '/2 ans'],
                                ['setup_fee', 'Frais de mise en service', 'unique'],
                            ] as [$field, $label, $per])
                            <div>
                                <label class="form-label">{{ $label }} <span class="text-xs text-gray-400">({{ $per }})</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                    <input type="number" name="{{ $field }}" value="{{ old($field, '0.00') }}" step="0.01" min="0" class="form-input pl-7">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="w-36">
                            <label class="form-label">Devise</label>
                            <select name="currency" class="form-input">
                                @foreach(['EUR' => '€ EUR', 'USD' => '$ USD', 'GBP' => '£ GBP', 'CAD' => 'C$ CAD'] as $code => $label)
                                    <option value="{{ $code }}" @selected(old('currency', 'EUR') == $code)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Ressources -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Ressources incluses</h3></div>
                    <div class="card-body">
                        <div class="grid md:grid-cols-2 gap-4" id="resources-fields">
                            @foreach(['disk' => 'Espace disque', 'bandwidth' => 'Bande passante', 'cpu' => 'CPU', 'ram' => 'RAM', 'databases' => 'Bases de données', 'email_accounts' => 'Comptes email', 'domains' => 'Domaines', 'slots' => 'Slots joueurs'] as $key => $label)
                            <div>
                                <label class="form-label text-xs">{{ $label }}</label>
                                <input type="text" name="resources[{{ $key }}]" value="{{ old("resources.$key") }}" class="form-input" placeholder="Ex: 10 GB ou Illimité">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">

                <!-- Options -->
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Options</h3></div>
                    <div class="card-body space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">Actif</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', true))>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">En vedette</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer" @checked(old('is_featured'))>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-yellow-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="form-label mb-0">Provisionnement auto</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="auto_provision" value="0">
                                <input type="checkbox" name="auto_provision" value="1" class="sr-only peer" @checked(old('auto_provision'))>
                                <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        <div>
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Stock <span class="text-xs text-gray-400">(vide = illimité)</span></label>
                            <input type="number" name="stock" value="{{ old('stock') }}" min="0" class="form-input" placeholder="Illimité">
                        </div>
                    </div>
                </div>

                <!-- Serveurs -->
                @if($servers->isNotEmpty())
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Serveurs de provisionnement</h3></div>
                    <div class="card-body space-y-2">
                        @foreach($servers as $server)
                        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <input type="checkbox" name="servers[]" value="{{ $server->id }}" class="rounded" @checked(in_array($server->id, old('servers', [])))>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $server->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $server->getTypeLabel() }}</p>
                            </div>
                            <span class="ml-auto w-2 h-2 rounded-full {{ $server->status === 'online' ? 'bg-green-500' : 'bg-red-400' }}"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Groupes -->
                @if($groups->isNotEmpty())
                <div class="card">
                    <div class="card-header"><h3 class="font-semibold text-gray-900 dark:text-white">Groupes</h3></div>
                    <div class="card-body space-y-2">
                        @foreach($groups as $group)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="groups[]" value="{{ $group->id }}" class="rounded" @checked(in_array($group->id, old('groups', [])))>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $group->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="submit" class="btn btn-primary w-full">
                    Créer le produit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
