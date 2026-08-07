<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    protected $signature   = 'currencies:update-rates {--base=EUR : Base currency code}';
    protected $description = 'Fetch latest exchange rates from the API';

    public function handle(): int
    {
        $base = strtoupper($this->option('base'));
        $this->info("Updating exchange rates (base: {$base})…");

        Currency::updateRates($base);

        $this->info('Exchange rates updated.');
        return Command::SUCCESS;
    }
}
