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

namespace App\Models\Store\Basket;

use App\Services\Store\TaxesService;

trait BasketRowCouponTrait
{
    private bool $enableCoupon = false;

    public function enableCoupon(bool $enable = true): void
    {
        $this->enableCoupon = $enable;
    }

    public function taxWithoutCoupon()
    {
        $this->enableCoupon(false);

        return TaxesService::getTaxAmount($this->subtotal(), $this->taxPercent());
    }

    public function subtotalWithoutCoupon()
    {
        $this->enableCoupon(false);

        return $this->recurringPaymentWithoutCoupon() + $this->setupWithoutCoupon() + $this->onetimePaymentWithoutCoupon();
    }

    public function recurringPaymentWithoutCoupon(bool $withQuantity = true, ?string $billing = null)
    {
        $this->enableCoupon(false);

        if ($this->billing == 'onetime') {
            return 0;
        }
        if (! $withQuantity) {
            $recurringPayment = $this->getUnitPrice($billing)->recurringPayment();
        } else {
            $recurringPayment = $this->getUnitPrice($billing)->recurringPayment() * $this->quantity;
        }

        return $this->applyCoupon($recurringPayment, self::PRICE) + $this->applyCoupon($this->optionRecurringPayment(), self::OPTION);
    }

    public function onetimePaymentWithoutCoupon(bool $withQuantity = true, ?string $billing = null)
    {
        $this->enableCoupon(false);

        if ($this->billing != 'onetime') {
            return 0;
        }
        if (! $withQuantity) {
            $onetimePayment = $this->getUnitPrice($billing)->onetimePayment();
        } else {
            $onetimePayment = $this->getUnitPrice($billing)->onetimePayment() * $this->quantity;
        }

        return $this->applyCoupon($onetimePayment, self::PRICE) + $this->applyCoupon($this->optionOnetimePayment(), self::OPTION);
    }

    public function setupWithoutCoupon(bool $withQuantity = true, ?string $billing = null)
    {
        $this->enableCoupon(false);
        if (! $withQuantity) {
            $setup = $this->getUnitPrice($billing)->setup;
        } else {
            $setup = $this->getUnitPrice($billing)->setup * $this->quantity;
        }

        return $this->applyCoupon($setup, self::SETUP_FEES) + $this->applyCoupon($this->optionSetup(), self::OPTION);
    }

    public function totalWithoutCoupon()
    {
        $this->enableCoupon(false);

        return $this->subtotalWithoutCoupon() + $this->taxWithoutCoupon();
    }
}
