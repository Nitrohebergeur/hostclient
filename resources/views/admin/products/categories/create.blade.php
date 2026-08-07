@extends('layouts.admin')
@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.categories.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouvelle Catégorie</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.categories.store') }}" class="card">
        @csrf
        <div class="card-body space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-input" placeholder="Auto-généré">
                </div>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-input">{{ old('description') }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Icône <span class="text-xs text-gray-400">(classe FontAwesome)</span></label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-input" placeholder="fas fa-globe">
                </div>
                <div>
                    <label class="form-label">Ordre</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="form-input">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" @checked(old('is_active', true))>
                <label for="is_active" class="form-label mb-0">Catégorie active</label>
            </div>
        </div>
        <div class="card-footer flex justify-end gap-3">
            <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>
    </form>
</div>
@endsection
