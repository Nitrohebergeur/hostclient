<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Log;

class ServiceProvisionService
{
    public function provision(Service $service): bool
    {
        $product = $service->product;

        if (!$product->auto_setup || !$product->module) {
            // Manual provisioning required
            $service->addHistory('pending_manual', 'En attente de provisionnement manuel');

            return false;
        }

        try {
            $module = $this->resolveModule($product->module);

            if (!$module) {
                Log::warning("Module not found: {$product->module}");

                return false;
            }

            $result = $module->create($service);

            if ($result) {
                $service->activate();
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Provisioning failed for service {$service->id}: " . $e->getMessage());
            $service->addHistory('provision_failed', $e->getMessage());

            return false;
        }
    }

    public function suspend(Service $service, string $reason = null): bool
    {
        $product = $service->product;

        if (!$product->module) {
            $service->suspend($reason);

            return true;
        }

        try {
            $module = $this->resolveModule($product->module);
            $module?->suspend($service);
            $service->suspend($reason);

            return true;
        } catch (\Exception $e) {
            Log::error("Suspend failed for service {$service->id}: " . $e->getMessage());

            return false;
        }
    }

    public function terminate(Service $service, string $reason = null): bool
    {
        $product = $service->product;

        if (!$product->module) {
            $service->terminate($reason);

            return true;
        }

        try {
            $module = $this->resolveModule($product->module);
            $module?->terminate($service);
            $service->terminate($reason);

            return true;
        } catch (\Exception $e) {
            Log::error("Terminate failed for service {$service->id}: " . $e->getMessage());

            return false;
        }
    }

    protected function resolveModule(string $moduleName): ?object
    {
        $class = "Modules\\{$moduleName}\\Services\\{$moduleName}Service";

        if (!class_exists($class)) {
            return null;
        }

        return app($class);
    }
}
