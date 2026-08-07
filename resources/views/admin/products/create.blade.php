@extends('layouts.admin')
@section('title', 'Nouveau Produit')
@section('content')
<div class="space-y-6 max-w-4xl">

    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="/admin/products" class="hover:text-primary-600 dark:hover:text-primary-400">Produits</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white">Nouveau produit</span>
    </nav>

    <form action="/admin/products" method="POST" class="space-y-6">
        @csrf

        <!-- General Info -->
        <div class="card">
            <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Informations Générales</h3></div>
            <div class="card-body space-y-5">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Nom du produit <span class="text-danger-500">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="Ex: Hébergement Premium" required>
                    </div>
                    <div>
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-input" placeholder="hebergement-premium">
                    </div>
                    <div>
                        <label class="form-label">Catégorie <span class="text-danger-500">*</span></label>
                        <select name="category_id" class="form-input" required>
                            <option value="">Sélectionner…</option>
                            <option>Hébergement Web</option>
                            <option>VPS</option>
                            <option>Serveurs Dédiés</option>
                            <option>Serveurs de Jeux</option>
                            <option>Domaines</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Module de provisionnement</label>
                        <select name="module" class="form-input">
                            <option value="">Aucun (manuel)</option>
                            <option>cPanel</option>
                            <option>Plesk</option>
                            <option>DirectAdmin</option>
                            <option>Pterodactyl</option>
                            <option>Proxmox</option>
                            <option>SolusVM</option>
                            <option>Docker</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="Description du produit visible par le client…"></textarea>
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-primary-600 rounded">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Produit actif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-primary-600 rounded">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Mis en avant</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="auto_provision" value="1" class="w-4 h-4 text-primary-600 rounded">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Provisionnement automatique</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="card">
            <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Tarification</h3></div>
            <div class="card-body space-y-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach(['Mensuel' => 'monthly', 'Trimestriel' => 'quarterly', 'Semestriel' => 'semiannually', 'Annuel' => 'annually', 'Biennal' => 'biennially', 'Frais d\'installation' => 'setup_fee'] as $label => $key)
                    <div>
                        <label class="form-label text-sm">{{ $label }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-sm">€</span>
                            <input type="number" name="price_{{ $key }}" step="0.01" min="0" class="form-input pl-7 text-sm" placeholder="0.00">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Devise</label>
                        <select name="currency" class="form-input">
                            <option value="EUR" selected>€ EUR</option>
                            <option value="USD">$ USD</option>
                            <option value="GBP">£ GBP</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Stock (vide = illimité)</label>
                        <input type="number" name="stock" min="0" class="form-input" placeholder="Illimité">
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources -->
        <div class="card">
            <div class="card-header"><h3 class="font-bold text-gray-900 dark:text-white">Ressources</h3></div>
            <div class="card-body">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach(['Stockage (Go)' => 'disk', 'Bande passante (Go)' => 'bandwidth', 'Bases de données' => 'databases', 'Comptes emails' => 'emails', 'Sous-domaines' => 'subdomains', 'vCPU' => 'cpu', 'RAM (Go)' => 'ram', 'Adresses IP' => 'ips', 'Slots joueurs' => 'slots'] as $label => $key)
                    <div>
                        <label class="form-label text-xs">{{ $label }}</label>
                        <input type="text" name="resources[{{ $key }}]" class="form-input text-sm" placeholder="Illimité">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Créer le produit
            </button>
            <a href="/admin/products" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>
@endsection
