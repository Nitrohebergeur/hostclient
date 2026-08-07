@extends('layouts.admin')

@section('title', 'Nouveau client')
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.clients.index') }}">Clients</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">Nouveau client</span>
    </div>

    <x-page-header title="Nouveau client" subtitle="Créez un compte client pour votre plateforme" />

    @if($errors->any())
        <x-alert type="danger">
            <strong>Le formulaire contient des erreurs :</strong>
            <ul style="margin: var(--hc-space-2) 0 0 var(--hc-space-5);">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.clients.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Prénom <span style="color: var(--hc-danger);">*</span></label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name') }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom <span style="color: var(--hc-danger);">*</span></label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-5);">
                <label class="hc-label">Email <span style="color: var(--hc-danger);">*</span></label>
                <input type="email" name="email" class="hc-input" value="{{ old('email') }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="hc-label">Société</label>
                    <input type="text" name="company" class="hc-input" value="{{ old('company') }}">
                </div>
                <div>
                    <label class="hc-label">Pays (code ISO)</label>
                    <input type="text" name="country" class="hc-input" value="{{ old('country', 'FR') }}" maxlength="2">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Mot de passe <span style="color: var(--hc-danger);">*</span></label>
                    <input type="password" name="password" class="hc-input" required minlength="8">
                    <p style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: 4px;">Minimum 8 caractères</p>
                </div>
                <div>
                    <label class="hc-label">Confirmation <span style="color: var(--hc-danger);">*</span></label>
                    <input type="password" name="password_confirmation" class="hc-input" required minlength="8">
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.clients.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="user-plus" style="width: 14px; height: 14px;"></i>
                    Créer le client
                </x-button>
            </div>
        </form>
    </x-card>
@endsection