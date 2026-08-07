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

    <x-card style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prénom</label>
                    <input type="text" name="first_name" class="hc-input" required>
                </div>
                <div>
                    <label class="hc-label">Nom</label>
                    <input type="text" name="last_name" class="hc-input" required>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Email</label>
                <input type="email" name="email" class="hc-input" required>
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

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Rôle</label>
                <select name="role" class="hc-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.users.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Créer</x-button>
            </div>
        </form>
    </x-card>
@endsection