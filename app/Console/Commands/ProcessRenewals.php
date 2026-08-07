<?php

namespace App\Console\Commands;

use App\Services\RenewalService;
use Illuminate\Console\Command;

class ProcessRenewals extends Command
{
    protected $signature = 'renewals:process
                            {--dry-run : Simulate without creating anything}
                            {--suspend : Also suspend overdue services}
                            {--terminate : Also terminate expired services}
                            {--reminders : Also send payment reminders}';

    protected $description = 'Process automatic service renewals, suspensions, and terminations';

    public function handle(RenewalService $renewalService): int
    {
        $isDry = $this->option('dry-run');

        if ($isDry) {
            $this->warn('DRY RUN — no changes will be made.');
        }

        // Renouvellements
        $this->info('Processing due renewals…');
        if (!$isDry) {
            $count = $renewalService->processAllDueRenewals();
            $this->line("  ✓ {$count} renewal(s) processed.");
        }

        // Rappels de paiement
        if ($this->option('reminders')) {
            $this->info('Sending payment reminders…');
            if (!$isDry) {
                $renewalService->sendPaymentReminders();
                $this->line('  ✓ Reminders sent.');
            }
        }

        // Suspensions
        if ($this->option('suspend')) {
            $this->info('Suspending overdue services…');
            if (!$isDry) {
                $count = $renewalService->suspendOverdueServices();
                $this->line("  ✓ {$count} service(s) suspended.");
            }
        }

        // Terminaisons
        if ($this->option('terminate')) {
            $this->info('Terminating expired services…');
            if (!$isDry) {
                $count = $renewalService->terminateExpiredServices();
                $this->line("  ✓ {$count} service(s) terminated.");
            }
        }

        $this->info('Done.');
        return Command::SUCCESS;
    }
}
