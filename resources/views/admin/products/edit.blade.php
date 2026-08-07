@extends('layouts.admin')

@section('title', 'Modifier ' . $product->name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.products.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux produits
        </a>
    </div>

    <x-page-header title="Modifier {{ $product->name }}" />

    <x-card>
        <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Nom du produit</label>
                    <input type="text" name="name" class="hc-input" value="{{ old('name', $product->name) }}" required>
                </div>
                <div>
                    <label class="hc-label">Catégorie</label>
                    <select name="category_id" class="hc-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Description</label>
                <textarea name="description" class="hc-textarea" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Type</label>
                    <select name="type" class="hc-select" required>
                        @foreach(['shared', 'vps', 'dedicated', 'domain', 'ssl', 'other'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $product->type) === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="hc-label">Prix (€)</label>
                    <input type="number" step="0.01" name="price" class="hc-input" value="{{ old('price', $product->price) }}" required>
                </div>
                <div>
                    <label class="hc-label">Frais d'installation (€)</label>
                    <input type="number" step="0.01" name="setup_fee" class="hc-input" value="{{ old('setup_fee', $product->setup_fee ?? 0) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Cycle de facturation</label>
                    <select name="billing_cycle" class="hc-select" required>
                        @foreach(['monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially', 'once'] as $cycle)
                            <option value="{{ $cycle }}" @selected(old('billing_cycle', $product->billing_cycle) === $cycle)>{{ ucfirst(str_replace('_', ' ', $cycle)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="hc-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" class="hc-input" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Stock</label>
                <input type="number" name="stock" class="hc-input" value="{{ old('stock', $product->stock ?? 0) }}">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); margin-top: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_unlimited_stock" value="0">
                    <input type="checkbox" name="is_unlimited_stock" value="1" @checked(old('is_unlimited_stock', $product->is_unlimited_stock ?? false))>
                    <span style="font-size: var(--hc-text-sm);">Stock illimité</span>
                </label>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Actif</span>
                </label>
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">En vedette</span>
                </label>
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="auto_setup" value="0">
                    <input type="checkbox" name="auto_setup" value="1" @checked(old('auto_setup', $product->auto_setup))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Activation auto</span>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: space-between; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit ?')" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" size="sm">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                        Supprimer
                    </x-button>
                </form>
                <div style="display: flex; gap: var(--hc-space-3);">
                    <x-button :href="route('admin.products.index')" variant="ghost">Annuler</x-button>
                    <x-button type="submit" variant="primary">Enregistrer</x-button>
                </div>
            </div>
        </form>
    </x-card>
@endsection
