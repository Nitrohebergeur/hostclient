<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()
            ->tickets()
            ->with(['category', 'lastReplyBy'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return response()->json($tickets);
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

        $ticket = $request->user()->tickets()->create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'category_id'   => $validated['category_id'],
            'service_id'    => $validated['service_id'] ?? null,
            'subject'       => $validated['subject'],
            'priority'      => $validated['priority'],
            'status'        => 'open',
        ]);

        $ticket->replies()->create([
            'user_id'  => $request->user()->id,
            'message'  => $validated['message'],
            'is_staff' => false,
        ]);

        $ticket->update(['last_reply_at' => now(), 'last_reply_by' => $request->user()->id]);

        return response()->json($ticket->load('category'), 201);
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['category', 'replies.user', 'service']);

        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        // Clients can only update certain fields
        $validated = $request->validate([
            'priority' => 'sometimes|in:low,medium,high,urgent',
        ]);

        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        return response()->json([
            'message' => 'La suppression de tickets n\'est pas autorisée.',
        ], 403);
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

        return response()->json([
            'message' => 'Réponse envoyée.',
            'ticket'  => $ticket->fresh(['replies']),
        ]);
    }

    public function close(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->close();

        return response()->json([
            'message' => 'Ticket fermé.',
            'ticket'  => $ticket->fresh(),
        ]);
    }
}
