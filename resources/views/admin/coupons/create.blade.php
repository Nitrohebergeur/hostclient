@extends('layouts.admin')

@section('title', 'Nouveau coupon')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.coupons.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux coupons
        </a>
    </div>

    <x-page-header title="Nouveau coupon" />

    <x-card style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Code</label>
                    <input type="text" name="code" class="hc-input" placeholder="EX: PROMO2026" required style="text-transform: uppercase;">
                </div>
                <div>
                    <label class="hc-label">Type de remise</label>
                    <select name="type" class="hc-select" required>
                        <option value="percentage">Pourcentage (%)</option>
                        <option value="fixed">Montant fixe (€)</option>
                        <option value="free_setup">Frais d'installation offerts</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Description</label>
                <textarea name="description" class="hc-textarea" rows="2"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Valeur</label>
                    <input type="number" step="0.01" name="value" class="hc-input" value="0" required>
                </div>
                <div>
                    <label class="hc-label">Commande minimum (€)</label>
                    <input type="number" step="0.01" name="minimum_order" class="hc-input" value="0">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Utilisations max</label>
                    <input type="number" name="max_uses" class="hc-input" placeholder="Illimité">
                </div>
                <div>
                    <label class="hc-label">Max par utilisateur</label>
                    <input type="number" name="max_uses_per_user" class="hc-input" value="1">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Début de validité</label>
                    <input type="date" name="starts_at" class="hc-input">
                </div>
                <div>
                    <label class="hc-label">Fin de validité</label>
                    <input type="date" name="expires_at" class="hc-input">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Coupon actif</span>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.coupons.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Créer le coupon</x-button>
            </div>
        </form>
    </x-card>
@endsection