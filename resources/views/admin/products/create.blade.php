@extends('layouts.admin')

@section('title', 'Nouveau produit')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.products.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux produits
        </a>
    </div>

    <x-page-header title="Nouveau produit" />

    <x-card>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Nom du produit</label>
                    <input type="text" name="name" class="hc-input" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="hc-label">Catégorie</label>
                    <select name="category_id" class="hc-select" required>
                        <option value="">Sélectionnez</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Description</label>
                <textarea name="description" class="hc-textarea" rows="3">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Type</label>
                    <select name="type" class="hc-select" required>
                        <option value="shared">Mutualisé</option>
                        <option value="vps">VPS</option>
                        <option value="dedicated">Dédié</option>
                        <option value="domain">Domaine</option>
                        <option value="ssl">SSL</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="hc-label">Prix (€)</label>
                    <input type="number" step="0.01" name="price" class="hc-input" value="{{ old('price', 0) }}" required>
                </div>
                <div>
                    <label class="hc-label">Frais d'installation (€)</label>
                    <input type="number" step="0.01" name="setup_fee" class="hc-input" value="{{ old('setup_fee', 0) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Cycle de facturation</label>
                    <select name="billing_cycle" class="hc-select" required>
                        <option value="monthly">Mensuel</option>
                        <option value="quarterly">Trimestriel</option>
                        <option value="semi_annually">Semestriel</option>
                        <option value="annually">Annuel</option>
                        <option value="biennially">Biennal</option>
                        <option value="triennially">Triennal</option>
                        <option value="once">Unique</option>
                    </select>
                </div>
                <div>
                    <label class="hc-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" class="hc-input" value="{{ old('sort_order', 0) }}">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Stock</label>
                <input type="number" name="stock" class="hc-input" value="{{ old('stock', 0) }}">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); margin-top: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_unlimited_stock" value="0">
                    <input type="checkbox" name="is_unlimited_stock" value="1" @checked(old('is_unlimited_stock', true))>
                    <span style="font-size: var(--hc-text-sm);">Stock illimité</span>
                </label>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Actif</span>
                </label>
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">En vedette</span>
                </label>
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="auto_setup" value="0">
                    <input type="checkbox" name="auto_setup" value="1" @checked(old('auto_setup'))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Activation auto</span>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.products.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Créer le produit
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
