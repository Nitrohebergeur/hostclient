@extends('layouts.admin')

@section('title', 'Nouvel utilisateur')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.users.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux utilisateurs
        </a>
    </div>

    <x-page-header title="Nouvel utilisateur" />

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem; color:#b91c1c;">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            {{-- Identité --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem;">
                Identité
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prénom *</label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name') }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom *</label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Email *</label>
                    <input type="email" name="email" class="hc-input" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone') }}" placeholder="+33 6 00 00 00 00">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Entreprise</label>
                <input type="text" name="company" class="hc-input" value="{{ old('company') }}" placeholder="Nom de l'entreprise (optionnel)">
            </div>

            {{-- Adresse --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem; margin-top:1.5rem;">
                Adresse
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Adresse</label>
                <input type="text" name="address" class="hc-input" value="{{ old('address') }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Ville</label>
                    <input type="text" name="city" class="hc-input" value="{{ old('city') }}">
                </div>
                <div>
                    <label class="hc-label">Code postal</label>
                    <input type="text" name="postal_code" class="hc-input" value="{{ old('postal_code') }}">
                </div>
                <div>
                    <label class="hc-label">Pays</label>
                    <input type="text" name="country" class="hc-input" value="{{ old('country') }}" placeholder="France">
                </div>
            </div>

            {{-- Accès --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem; margin-top:1.5rem;">
                Accès & Rôle
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Mot de passe *</label>
                    <input type="password" name="password" class="hc-input" required minlength="8">
                </div>
                <div>
                    <label class="hc-label">Confirmation *</label>
                    <input type="password" name="password_confirmation" class="hc-input" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Rôle *</label>
                    <select name="role" class="hc-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                {{ ucfirst($role->name) }}
                                @if($role->name === 'admin') — Accès complet
                                @elseif($role->name === 'client') — Espace client
                                @elseif($role->name === 'support') — Support
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; align-items:flex-end; padding-bottom:0.25rem;">
                    <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        <span style="font-size: var(--hc-text-sm); font-weight: 500;">Compte actif</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.users.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Créer l'utilisateur</x-button>
            </div>
        </form>
    </x-card>
@endsection
