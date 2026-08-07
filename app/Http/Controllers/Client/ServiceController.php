<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Gestion des services du client (hébergement, VPS, domaines, etc.)
 */
class ServiceController extends Controller
{
    /**
     * Liste tous les services du client connecté.
     */
    public function index(Request $request): View
    {
        // $services = Service::where('user_id', auth()->id())
        //     ->with(['product', 'invoices'])
        //     ->latest()
        //     ->paginate(12);

        $services = collect(); // Placeholder — sera remplacé par la vraie requête

        return view('client.services.index', compact('services'));
    }

    /**
     * Affiche le détail d'un service.
     */
    public function show(Request $request, int $service): View
    {
        // $service = Service::where('user_id', auth()->id())
        //     ->where('id', $service)
        //     ->firstOrFail();

        return view('client.services.show');
    }

    /**
     * Lance le renouvellement d'un service.
     */
    public function renew(Request $request, int $service): RedirectResponse
    {
        // Logique de renouvellement via ServiceRenewalJob
        return redirect()->route('client.invoices.index')
            ->with('success', 'Une facture de renouvellement a été générée.');
    }

    /**
     * Suspendre un service.
     */
    public function suspend(Request $request, int $service): RedirectResponse
    {
        // ServiceSuspendJob::dispatch($service);
        return back()->with('success', 'Votre service a été suspendu.');
    }

    /**
     * Résilier un service.
     */
    public function cancel(Request $request, int $service): RedirectResponse
    {
        // ServiceCancelJob::dispatch($service);
        return redirect()->route('client.services.index')
            ->with('success', 'Votre service a été résilié.');
    }
}
