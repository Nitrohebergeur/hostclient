@extends('layouts.admin')

@section('title', '#' . $ticket->ticket_number . ' — ' . $ticket->subject)
@section('content')
    <div style="margin-bottom: var(--hc-space-4);">
        <a href="{{ route('admin.tickets.index') }}" style="color: var(--hc-text-muted); text-decoration: none; font-size: var(--hc-text-sm); display: inline-flex; align-items: center; gap: var(--hc-space-2);">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            Retour aux tickets
        </a>
    </div>

    <x-page-header title="{{ $ticket->subject }}">
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

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--hc-space-6);" class="hc-detail-grid">
        <div style="display: flex; flex-direction: column; gap: var(--hc-space-4);">
            @foreach($ticket->replies as $reply)
                <x-card padding="false">
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); border-bottom: 1px solid var(--hc-border); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: var(--hc-space-3);">
                            <div style="width: 36px; height: 36px; background: {{ $reply->is_staff ? 'var(--hc-primary)' : 'var(--hc-gray-200)' }}; color: {{ $reply->is_staff ? 'var(--hc-text-inverse)' : 'var(--hc-text)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ strtoupper(substr($reply->user->first_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: var(--hc-text-sm);">
                                    {{ $reply->user->first_name ?? '' }} {{ $reply->user->last_name ?? '' }}
                                    @if($reply->is_staff)
                                        <x-badge variant="info" style="margin-left: var(--hc-space-2);">Support</x-badge>
                                    @endif
                                </div>
                                <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $reply->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: var(--hc-space-4) var(--hc-space-5); white-space: pre-wrap; font-size: var(--hc-text-sm); line-height: 1.6;">{{ $reply->message }}</div>
                </x-card>
            @endforeach

            <x-card header="Répondre">
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

                    <textarea name="message" class="hc-textarea" rows="6" required minlength="2" placeholder="Votre réponse au client..."></textarea>
                    <div style="margin-top: var(--hc-space-3); display: flex; justify-content: flex-end;">
                        <x-button type="submit" variant="primary">
                            <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                            Envoyer
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div style="display: flex; flex-direction: column; gap: var(--hc-space-4);">

            <x-card header="Client">
                @if($ticket->user)
                    <div style="display: flex; align-items: center; gap: var(--hc-space-3); margin-bottom: var(--hc-space-3);">
                        <div style="width: 40px; height: 40px; background: var(--hc-primary-50); color: var(--hc-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ strtoupper(substr($ticket->user->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: var(--hc-text-sm);">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                            <div style="font-size: var(--hc-text-xs); color: var(--hc-text-muted);">{{ $ticket->user->email }}</div>
                        </div>
                    </div>
                    <x-button :href="route('admin.clients.show', $ticket->user)" variant="secondary" size="sm" style="width: 100%;">
                        Voir le client
                    </x-button>
                @endif
            </x-card>

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
                    <x-button type="submit" variant="secondary" style="width: 100%;">Assigner</x-button>
                </form>
            </x-card>

            <x-card header="Informations">
                <dl style="margin: 0; font-size: var(--hc-text-sm);">
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">N° de ticket</dt>
                        <dd style="margin: 0; font-family: var(--hc-font-mono); font-weight: 500;">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Catégorie</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    @if($ticket->service)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Service</dt>
                            <dd style="margin: 0; font-weight: 500;">
                                <a href="{{ route('admin.services.show', $ticket->service) }}" style="color: var(--hc-primary); text-decoration: none;">{{ $ticket->service->name }}</a>
                            </dd>
                        </div>
                    @endif
                    @if($ticket->assignedTo)
                        <div style="padding: var(--hc-space-2) 0;">
                            <dt style="color: var(--hc-text-muted);">Assigné à</dt>
                            <dd style="margin: 0; font-weight: 500;">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</dd>
                        </div>
                    @endif
                    <div style="padding: var(--hc-space-2) 0;">
                        <dt style="color: var(--hc-text-muted);">Créé le</dt>
                        <dd style="margin: 0; font-weight: 500;">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card>
                <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Supprimer ce ticket ?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" style="width: 100%;">
                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        Supprimer
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .hc-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
