@extends('layouts.admin')

@section('title', 'Paramètres')
@section('content')
    <x-page-header title="Paramètres" />

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        @forelse($settings ?? [] as $group => $items)
            <x-card header="{{ ucfirst($group) }}">
                @foreach($items as $setting)
                    <div style="margin-bottom: var(--hc-space-4);">
                        <label class="hc-label">{{ $setting->key }}</label>
                        @if(strlen($setting->value ?? '') > 100)
                            <textarea name="settings[{{ $setting->key }}]" class="hc-textarea" rows="3">{{ $setting->value }}</textarea>
                        @else
                            <input type="text" name="settings[{{ $setting->key }}]" class="hc-input" value="{{ $setting->value }}">
                        @endif
                        @if($setting->description)
                            <p style="font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-top: var(--hc-space-1);">{{ $setting->description }}</p>
                        @endif
                    </div>
                @endforeach
            </x-card>
        @empty
            <x-card>
                <x-empty-state title="Aucun paramètre" description="Les paramètres sont créés via la base de données ou le fichier .env." icon="⚙️" />
            </x-card>
        @endforelse

        @if(($settings ?? collect())->count() > 0)
            <div style="display: flex; justify-content: flex-end; margin-top: var(--hc-space-4);">
                <x-button type="submit" variant="primary">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Enregistrer tous les paramètres
                </x-button>
            </div>
        @endif
    </form>
@endsection