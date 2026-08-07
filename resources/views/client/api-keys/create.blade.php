@extends('layouts.client')

@section('title', 'Nouvelle clé API')
@section('subtitle', 'Créer une clé d\'accès à l\'API')

@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('client.api-keys.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux clés API
        </a>
    </div>

    <x-page-header title="Nouvelle clé API" />

    <x-card style="max-width: 600px;">
        <form method="POST" action="{{ route('client.api-keys.store') }}">
            @csrf

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Nom de la clé</label>
                <input type="text" name="name" class="hc-input" placeholder="Ex: Production server" required>
                <p style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: var(--hc-space-1);">
                    Donnez un nom explicite pour retrouver cette clé facilement.
                </p>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Date d'expiration (optionnel)</label>
                <input type="date" name="expires_at" class="hc-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>

            <div style="background: var(--hc-info-bg); border: 1px solid var(--hc-info); border-radius: var(--hc-radius); padding: var(--hc-space-3); margin-bottom: var(--hc-space-4);">
                <p style="margin: 0; font-size: var(--hc-text-sm); color: #1e40af;">
                    <i data-lucide="info" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i>
                    La clé complète sera affichée <strong>une seule fois</strong> après la création. Copiez-la et conservez-la en lieu sûr.
                </p>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end;">
                <x-button :href="route('client.api-keys.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Créer la clé
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
