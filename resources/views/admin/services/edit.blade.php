@extends('layouts.admin')

@section('title', 'Modifier ' . $service->name)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.services.index') }}">Services</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.services.show', $service) }}">{{ $service->name }}</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">Modifier</span>
    </div>

    <x-page-header title="Modifier le service" :subtitle="$service->name" />

    @if($errors->any())
        <x-alert type="danger">
            <ul style="margin: var(--hc-space-2) 0 0 var(--hc-space-5);">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.services.update', $service) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: var(--hc-space-5);">
                <label class="hc-label">Nom du service <span style="color: var(--hc-danger);">*</span></label>
                <input type="text" name="name" class="hc-input" value="{{ old('name', $service->name) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-5);">
                <div>
                    <label class="hc-label">Prix (€) <span style="color: var(--hc-danger);">*</span></label>
                    <input type="number" step="0.01" name="price" class="hc-input" value="{{ old('price', $service->price) }}" required>
                </div>
                <div>
                    <label class="hc-label">Cycle de facturation</label>
                    <select name="billing_cycle" class="hc-select" required>
                        @foreach([
                            'monthly' => 'Mensuel',
                            'quarterly' => 'Trimestriel',
                            'semi_annually' => 'Semestriel',
                            'annually' => 'Annuel',
                            'biennially' => 'Biennal',
                            'triennially' => 'Triennal',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(old('billing_cycle', $service->billing_cycle) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-5);">
                <label class="hc-label">Prochaine échéance</label>
                <input type="date" name="next_due_date" class="hc-input" value="{{ old('next_due_date', $service->next_due_date?->format('Y-m-d')) }}">
            </div>

            <div style="margin-bottom: var(--hc-space-5); padding: var(--hc-space-4); background: var(--hc-gray-50); border-radius: var(--hc-radius);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-3); cursor: pointer;">
                    <input type="hidden" name="auto_renew" value="0">
                    <input type="checkbox" name="auto_renew" value="1" @checked(old('auto_renew', $service->auto_renew)) style="width: 18px; height: 18px; accent-color: var(--hc-primary);">
                    <div>
                        <div style="font-size: var(--hc-text-sm); font-weight: 600;">Renouvellement automatique</div>
                        <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">Le service sera renouvelé automatiquement à échéance</div>
                    </div>
                </label>
            </div>

            <div style="margin-bottom: var(--hc-space-5);">
                <label class="hc-label">Notes internes</label>
                <textarea name="notes" class="hc-textarea" rows="4">{{ old('notes', $service->notes) }}</textarea>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-4); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.services.show', $service)" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">
                    <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                    Enregistrer
                </x-button>
            </div>
        </form>
    </x-card>
@endsection