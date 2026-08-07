<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Gestion du support client via le système de tickets.
 */
class TicketController extends Controller
{
    /**
     * Liste les tickets du client.
     */
    public function index(Request $request): View
    {
        return view('client.tickets.index');
    }

    /**
     * Formulaire de création d'un ticket.
     */
    public function create(): View
    {
        return view('client.tickets.create');
    }

    /**
     * Enregistre un nouveau ticket.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject'     => ['required', 'string', 'min:5', 'max:255'],
            'category'    => ['required', 'string', 'in:billing,technical,sales,abuse,other'],
            'priority'    => ['required', 'string', 'in:low,normal,high'],
            'message'     => ['required', 'string', 'min:30'],
            'service_id'  => ['nullable', 'integer'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:png,jpg,jpeg,pdf,txt,log'],
        ]);

        // $ticket = TicketService::create($validated, auth()->user());
        // event(new TicketCreated($ticket));

        return redirect()->route('client.tickets.index')
            ->with('success', 'Votre ticket #XXXX a été ouvert avec succès. Notre équipe vous répondra sous 4h.');
    }

    /**
     * Affiche un ticket et ses messages.
     */
    public function show(Request $request, int $ticket): View
    {
        // $ticket = Ticket::where('user_id', auth()->id())
        //     ->where('id', $ticket)
        //     ->with(['messages.user', 'attachments'])
        //     ->firstOrFail();

        return view('client.tickets.show');
    }

    /**
     * Ajoute une réponse à un ticket.
     */
    public function reply(Request $request, int $ticket): RedirectResponse
    {
        $request->validate([
            'message'       => ['required', 'string', 'min:5'],
            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:png,jpg,jpeg,pdf,txt,log'],
        ]);

        // TicketReplyService::reply($ticket, $request->message, auth()->user());

        return back()->with('success', 'Votre réponse a été envoyée.');
    }

    /**
     * Ferme un ticket.
     */
    public function close(Request $request, int $ticket): RedirectResponse
    {
        // Ticket::where('user_id', auth()->id())->where('id', $ticket)->update(['status' => 'closed']);

        return back()->with('success', 'Le ticket a été fermé.');
    }
}
