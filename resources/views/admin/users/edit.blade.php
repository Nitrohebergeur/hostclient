@extends('layouts.admin')

@section('title', 'Modifier ' . $user->first_name . ' ' . $user->last_name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.users.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux utilisateurs
        </a>
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; margin:0;">{{ $user->full_name }}</h1>
            <p style="color:var(--hc-text-muted); font-size:0.875rem; margin:0.25rem 0 0;">
                {{ $user->email }}
                &nbsp;·&nbsp;
                @foreach($user->roles as $role)
                    <span style="font-weight:600; color:{{ $role->name === 'admin' ? '#2563eb' : 'inherit' }}">{{ ucfirst($role->name) }}</span>
                @endforeach
            </p>
        </div>
        {{-- Bouton accéder à l'espace client de cet utilisateur --}}
        @if(!$user->hasRole('admin'))
            <a href="{{ route('admin.clients.show', $user) }}"
               style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--hc-bg-secondary,#f3f4f6); border:1px solid var(--hc-border); border-radius:0.5rem; padding:0.5rem 1rem; font-size:0.875rem; font-weight:500; text-decoration:none; color:var(--hc-text);">
                <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                Voir le profil client
            </a>
        @endif
    </div>

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem; color:#b91c1c;">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem; color:#15803d;">
            {{ session('success') }}
        </div>
    @endif

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            {{-- Identité --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem;">
                Identité
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prénom *</label>
                    <input type="text" name="first_name" class="hc-input" value="{{ old('first_name', $user->first_name) }}" required>
                </div>
                <div>
                    <label class="hc-label">Nom *</label>
                    <input type="text" name="last_name" class="hc-input" value="{{ old('last_name', $user->last_name) }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Email *</label>
                    <input type="email" name="email" class="hc-input" value="{{ old('email', $user->email) }}" required>
                </div>
                <div>
                    <label class="hc-label">Téléphone</label>
                    <input type="text" name="phone" class="hc-input" value="{{ old('phone', $user->phone) }}" placeholder="+33 6 00 00 00 00">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Entreprise</label>
                <input type="text" name="company" class="hc-input" value="{{ old('company', $user->company) }}" placeholder="Nom de l'entreprise (optionnel)">
            </div>

            {{-- Adresse --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem; margin-top:1.5rem;">
                Adresse
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Adresse</label>
                <input type="text" name="address" class="hc-input" value="{{ old('address', $user->address) }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
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
                    <input type="text" name="country" class="hc-input" value="{{ old('country', $user->country) }}" placeholder="France">
                </div>
            </div>

            {{-- Rôle & Statut --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:1rem; margin-top:1.5rem;">
                Rôle & Statut
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Rôle *</label>
                    <select name="role" class="hc-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                                {{ ucfirst($role->name) }}
                                @if($role->name === 'admin') — Accès complet
                                @elseif($role->name === 'client') — Espace client
                                @elseif($role->name === 'support') — Support
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @if($user->id === auth()->id())
                        <p style="font-size:0.75rem; color:#f59e0b; margin-top:0.25rem;">
                            ⚠️ Vous ne pouvez pas changer votre propre rôle.
                        </p>
                    @endif
                </div>
                <div style="display:flex; align-items:flex-end; padding-bottom:0.25rem;">
                    <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))>
                        <span style="font-size: var(--hc-text-sm); font-weight: 500;">Compte actif</span>
                    </label>
                </div>
            </div>

            {{-- Changer le mot de passe --}}
            <div style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:var(--hc-text-muted); margin-bottom:0.5rem; margin-top:1.5rem;">
                Changer le mot de passe
            </div>
            <p style="font-size:0.8rem; color:var(--hc-text-muted); margin-bottom:1rem;">
                Laissez vide pour conserver le mot de passe actuel.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="hc-input" minlength="8" placeholder="Minimum 8 caractères">
                </div>
                <div>
                    <label class="hc-label">Confirmation</label>
                    <input type="password" name="new_password_confirmation" class="hc-input">
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.users.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer les modifications</x-button>
            </div>
        </form>
    </x-card>
@endsection
