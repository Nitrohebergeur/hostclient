@extends('layouts.admin')

@section('title', 'Modifier ' . $coupon->code)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.coupons.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux coupons
        </a>
    </div>

    <x-page-header title="Modifier le coupon {{ $coupon->code }}" />

    <x-card style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Code</label>
                <input type="text" class="hc-input" value="{{ $coupon->code }}" disabled style="font-family: var(--hc-font-mono);">
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Description</label>
                <textarea name="description" class="hc-textarea" rows="2">{{ old('description', $coupon->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Type</label>
                    <select name="type" class="hc-select" required>
                        <option value="percentage" @selected(old('type', $coupon->type) === 'percentage')>Pourcentage</option>
                        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Montant fixe</option>
                        <option value="free_setup" @selected(old('type', $coupon->type) === 'free_setup')>Setup offert</option>
                    </select>
                </div>
                <div>
                    <label class="hc-label">Valeur</label>
                    <input type="number" step="0.01" name="value" class="hc-input" value="{{ old('value', $coupon->value) }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Max utilisations</label>
                    <input type="number" name="max_uses" class="hc-input" value="{{ old('max_uses', $coupon->max_uses) }}">
                </div>
                <div>
                    <label class="hc-label">Max / utilisateur</label>
                    <input type="number" name="max_uses_per_user" class="hc-input" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user ?? 1) }}">
                </div>
                <div>
                    <label class="hc-label">Commande min. (€)</label>
                    <input type="number" step="0.01" name="minimum_order" class="hc-input" value="{{ old('minimum_order', $coupon->minimum_order ?? 0) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Début</label>
                    <input type="date" name="starts_at" class="hc-input" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="hc-label">Fin</label>
                    <input type="date" name="expires_at" class="hc-input" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active))>
                    <span style="font-size: var(--hc-text-sm); font-weight: 500;">Coupon actif</span>
                </label>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.coupons.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer</x-button>
            </div>
        </form>
    </x-card>
@endsection