@extends('layouts.admin')

@section('title', 'Modifier ' . $paymentGateway->name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.payment-gateways.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux passerelles
        </a>
    </div>

    <x-page-header title="Modifier {{ $paymentGateway->name }}" />

    <x-card style="max-width: 700px;">
        <form method="POST" action="{{ route('admin.payment-gateways.update', $paymentGateway) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Nom affiché</label>
                <input type="text" name="name" class="hc-input" value="{{ old('name', $paymentGateway->name) }}" required>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Provider</label>
                <input type="text" class="hc-input" value="{{ $paymentGateway->provider }}" disabled>
                <p style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: var(--hc-space-1);">Le provider ne peut pas être modifié.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" class="hc-input" value="{{ old('sort_order', $paymentGateway->sort_order ?? 0) }}">
                </div>
                <div style="display: flex; align-items: end;">
                    <label style="display: flex; align-items: center; gap: var(--hc-space-2); cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $paymentGateway->is_active))>
                        <span style="font-size: var(--hc-text-sm); font-weight: 500;">Passerelle active</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.payment-gateways.index')" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer</x-button>
            </div>
        </form>
    </x-card>
@endsection