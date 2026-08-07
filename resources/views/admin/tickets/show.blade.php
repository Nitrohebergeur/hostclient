@extends('layouts.admin')

@section('title', '#' . $ticket->ticket_number . ' — ' . $ticket->subject)
@section('content')
    <div class="hc-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <a href="{{ route('admin.tickets.index') }}">Tickets</a>
        <i data-lucide="chevron-right" class="hc-breadcrumb-sep" style="width: 14px; height: 14px;"></i>
        <span class="hc-breadcrumb-current">{{ $ticket->ticket_number }}</span>
    </div>

    <x-page-header :title="$ticket->subject" :subtitle="'Ticket #' . $ticket->ticket_number . ' · ouvert par ' . ($ticket->user?->first_name ?? '—')">
        <x-slot:actions>
            <x-badge :variant="match($ticket->priority) {
                'urgent' => 'danger',
                'high' => 'warning',
                'medium' => 'info',
                'low' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst($ticket->priority) }}</x-badge>
            <x-badge :variant="match($ticket->status) {
                'open' => 'warning',
                'in_progress' => 'info',
                'waiting_customer' => 'warning',
                'waiting_staff' => 'info',
                'closed' => 'neutral',
                default => 'neutral'
            }">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div class="hc-info-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-4);">
            @forelse($ticket->replies as $reply)
                <x-card :padding="false">
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); border-bottom: 1px solid var(--hc-border); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                            <div class="hc-avatar {{ $reply->is_staff ? 'hc-avatar-primary' : 'hc-avatar-neutral' }}" style="background: {{ $reply->is_staff ? 'var(--hc-primary)' : 'var(--hc-gray-200)' }}; color: {{ $reply->is_staff ? 'var(--hc-text-inverse)' : 'var(--hc-text)' }};">
                                {{ strtoupper(substr($reply->user->first_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm); display: flex; align-items: center; gap: var(--hc-space-2);">
                                    {{ $reply->user->first_name ?? '' }} {{ $reply->user->last_name ?? '' }}
                                    @if($reply->is_staff)
                                        <x-badge variant="info">Support</x-badge>
                                    @else
                                        <x-badge variant="neutral">Client</x-badge>
                                    @endif
                                </div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $reply->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); white-space: pre-wrap; font-size: var(--hc-text-sm); line-height: 1.6; color: var(--hc-text);">{{ $reply->message }}</div>
                </x-card>
            @empty
                <x-card>
                    <x-empty-state title="Aucun message" description="Ce ticket ne contient aucun message." icon="💬" />
                </x-card>
            @endforelse

            <x-card header="Répondre au ticket">
                <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--hc-space-4); margin-bottom: var(--hc-space-4);">
                        <div>
                            <label class="hc-label">Statut</label>
                            <select name="status" class="hc-select">
                                @foreach(['open', 'in_progress', 'waiting_customer', 'waiting_staff', 'closed'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $ticket->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="hc-label">Priorité</label>
                            <select name="priority" class="hc-select">
                                @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                    <option value="{{ $priority }}" @selected(old('priority', $ticket->priority) === $priority)>{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: var(--hc-space-3);">
                        <label class="hc-label">Votre réponse</label>
                        <textarea name="message" class="hc-textarea" rows="6" required minlength="2" placeholder="Répondez au client..."></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <x-button type="submit" variant="primary">
                            <i data-lucide="send" style="width: 14px; height: 14px;"></i>
                            Envoyer la réponse
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-6);">

            @if($ticket->user)
            <x-card>
                <x-slot:header>
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                        <div class="hc-avatar hc-avatar-primary">{{ strtoupper(substr($ticket->user->first_name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <h3 style="margin: 0; font-size: var(--hc-text-sm); font-weight: 600;">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</h3>
                            <p style="margin: 2px 0 0;">{{ $ticket->user->email }}</p>
                        </div>
                    </div>
                </x-slot:header>
                <x-button :href="route('admin.clients.show', $ticket->user)" variant="secondary" style="width: 100%;">
                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                    Voir le client
                </x-button>
            </x-card>
            @endif

            <x-card header="Assigner à">
                <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                    @csrf
                    <select name="user_id" class="hc-select" style="margin-bottom: var(--hc-space-3);">
                        <option value="">— Non assigné —</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" @selected($ticket->assigned_to === $member->id)>
                                {{ $member->first_name }} {{ $member->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-button type="submit" variant="secondary" style="width: 100%;">
                        <i data-lucide="user-check" style="width: 14px; height: 14px;"></i>
                        Assigner
                    </x-button>
                </form>
            </x-card>

            <x-card header="Informations">
                <dl class="hc-dl">
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">N° de ticket</dt>
                        <dd style="font-family: var(--hc-font-mono); font-weight: 600;">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Catégorie</dt>
                        <dd class="hc-dl-value">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    @if($ticket->service)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Service</dt>
                            <dd class="hc-dl-value">
                                <a href="{{ route('admin.services.show', $ticket->service) }}" style="color: var(--hc-primary);">{{ $ticket->service->name }}</a>
                            </dd>
                        </div>
                    @endif
                    @if($ticket->assignedTo)
                        <div class="hc-dl-row">
                            <dt class="hc-dl-label">Assigné à</dt>
                            <dd class="hc-dl-value">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</dd>
                        </div>
                    @endif
                    <div class="hc-dl-row">
                        <dt class="hc-dl-label">Créé le</dt>
                        <dd class="hc-dl-value">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card header="Zone dangereuse">
                <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Supprimer définitivement ce ticket ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                        Supprimer le ticket
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
@endsection