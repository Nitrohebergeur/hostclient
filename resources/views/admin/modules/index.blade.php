@extends('layouts.admin')

@section('title', 'Modules')
@section('content')
    <x-page-header title="Modules" />

    @if(empty($modules) || count($modules) === 0)
        <x-card>
            <x-empty-state
                title="Aucun module"
                description="Placez vos modules dans le dossier <code>modules/</code> à la racine du projet."
                icon="🧩"
            />
        </x-card>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--hc-space-4);">
            @foreach($modules as $module)
                <x-card>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--hc-space-3);">
                        <h3 style="font-size: var(--hc-text-base); font-weight: 600; margin: 0;">{{ $module['display'] }}</h3>
                        <x-badge :variant="$module['enabled'] ? 'success' : 'neutral'">
                            {{ $module['enabled'] ? 'Activé' : 'Désactivé' }}
                        </x-badge>
                    </div>

                    <p style="color: var(--hc-text-muted); font-size: var(--hc-text-sm); margin-bottom: var(--hc-space-3); min-height: 40px;">
                        {{ $module['description'] ?: 'Aucune description.' }}
                    </p>

                    <div style="display: flex; gap: var(--hc-space-2); font-size: var(--hc-text-xs); color: var(--hc-text-muted); margin-bottom: var(--hc-space-4);">
                        <span>v{{ $module['version'] }}</span>
                        <span>·</span>
                        <span>{{ $module['author'] }}</span>
                    </div>

                    <div style="display: flex; gap: var(--hc-space-2);">
                        @if($module['enabled'])
                            <form method="POST" action="{{ route('admin.modules.uninstall', $module['name']) }}" style="flex: 1;">
                                @csrf
                                <x-button type="submit" variant="secondary" size="sm" style="width: 100%;">
                                    Désactiver
                                </x-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.modules.install', $module['name']) }}" style="flex: 1;">
                                @csrf
                                <x-button type="submit" variant="primary" size="sm" style="width: 100%;">
                                    Activer
                                </x-button>
                            </form>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection