@extends('layouts.client')

@section('title', 'Nouveau ticket')
@section('subtitle', 'Créer une demande de support')

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.tickets.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux tickets
        </a>
    </div>

    <x-page-header title="Nouveau ticket" />

    <x-card>
        <form method="POST" action="{{ route('client.tickets.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Catégorie</label>
                    <select name="category_id" class="hc-select" required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="hc-label">Service concerné (optionnel)</label>
                    <select name="service_id" class="hc-select">
                        <option value="">Aucun</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Sujet</label>
                    <input type="text" name="subject" class="hc-input" value="{{ old('subject') }}" required>
                </div>
                <div>
                    <label class="hc-label">Priorité</label>
                    <select name="priority" class="hc-select" required>
                        <option value="low" @selected(old('priority') === 'low')>Basse</option>
                        <option value="medium" @selected(old('priority', 'medium') === 'medium')>Moyenne</option>
                        <option value="high" @selected(old('priority') === 'high')>Haute</option>
                        <option value="urgent" @selected(old('priority') === 'urgent')>Urgente</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Message</label>
                <textarea name="message" class="hc-textarea" rows="8" required minlength="10">{{ old('message') }}</textarea>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end;">
                <x-button :href="route('client.tickets.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                    Envoyer
                </x-button>
            </div>
        </form>
    </x-card>
@endsection