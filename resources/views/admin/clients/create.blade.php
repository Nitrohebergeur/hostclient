@extends('layouts.admin')

@section('title', 'Nouveau client')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.clients.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux clients
        </a>
    </div>

    <x-page-header title="Nouveau client" />

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.clients.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prénom</label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name') }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom</label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Email</label>
                <input type="email" name="email" class="hc-input" value="{{ old('email') }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Mot de passe</label>
                    <input type="password" name="password" class="hc-input" required minlength="8">
                </div>
                <div>
                    <label class="hc-label">Confirmation</label>
                    <input type="password" name="password_confirmation" class="hc-input" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="hc-label">Société</label>
                    <input type="text" name="company" class="hc-input" value="{{ old('company') }}">
                </div>
                <div>
                    <label class="hc-label">Pays</label>
                    <input type="text" name="country" class="hc-input" value="{{ old('country') }}" maxlength="2" placeholder="FR">
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.clients.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Créer le client
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
