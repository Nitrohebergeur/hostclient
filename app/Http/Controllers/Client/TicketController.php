<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\Service;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = auth()->user()->tickets()
            ->with(['category', 'lastReplyBy'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('client.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::orderBy('sort_order')->get();
        $services   = auth()->user()->services()->active()->get();

        return view('client.tickets.create', compact('categories', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            'service_id'  => 'nullable|exists:services,id',
            'subject'     => 'required|string|max:255',
            'priority'    => 'required|in:low,medium,high,urgent',
            'message'     => 'required|string|min:10',
        ]);

        $ticket = auth()->user()->tickets()->create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'category_id'   => $validated['category_id'],
            'service_id'    => $validated['service_id'] ?? null,
            'subject'       => $validated['subject'],
            'priority'      => $validated['priority'],
            'status'        => 'open',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        $ticket->update(['last_reply_at' => now(), 'last_reply_by' => auth()->id()]);

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['category', 'replies.user', 'replies.attachments', 'service']);

        return view('client.tickets.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        return back();
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        return back()->with('error', 'La suppression de tickets n\'est pas autorisée.');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $validated = $request->validate([
            'message' => 'required|string|min:2',
        ]);

        if ($ticket->isClosed()) {
            $ticket->reopen();
        }

        $ticket->addReply($validated['message'], false, false);

        return back()->with('success', 'Réponse envoyée.');
    }

    public function close(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->close();

        return back()->with('success', 'Ticket fermé.');
    }
}
