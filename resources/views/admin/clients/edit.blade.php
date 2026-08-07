@extends('layouts.admin')

@section('title', 'Modifier ' . $client->first_name . ' ' . $client->last_name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.clients.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux clients
        </a>
    </div>

    <x-page-header title="Modifier le client" />

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prénom</label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name', $client->first_name) }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom</label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name', $client->last_name) }}" required>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Email</label>
                <input type="email" name="email" class="hc-input" value="{{ old('email', $client->email) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone', $client->phone) }}">
                </div>
                <div>
                    <label class="hc-label">Société</label>
                    <input type="text" name="company" class="hc-input" value="{{ old('company', $client->company) }}">
                </div>
                <div>
                    <label class="hc-label">Pays</label>
                    <input type="text" name="country" class="hc-input" value="{{ old('country', $client->country) }}" maxlength="2">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-3); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client->is_active ?? true)) style="width: 18px; height: 18px;">
                    <span style="font-size: var(--hc-text-sm);">Compte actif</span>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.clients.show', $client)" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer</x-button>
            </div>
        </form>
    </x-card>
@endsection
