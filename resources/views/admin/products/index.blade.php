@extends('layouts.admin')

@section('title', 'Produits')
@section('content')
    <x-page-header title="Produits">
        <x-slot:actions>
            <x-button :href="route('admin.products.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouveau produit
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card padding="false" style="margin-bottom: var(--hc-space-6);">
        <form method="GET" style="padding: var(--hc-space-4); display: flex; gap: var(--hc-space-3); flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="hc-label">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom du produit..." class="hc-input">
            </div>
            <div style="min-width: 180px;">
                <label class="hc-label">Catégorie</label>
                <select name="category" class="hc-select">
                    <option value="">Toutes</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width: 140px;">
                <label class="hc-label">Type</label>
                <select name="type" class="hc-select">
                    <option value="">Tous</option>
                    <option value="shared" @selected(request('type') === 'shared')>Mutualisé</option>
                    <option value="vps" @selected(request('type') === 'vps')>VPS</option>
                    <option value="dedicated" @selected(request('type') === 'dedicated')>Dédié</option>
                    <option value="domain" @selected(request('type') === 'domain')>Domaine</option>
                    <option value="ssl" @selected(request('type') === 'ssl')>SSL</option>
                </select>
            </div>
            <x-button type="submit" variant="primary">Filtrer</x-button>
            @if(request('search') || request('category') || request('type'))
                <a href="{{ route('admin.products.index') }}" class="hc-btn hc-btn-ghost">Réinitialiser</a>
            @endif
        </form>
    </x-card>

    @if(($products ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucun produit" description="Commencez par créer votre premier produit." icon="📦">
                <x-button :href="route('admin.products.create')" variant="primary">Nouveau produit</x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Produit', 'Catégorie', 'Type', 'Prix', 'Stock', 'Services', 'Statut', '']">
                @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ ucfirst($product->type ?? '—') }}</td>
                        <td><strong>{{ number_format($product->price, 2) }} €</strong><br><span style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">/ {{ $product->billing_cycle }}</span></td>
                        <td>
                            @if($product->is_unlimited_stock)
                                <span style="color: var(--hc-text-muted);">Illimité</span>
                            @else
                                {{ $product->stock ?? 0 }}
                            @endif
                        </td>
                        <td>{{ $product->services_count ?? 0 }}</td>
                        <td>
                            @if($product->is_active)
                                <x-badge variant="success">Actif</x-badge>
                            @else
                                <x-badge variant="neutral">Inactif</x-badge>
                            @endif
                            @if($product->is_featured)
                                <x-badge variant="warning" style="margin-left: 4px;">★</x-badge>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $products->links() }}
        </div>
    @endif
@endsection
