@extends('layouts.admin')

@section('title', 'Journal d\'activité')
@section('content')
    <x-page-header title="Journal d'activité" />

    @if(($activities ?? collect())->count() === 0)
        <x-card>
            <x-empty-state title="Aucune activité enregistrée" icon="📋" />
        </x-card>
    @else
        <x-card padding="false">
            <table class="hc-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Sujet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <tr>
                            <td style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($activity->causer)
                                    {{ $activity->causer->first_name }} {{ $activity->causer->last_name }}
                                @else
                                    <span style="color: var(--hc-text-muted);">Système</span>
                                @endif
                            </td>
                            <td><x-badge variant="info">{{ $activity->log_name ?? '—' }}</x-badge></td>
                            <td>{{ $activity->description }}</td>
                            <td style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">
                                @if($activity->subject_type)
                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        <div style="margin-top: var(--hc-space-6);">
            {{ $activities->links() }}
        </div>
    @endif
@endsection