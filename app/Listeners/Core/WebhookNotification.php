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

namespace App\Listeners\Core;

use App\DTO\Core\WebhookDTO;
use App\Events\Core\CheckoutCompletedEvent;
use App\Events\Core\Service\ServiceRenewed;
use App\Events\Core\Service\ServiceUpgraded;
use App\Events\Helpdesk\HelpdeskTicketAnsweredCustomer;
use App\Events\Helpdesk\HelpdeskTicketAnsweredStaff;
use App\Events\Helpdesk\HelpdeskTicketClosedEvent;
use App\Events\Helpdesk\HelpdeskTicketCreatedEvent;
use App\Models\Billing\Invoice;

class WebhookNotification
{
    private array $webhooks = [];

    private static array $extensionWebhooks = [];

    public static function addExtensionWebhook(WebhookDTO $webhook): void
    {
        self::$extensionWebhooks[] = $webhook;
    }

    public static function getExtensionWebhooks(): array
    {
        return self::$extensionWebhooks;
    }

    public function handle($event): void
    {
        $this->registerWebhook();
        $this->webhooks = array_merge($this->webhooks, self::$extensionWebhooks);
        if ($this->inWebhookList($event)) {
            $this->sendWebhook($event);
        }
    }

    private function inWebhookList($event): bool
    {
        return collect($this->webhooks)->contains('event', get_class($event));
    }

    private function sendWebhook($event): void
    {
        /** @var WebhookDTO $webhook */
        $webhook = collect($this->webhooks)->firstWhere('event', get_class($event));
        if (! $webhook) {
            return;
        }
        if ($webhook->isDisabled()) {
            return;
        }
        $webhook->send([$event]);
    }

