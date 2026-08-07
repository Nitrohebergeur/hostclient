@extends('layouts.admin')

@section('title', 'Nouvelle passerelle')
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.payment-gateways.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux passerelles
        </a>
    </div>

    <x-page-header title="Nouvelle passerelle" />

    <x-card style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.payment-gateways.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Nom affiché</label>
                    <input type="text" name="name" class="hc-input" placeholder="Ex: Stripe Production" required>
                </div>
                <div>
                    <label class="hc-label">Provider</label>
                    <select name="provider" class="hc-select" required>
                        <option value="stripe">Stripe</option>
                        <option value="paypal">PayPal</option>
                        <option value="mollie">Mollie</option>
                        <option value="bank_transfer">Virement bancaire</option>
                        <option value="balance">Solde client</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Ordre d'affichage</label>
                <input type="number" name="sort_order" class="hc-input" value="0">
            </div>

            <div style="margin-bottom: var(--hc-space-4); padding: var(--hc-space-4); background: var(--hc-info-bg); border: 1px solid var(--hc-info); border-radius: var(--hc-radius);">
                <p style="margin: 0; font-size: var(--hc-text-sm); color: #1e40af;">
                    <i data-lucide="info" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i>
                    Les clés API se configurent via le fichier <code>.env</code>, pas dans l'admin.
                </p>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.payment-gateways.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Créer</x-button>
            </div>
        </form>
    </x-card>
@endsection