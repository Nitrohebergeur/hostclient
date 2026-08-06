<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\ServiceProvisionService;
use Illuminate\Console\Command;

class TerminateServicesCommand extends Command
{
    protected $signature   = 'services:terminate';
    protected $description = 'Terminate suspended services after grace period';

    public function __construct(protected ServiceProvisionService $provisionService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days     = config('hostclient.auto_terminate_days', 14);
        $services = Service::suspended()
            ->where('suspended_at', '<', now()->subDays($days))
            ->with('user')
            ->get();

        $count = 0;

        foreach ($services as $service) {
            try {
                $this->provisionService->terminate($service, 'Auto-résilié après période de grâce');
                $this->line("Service #{$service->id} terminated");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to terminate service #{$service->id}: " . $e->getMessage());
            }
        }

        $this->info("Terminated {$count} service(s).");

        return 0;
    }
}