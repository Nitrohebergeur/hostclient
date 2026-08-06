<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->when($request->search, fn($q, $s) => $q->where('subject', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn($q, $p) => $q->where('priority', $p))
            ->latest()
            ->paginate(20);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'category', 'assignedTo', 'replies.user', 'service']);
        $staff = User::role('admin')->get();

        return view('admin.tickets.show', compact('ticket', 'staff'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status'   => 'required|in:open,in_progress,waiting_customer,waiting_staff,closed',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Ticket mis à jour.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $ticket->assignTo($request->user_id);

        return back()->with('success', 'Ticket assigné.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket supprimé.');
    }
}
