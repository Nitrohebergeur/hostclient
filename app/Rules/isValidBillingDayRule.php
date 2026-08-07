<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */

namespace App\Rules;

use App\Models\Billing\Subscription;
use App\Models\Provisioning\Service;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class isValidBillingDayRule implements ValidationRule
{
    /**
     * The subscription instance.
     */
    protected Subscription $subscription;

    /**
     * The service instance.
     */
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->subscription = ($service->subscription) ? $service->subscription : (new Subscription(['service_id' => $service->id]));
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->subscription->isValidBillingDay($value)) {
            $fail(__('client.services.subscription.invalid_billing_day', ['day' => $this->service->expires_at->format('d/m'), 'now' => now()->format('d/m')]));
        }

    }
}