    private function registerWebhook(): void
    {
        $this->webhooks[] = new WebhookDTO(HelpdeskTicketAnsweredCustomer::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.ticket_answer.title'),
                        'description' => __('webhook.ticket_answer.description'),
                        'color' => 0x3498DB,
                        'fields' => [
                            [
                                'name' => __('helpdesk.subject'),
                                'value' => '[`📝`]  %subject%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.department'),
                                'value' => '[`📂`]  %department%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.support.show.reply'),
                                'value' => '[`🔗`]  %__url%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.content'),
                                'value' => '%message%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (HelpdeskTicketAnsweredCustomer $event) {
            return [
                '%action%' => 'helpdesk_answered_customer',
                '%__url%' => route('admin.helpdesk.tickets.show', $event->ticket->id),
                '%ticketid%' => $event->ticket->id,
                '%customer_url%' => route('admin.customers.show', $event->ticket->customer->id),
                '%subject%' => $event->ticket->subject,
                '%customername%' => $event->ticket->customer->excerptFullName(),
                '%customeremail%' => $event->ticket->customer->email,
                '%department%' => $event->ticket->department->trans('name'),
                '%message%' => substr($event->message->message, 0, 100),
            ];
        }, setting('helpdesk_webhook_url'));

        $this->webhooks[] = new WebhookDTO(HelpdeskTicketClosedEvent::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.ticket_closed.title'),
                        'description' => __('webhook.ticket_closed.description'),
                        'color' => 0x95A5A6,
                        'fields' => [
                            [
                                'name' => __('helpdesk.subject'),
                                'value' => '[`📝`]  %subject%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.department'),
                                'value' => '[`📂`]  %department%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.support.show.reply'),
                                'value' => '[`🔗`]  %__url%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (HelpdeskTicketClosedEvent $event) {
            return [
                '%action%' => 'helpdesk_closed',
                '%__url%' => route('admin.helpdesk.tickets.show', $event->ticket->id),
                '%ticketid%' => $event->ticket->id,
                '%customer_url%' => route('admin.customers.show', $event->ticket->customer->id),
                '%subject%' => $event->ticket->subject,
                '%customername%' => $event->ticket->customer->excerptFullName(),
                '%customeremail%' => $event->ticket->customer->email,
                '%department%' => $event->ticket->department->trans('name'),
            ];
        }, setting('helpdesk_webhook_url'));
        $this->webhooks[] = new WebhookDTO(HelpdeskTicketCreatedEvent::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.ticket.title'),
                        'description' => __('webhook.ticket.description'),
                        'color' => 0xE67E22,
                        'fields' => [
                            [
                                'name' => __('helpdesk.subject'),
                                'value' => '[`📝`]  %subject%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.department'),
                                'value' => '[`📂`]  %department%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.priority'),
                                'value' => '[`🔖`]  %priority%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.support.show.reply'),
                                'value' => '[`🔗`]  %__url%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.content'),
                                'value' => '%message%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (HelpdeskTicketCreatedEvent $event) {
            return [
                '%action%' => 'helpdesk_created',
                '%__url%' => route('admin.helpdesk.tickets.show', $event->ticket->id),
                '%ticketid%' => $event->ticket->id,
                '%customer_url%' => route('admin.customers.show', $event->ticket->customer->id),
                '%subject%' => $event->ticket->subject,
                '%customername%' => $event->ticket->customer->excerptFullName(),
                '%customeremail%' => $event->ticket->customer->email,
                '%message%' => substr($event->message->message, 0, 100),
                '%department%' => $event->ticket->department->trans('name'),
                '%priority%' => $event->ticket->priorityLabel(),
            ];
        }, setting('helpdesk_webhook_url'));

        $this->webhooks[] = new WebhookDTO(HelpdeskTicketAnsweredStaff::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.ticket_answer.title'),
                        'description' => __('webhook.ticket_answer.description'),
                        'color' => 0x9B59B6,
                        'fields' => [
                            [
                                'name' => __('helpdesk.subject'),
                                'value' => '[`📝`]  %subject%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.department'),
                                'value' => '[`📂`]  %department%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.priority'),
                                'value' => '[`🔖`]  %priority%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('helpdesk.support.show.reply'),
                                'value' => '[`🔗`]  %__url%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.content'),
                                'value' => '%message%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (HelpdeskTicketAnsweredStaff $event) {
            return [
                '%action%' => 'helpdesk_answered_staff',
                '%__url%' => route('admin.helpdesk.tickets.show', $event->ticket->id),
                '%ticketid%' => $event->ticket->id,
                '%customer_url%' => route('admin.customers.show', $event->ticket->customer->id),
                '%department%' => $event->ticket->department->trans('name'),
                '%priority%' => $event->ticket->priorityLabel(),
                '%subject%' => $event->ticket->subject,
                '%message%' => substr($event->message->message, 0, 100),
                '%customername%' => $event->ticket->customer->excerptFullName(),
                '%customeremail%' => $event->ticket->customer->email,
            ];
        }, setting('helpdesk_webhook_url'));

        $this->webhooks[] = new WebhookDTO(ServiceRenewed::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.renew.title'),
                        'description' => __('webhook.renew.description'),
                        'color' => 0x2ECC71,
                        'fields' => [
                            [
                                'name' => __('global.name'),
                                'value' => '[`🧾`] [%servicename%](%__url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.expiration'),
                                'value' => '[`📗`] %last_expires_at% - %expiresat%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('store.price'),
                                'value' => '[`💰`] %price% %currency%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.invoice'),
                                'value' => '[`💸`] %invoiceurl%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (ServiceRenewed $event) {
            $invoice = $event->service->serviceRenewals->last()->invoice;
            $invoiceId = $invoice ? $invoice->id : 0;

            return [
                '%action%' => 'service_renewed',
                '%__url%' => route('admin.services.show', $event->service->id),
                '%servicename%' => $event->service->name,
                '%serviceid%' => $event->service->id,
                '%customer_url%' => route('admin.customers.show', $event->service->customer->id),
                '%expiresat%' => $event->service->expires_at->format('d/m/y'),
                '%last_expires_at%' => $event->service->last_expires_at ? $event->service->last_expires_at->format('d/m/y') : __('global.never'),
                '%customername%' => $event->service->customer->excerptFullName(),
                '%currency%' => currency_symbol($event->service->currency),
                '%customeremail%' => $event->service->customer->email,
                '%invoiceurl%' => route('admin.invoices.show', ['invoice' => $invoiceId]),
                '%price%' => $event->service->getBillingPrice()->displayPrice(),
            ];
        }, setting('webhook_renewal_url'));

        $this->webhooks[] = new WebhookDTO(CheckoutCompletedEvent::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.checkout.title'),
                        'description' => __('webhook.checkout.description'),
                        'color' => 0xE74C3C,
                        'fields' => [
                            [
                                'name' => __('store.basket.title'),
                                'value' => '[`🛒`] # %basketid%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('store.total'),
                                'value' => '[`💰`] %total% %currency% - %gatewayname%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`]  [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.customer'),
                                'value' => '[`📗`] %customername%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.products'),
                                'value' => '[`🛍️`] %productnames%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.invoice'),
                                'value' => '[`💸`] %invoiceurl%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (CheckoutCompletedEvent $event) {
            $customer = $event->basket->customer;
            if (! $customer || $event->invoice->status != Invoice::STATUS_PAID) {
                return [];
            }

            return [
                '%action%' => 'checkout_completed',
                '%__url%' => route('admin.invoices.show', ['invoice' => $event->basket->getMetadata('invoice')]),
                '%customername%' => $customer->excerptFullName(),
                '%customeremail%' => $customer->email,
                '%basketid%' => $event->basket->id,
                '%customer_url%' => route('admin.customers.show', $customer->id),
                '%total%' => $event->basket->total(),
                '%currency%' => currency_symbol($event->basket->currency()),
                '%gatewayname%' => $event->invoice->gateway->name,
                '%invoiceurl%' => route('admin.invoices.show', ['invoice' => $event->basket->getMetadata('invoice')]),
                '%productnames%' => $event->basket->items->map(fn ($item) => $item->name())->implode(', '),
            ];
        }, setting('store_checkout_webhook_url'), ['label' => __('View Order')]);
        $this->webhooks[] = new WebhookDTO(ServiceUpgraded::class, function () {
            return [
                'content' => null,
                'embeds' => [
                    [
                        'title' => __('webhook.upgrade.title'),
                        'description' => __('webhook.upgrade.description'),
                        'color' => 0x1ABC9C,
                        'fields' => [
                            [
                                'name' => __('global.customer'),
                                'value' => '[`👤`] %customername%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.email'),
                                'value' => '[`📙`] [%customeremail%](%customer_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('store.product.old'),
                                'value' => '[`⬅️`] %old_product%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('store.product.new'),
                                'value' => '[`➡️`] %new_product%',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.service'),
                                'value' => '[`🔧`] [%service_name%](%service_url%)',
                                'inline' => true,
                            ],
                            [
                                'name' => __('global.invoice'),
                                'value' => '[`💸`] %invoice_url%',
                                'inline' => true,
                            ],
                        ],
                        'footer' => [
                            'text' => config('app.name'),
                            'icon_url' => 'https://Hostclient.com/Themes/Hostclient/images/Hostclient/LogoBlue.png',
                        ],
                        'timestamp' => now()->format('c'),
                    ],
                ],
            ];
        }, function (ServiceUpgraded $event) {
            $upgrade = $event->upgrade;
            $customer = $upgrade->customer;
            $service = $upgrade->service;

            return [
                '%action%' => 'service_upgraded',
                '%customername%' => $customer->excerptFullName(),
                '%customeremail%' => $customer->email,
                '%customer_url%' => route('admin.customers.show', $customer->id),
                '%old_product%' => $event->old->name,
                '%new_product%' => $event->new->name,
                '%service_name%' => $service->name,
                '%service_url%' => route('admin.services.show', $service->id),
                '%invoice_url%' => $upgrade->invoice ? route('admin.invoices.show', $upgrade->invoice->id) : __('global.none'),
            ];
        }, setting('webhook_renewal_url'));

    }
}
