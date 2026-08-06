<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\ServiceProvisionService;
use Illuminate\Console\Command;

class SuspendServicesCommand extends Command
{
    protected $signature   = 'services:suspend';
    protected $description = 'Suspend services with overdue invoices';

    public function __construct(protected ServiceProvisionService $provisionService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days     = config('hostclient.auto_suspend_days', 7);
        $services = Service::active()
            ->whereHas('invoices', fn($q) => $q->unpaid()->where('due_date', '<', now()->subDays($days)))
            ->with('user')
            ->get();

        $count = 0;

        foreach ($services as $service) {
            try {
                $this->provisionService->suspend($service, 'Auto-suspendu pour facture impayée');
                $this->line("Service #{$service->id} suspended");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to suspend service #{$service->id}: " . $e->getMessage());
            }
        }

        $this->info("Suspended {$count} service(s).");

        return 0;
    }
}