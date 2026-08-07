@extends('layouts.admin')

@section('title', 'Catégories')
@section('content')
    <x-page-header title="Catégories">
        <x-slot:actions>
            <x-button :href="route('admin.categories.create')" variant="primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Nouvelle catégorie
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if(($categories ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucune catégorie" description="Commencez par créer votre première catégorie." icon="📁">
                <x-button :href="route('admin.categories.create')" variant="primary">Nouvelle catégorie</x-button>
            </x-empty-state>
        </x-card>
    @else
        <x-card :padding="false">
            <x-table :headers="['Catégorie', 'Slug', 'Produits', 'Ordre', 'Statut', '']">
                @foreach($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td style="font-family: var(--hc-font-mono); font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $category->slug }}</td>
                        <td>{{ $category->products_count ?? 0 }}</td>
                        <td>{{ $category->sort_order ?? 0 }}</td>
                        <td>
                            <x-badge :variant="($category->is_active ?? true) ? 'success' : 'neutral'">
                                {{ ($category->is_active ?? true) ? 'Active' : 'Inactive' }}
                            </x-badge>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="hc-btn hc-btn-ghost hc-btn-sm">
                                <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    @endif
@endsection
