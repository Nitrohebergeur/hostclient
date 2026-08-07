@extends('layouts.admin')

@section('title', $user->first_name . ' ' . $user->last_name)
@section('content')

    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--hc-text-muted); text-decoration:none; font-size:0.875rem; display:inline-flex; align-items:center; gap:0.4rem;">
            <i data-lucide="arrow-left" style="width:14px; height:14px;"></i>
            Retour aux utilisateurs
        </a>
    </div>

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div style="display:flex; align-items:center; gap:1rem;">
            <div style="width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.25rem; flex-shrink:0;
                background: {{ $user->hasRole('admin') ? '#2563eb' : 'var(--hc-primary-50)' }};
                color: {{ $user->hasRole('admin') ? 'white' : 'var(--hc-primary)' }};">
                {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
            </div>
            <div>
                <h1 style="font-size:1.5rem; font-weight:700; margin:0;">{{ $user->full_name }}</h1>
                <div style="display:flex; align-items:center; gap:0.75rem; margin-top:0.25rem; flex-wrap:wrap;">
                    <span style="color:var(--hc-text-muted); font-size:0.875rem;">{{ $user->email }}</span>
                    @foreach($user->roles as $role)
                        <span style="padding:0.15rem 0.5rem; border-radius:999px; font-size:0.7rem; font-weight:600;
                            background: {{ $role->name === 'admin' ? '#dbeafe' : '#f3f4f6' }};
                            color: {{ $role->name === 'admin' ? '#1d4ed8' : '#374151' }};">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                    <span style="padding:0.15rem 0.5rem; border-radius:999px; font-size:0.7rem; font-weight:600;
                        background: {{ ($user->is_active ?? true) ? '#dcfce7' : '#f3f4f6' }};
                        color: {{ ($user->is_active ?? true) ? '#16a34a' : '#6b7280' }};">
                        {{ ($user->is_active ?? true) ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="hc-btn hc-btn-primary">
            <i data-lucide="edit" style="width:16px; height:16px;"></i>
            Modifier
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-alert>
    @endif

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:var(--hc-primary);">{{ $stats['services'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Services</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#2563eb;">{{ $stats['invoices'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Factures</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#d97706;">{{ $stats['tickets'] }}</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Tickets</div>
        </div>
        <div style="background:var(--hc-bg-elevated); border:1px solid var(--hc-border); border-radius:0.75rem; padding:1.25rem; text-align:center;">
            <div style="font-size:1.75rem; font-weight:800; color:#16a34a;">{{ number_format($stats['total_paid'], 2) }}€</div>
            <div style="font-size:0.8rem; color:var(--hc-text-muted); margin-top:0.25rem;">Total payé</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

        {{-- Colonne gauche --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Informations --}}
            <x-card header="Informations">
                <dl style="display:flex; flex-direction:column; gap:0.75rem;">
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Prénom</dt>
                        <dd style="font-weight:500; font-size:0.875rem;">{{ $user->first_name }}</dd>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Nom</dt>
                        <dd style="font-weight:500; font-size:0.875rem;">{{ $user->last_name }}</dd>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Email</dt>
                        <dd style="font-weight:500; font-size:0.875rem; font-family:monospace;">{{ $user->email }}</dd>
                    </div>
                    @if($user->phone)
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Téléphone</dt>
                        <dd style="font-weight:500; font-size:0.875rem;">{{ $user->phone }}</dd>
                    </div>
                    @endif
                    @if($user->company)
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Entreprise</dt>
                        <dd style="font-weight:500; font-size:0.875rem;">{{ $user->company }}</dd>
                    </div>
                    @endif
                    @if($user->address)
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Adresse</dt>
                        <dd style="font-weight:500; font-size:0.875rem; text-align:right;">
                            {{ $user->address }}<br>
                            {{ $user->postal_code }} {{ $user->city }}<br>
                            {{ $user->country }}
                        </dd>
                    </div>
                    @endif
                    <div style="display:flex; justify-content:space-between;">
                        <dt style="color:var(--hc-text-muted); font-size:0.875rem;">Inscrit le</dt>
                        <dd style="font-weight:500; font-size:0.875rem;">{{ $user->created_at?->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>
            </x-card>

            {{-- Services --}}
            <x-card header="Services" :padding="false">
                @forelse($user->services as $service)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.875rem 1.25rem; border-bottom:1px solid var(--hc-border);">
                        <div>
                            <a href="{{ route('admin.services.show', $service) }}" style="font-weight:600; font-size:0.875rem; color:var(--hc-text); text-decoration:none;">
                                {{ $service->name }}
                            </a>
                            <div style="font-size:0.75rem; color:var(--hc-text-muted); margin-top:2px;">
                                {{ $service->product?->name ?? '—' }} · {{ number_format($service->price ?? 0, 2) }}€/{{ $service->billing_cycle ?? 'mois' }}
                            </div>
                        </div>
                        <span style="padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600;
                            background:{{ match($service->status) { 'active'=>'#dcfce7','suspended'=>'#fee2e2','pending'=>'#fef9c3',default=>'#f3f4f6' } }};
                            color:{{ match($service->status) { 'active'=>'#16a34a','suspended'=>'#dc2626','pending'=>'#b45309',default=>'#6b7280' } }};">
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>
                @empty
                    <div style="padding:2rem; text-align:center; color:var(--hc-text-muted); font-size:0.875rem;">Aucun service</div>
                @endforelse
            </x-card>
        </div>

        {{-- Colonne droite --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Factures --}}
            <x-card header="Factures récentes" :padding="false">
                @forelse($user->invoices->take(5) as $invoice)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.875rem 1.25rem; border-bottom:1px solid var(--hc-border);">
                        <div>
                            <a href="{{ route('admin.invoices.show', $invoice) }}" style="font-weight:600; font-size:0.875rem; font-family:monospace; color:var(--hc-text); text-decoration:none;">
                                {{ $invoice->invoice_number ?? '#'.$invoice->id }}
                            </a>
                            <div style="font-size:0.75rem; color:var(--hc-text-muted);">{{ $invoice->created_at?->format('d/m/Y') }}</div>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <span style="font-weight:700; font-size:0.875rem;">{{ number_format($invoice->total ?? 0, 2) }}€</span>
                            <span style="padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600;
                                background:{{ match($invoice->status) { 'paid'=>'#dcfce7','unpaid'=>'#fee2e2','cancelled'=>'#f3f4f6',default=>'#fef9c3' } }};
                                color:{{ match($invoice->status) { 'paid'=>'#16a34a','unpaid'=>'#dc2626','cancelled'=>'#6b7280',default=>'#b45309' } }};">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem; text-align:center; color:var(--hc-text-muted); font-size:0.875rem;">Aucune facture</div>
                @endforelse
            </x-card>

            {{-- Tickets --}}
            <x-card header="Tickets récents" :padding="false">
                @forelse($user->tickets->take(5) as $ticket)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.875rem 1.25rem; border-bottom:1px solid var(--hc-border);">
                        <div>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" style="font-weight:600; font-size:0.875rem; color:var(--hc-text); text-decoration:none;">
                                {{ Str::limit($ticket->subject, 40) }}
                            </a>
                            <div style="font-size:0.75rem; color:var(--hc-text-muted);">{{ $ticket->created_at?->format('d/m/Y') }}</div>
                        </div>
                        <span style="padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600;
                            background:{{ match($ticket->status ?? 'open') { 'open'=>'#fef9c3','closed'=>'#f3f4f6','in_progress'=>'#dbeafe',default=>'#f3f4f6' } }};
                            color:{{ match($ticket->status ?? 'open') { 'open'=>'#b45309','closed'=>'#6b7280','in_progress'=>'#1d4ed8',default=>'#6b7280' } }};">
                            {{ ucfirst($ticket->status ?? 'open') }}
                        </span>
                    </div>
                @empty
                    <div style="padding:2rem; text-align:center; color:var(--hc-text-muted); font-size:0.875rem;">Aucun ticket</div>
                @endforelse
            </x-card>

            {{-- Actions --}}
            <x-card header="Actions">
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    <a href="{{ route('admin.users.edit', $user) }}" class="hc-btn hc-btn-secondary" style="justify-content:center;">
                        <i data-lucide="edit" style="width:16px; height:16px;"></i>
                        Modifier les informations
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement {{ $user->full_name }} ? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hc-btn hc-btn-ghost" style="width:100%; justify-content:center; color:var(--hc-danger); border-color:var(--hc-danger);">
                                <i data-lucide="trash-2" style="width:16px; height:16px;"></i>
                                Supprimer l'utilisateur
                            </button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
@endsection
