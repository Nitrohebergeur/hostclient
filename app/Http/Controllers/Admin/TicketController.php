<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    public function index(): View
    {
        return view('admin.tickets.index');
    }

    public function show(int $ticket): View
    {
        return view('admin.tickets.show');
    }

    public function reply(Request $request, int $ticket): RedirectResponse
    {
        $request->validate([
            'message'  => ['required', 'string', 'min:5'],
            'is_private' => ['boolean'],
        ]);
        return back()->with('success', 'Réponse envoyée.');
    }

    public function assign(Request $request, int $ticket): RedirectResponse
    {
        $request->validate(['agent_id' => ['required', 'exists:users,id']]);
        return back()->with('success', 'Ticket assigné.');
    }

    public function close(int $ticket): RedirectResponse
    {
        return back()->with('success', 'Ticket fermé.');
    }

    public function destroy(int $ticket): RedirectResponse
    {
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket supprimé.');
    }
}
