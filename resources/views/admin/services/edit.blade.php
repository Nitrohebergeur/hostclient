@extends('layouts.admin')

@section('title', 'Modifier ' . $service->name)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.services.show', $service) }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour au service
        </a>
    </div>

    <x-page-header title="Modifier le service" />

    <x-card style="max-width: 800px;">
        <form method="POST" action="{{ route('admin.services.update', $service) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Nom du service</label>
                <input type="text" name="name" class="hc-input" value="{{ old('name', $service->name) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                <div>
                    <label class="hc-label">Prix</label>
                    <input type="number" step="0.01" name="price" class="hc-input" value="{{ old('price', $service->price) }}" required>
                </div>
                <div>
                    <label class="hc-label">Cycle de facturation</label>
                    <select name="billing_cycle" class="hc-select" required>
                        @foreach(['monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially'] as $cycle)
                            <option value="{{ $cycle }}" @selected(old('billing_cycle', $service->billing_cycle) === $cycle)>{{ $cycle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Prochaine échéance</label>
                <input type="date" name="next_due_date" class="hc-input" value="{{ old('next_due_date', $service->next_due_date?->format('Y-m-d')) }}">
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label style="display: flex; align-items: center; gap: var(--hc-space-3); cursor: pointer;">
                    <input type="hidden" name="auto_renew" value="0">
                    <input type="checkbox" name="auto_renew" value="1" @checked(old('auto_renew', $service->auto_renew)) style="width: 18px; height: 18px;">
                    <span style="font-size: var(--hc-text-sm);">Renouvellement automatique</span>
                </label>
            </div>

            <div style="margin-bottom: var(--hc-space-4);">
                <label class="hc-label">Notes internes</label>
                <textarea name="notes" class="hc-textarea" rows="4">{{ old('notes', $service->notes) }}</textarea>
            </div>

            <div style="display: flex; gap: var(--hc-space-3); justify-content: flex-end; padding-top: var(--hc-space-3); border-top: 1px solid var(--hc-border);">
                <x-button :href="route('admin.services.show', $service)" variant="ghost">Annuler</x-button>
                <x-button type="submit" variant="primary">Enregistrer</x-button>
            </div>
        </form>
    </x-card>
@endsection
