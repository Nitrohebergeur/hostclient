@extends('layouts.admin')
@section('title', 'Produits')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Produits</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gérer vos offres et tarifs</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Catégories
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau Produit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Tarifs</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->module ?? 'Aucun module' }}</p>
                                </div>
                                @if($product->is_featured)
                                    <span class="badge badge-warning text-xs">⭐ Vedette</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $product->category->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary capitalize">{{ $product->type }}</span>
                        </td>
                        <td>
                            <div class="text-xs space-y-0.5">
                                @if($product->allow_hourly_billing && $product->price_hourly > 0)
                                    <div class="text-purple-600 dark:text-purple-400 font-medium">{{ number_format($product->price_hourly, 4) }} €/h</div>
                                @endif
                                @if($product->price_monthly > 0)
                                    <div>{{ number_format($product->price_monthly, 2) }} €/mois</div>
                                @endif
                                @if($product->price_annually > 0)
                                    <div class="text-green-600 dark:text-green-400">{{ number_format($product->price_annually, 2) }} €/an</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-danger">Inactif</span>
                            @endif
                            @if($product->stock !== null)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stock: {{ $product->stock }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary">Modifier</a>
                                <form action="{{ route('admin.products.duplicate', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Dupliquer">⧉</button>
                                </form>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 dark:text-gray-400 py-12">
                            Aucun produit. <a href="{{ route('admin.products.create') }}" class="text-primary-600 hover:underline">Créer le premier</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="card-body border-t border-gray-200 dark:border-gray-700">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
