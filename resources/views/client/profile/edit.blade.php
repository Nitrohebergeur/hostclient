@extends('layouts.client')

@section('title', 'Mon profil')
@section('subtitle', 'Gérer vos informations personnelles')

@section('content')
    <x-page-header title="Mon profil" />

    <div style="display: grid; grid-template-columns: 1fr; gap: var(--hc-space-6); max-width: 800px;">

        <x-card header="Informations personnelles">
            <form method="POST" action="{{ route('client.profile.update') }}">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                    <div>
                        <label class="hc-label">Prénom</label>
                        <input type="text" name="first_name" class="hc-input" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>
                    <div>
                        <label class="hc-label">Nom</label>
                        <input type="text" name="last_name" class="hc-input" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>

                <div style="margin-bottom: var(--hc-space-4);">
                    <label class="hc-label">Email</label>
                    <input type="email" name="email" class="hc-input" value="{{ old('email', $user->email) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                    <div>
                        <label class="hc-label">Téléphone</label>
                        <input type="text" name="phone" class="hc-input" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div>
                        <label class="hc-label">Société</label>
                        <input type="text" name="company" class="hc-input" value="{{ old('company', $user->company) }}">
                    </div>
                </div>

                <div style="margin-bottom: var(--hc-space-4);">
                    <label class="hc-label">Adresse</label>
                    <input type="text" name="address" class="hc-input" value="{{ old('address', $user->address) }}">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                    <div>
                        <label class="hc-label">Ville</label>
                        <input type="text" name="city" class="hc-input" value="{{ old('city', $user->city) }}">
                    </div>
                    <div>
                        <label class="hc-label">Code postal</label>
                        <input type="text" name="postal_code" class="hc-input" value="{{ old('postal_code', $user->postal_code) }}">
                    </div>
                    <div>
                        <label class="hc-label">Pays</label>
                        <input type="text" name="country" class="hc-input" value="{{ old('country', $user->country) }}" maxlength="2" placeholder="FR">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                    <x-button type="submit" variant="primary">Enregistrer</x-button>
                </div>
            </form>
        </x-card>

        <x-card header="Changer le mot de passe">
            <form method="POST" action="{{ route('client.profile.password') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: var(--hc-space-4);">
                    <label class="hc-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="hc-input" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                    <div>
                        <label class="hc-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="hc-input" required minlength="8">
                    </div>
                    <div>
                        <label class="hc-label">Confirmation</label>
                        <input type="password" name="password_confirmation" class="hc-input" required>
                    </div>
                </div>

                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">
                    Minimum 8 caractères, avec majuscules, minuscules et chiffres.
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                    <x-button type="submit" variant="primary">Mettre à jour le mot de passe</x-button>
                </div>
            </form>
        </x-card>

        <x-card>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: var(--hc-space-4);">
                <div>
                    <h3 style="font-size: var(--hc-text-base); font-weight: 600; margin: 0 0 var(--hc-space-1);">Supprimer le compte</h3>
                    <p style="font-size: var(--hc-text-sm); color: var(--hc-text-muted); margin: 0;">
                        Cette action est irréversible. Toutes vos données seront définitivement supprimées.
                    </p>
                </div>
                <form method="POST" action="{{ route('client.profile.destroy') }}" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="password" value="" id="delete-password-field">
                    <x-button type="button" variant="danger" onclick="if(confirm('Confirmez avec votre mot de passe :')) { var p = prompt('Votre mot de passe :'); if (p) { document.getElementById('delete-password-field').value = p; this.closest('form').submit(); } }">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer mon compte
                    </x-button>
                </form>
            </div>
        </x-card>
    </div>
@endsection
