@extends('layouts.admin')
@section('title', 'Catégories de Produits')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Catégories</h1>
        </div>
        <a href="{{ route('admin.products.categories.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvelle Catégorie
        </a>
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
                        <th>Ordre</th>
                        <th>Icône</th>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Produits</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="text-gray-500 dark:text-gray-400">{{ $category->order }}</td>
                        <td>
                            @if($category->icon)
                                <i class="{{ $category->icon }} text-gray-600 dark:text-gray-300 text-lg"></i>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                            @if($category->description)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($category->description, 60) }}</p>
                            @endif
                        </td>
                        <td><code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge badge-secondary">{{ $category->products_count }}</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-danger">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.categories.edit', $category) }}" class="btn btn-sm btn-secondary">Modifier</a>
                                <form action="{{ route('admin.products.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Suppr.</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-12">
                            Aucune catégorie. <a href="{{ route('admin.products.categories.create') }}" class="text-primary-600 hover:underline">Créer la première</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
