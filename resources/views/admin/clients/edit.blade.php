@extends('layouts.admin')

@section('title', 'Modifier ' . $client->first_name . ' ' . $client->last_name)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.clients.index') }}">Clients</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.clients.show', $client) }}">{{ $client->first_name }} {{ $client->last_name }}</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">Modifier</span>
    </div>

    <x-page-header title="Modifier le client" :subtitle="$client->email" />

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
        <form method="POST" action="{{ route('admin.clients.update', $client) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Prénom <span style="color: var(--hc-danger);">*</span></label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name', $client->first_name) }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom <span style="color: var(--hc-danger);">*</span></label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name', $client->last_name) }}" required>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-5);">
                <label class="hc-label">Email <span style="color: var(--hc-danger);">*</span></label>
                <input type="email" name="email" class="hc-input" value="{{ old('email', $client->email) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone', $client->phone) }}">
                </div>
                <div>
                    <label class="hc-label">Société</label>
                    <input type="text" name="company" class="hc-input" value="{{ old('company', $client->company) }}">
                </div>
                <div>
                    <label class="hc-label">Pays (code ISO)</label>
                    <input type="text" name="country" class="hc-input" value="{{ old('country', $client->country) }}" maxlength="2" placeholder="FR">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-5); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-3); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client->is_active ?? true)) style="width: 18px; height: 18px; accent-color: var(--hc-primary);">
                    <div>
                        <div style="font-size: var(--hc-text-sm); font-weight: 600;">Compte actif</div>
                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">Le client peut se connecter et utiliser ses services</div>
                    </div>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.clients.show', $client)" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                    Enregistrer
                </x-button>
            </div>
        </form>
    </x-card>
@endsection