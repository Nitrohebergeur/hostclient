@extends('layouts.admin')

@section('title', 'Nouvelle catégorie')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.categories.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux catégories
        </a>
    </div>

    <x-page-header title="Nouvelle catégorie" />

    <x-card style="max-width: 600px;">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Nom</label>
                <input type="text" name="name" class="hc-input" value="{{ old('name') }}" required>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Description</label>
                <textarea name="description" class="hc-textarea" rows="3">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" class="hc-input" value="{{ old('sort_order', 0) }}">
                </div>
                <div style="display: flex; align-items: end; padding-bottom: var(--hc-space-2);">
                    <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        <span style="font-size: var(--hc-text-sm); font-weight: 500;">Active</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.categories.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Créer la catégorie</x-button>
            </div>
        </form>
    </x-card>
@endsection
