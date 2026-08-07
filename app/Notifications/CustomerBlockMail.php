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

namespace App\Notifications;

use App\Models\Admin\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomerBlockMail extends Notification implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable;

    private string $reason;

    private string $template;

    public function __construct(string $reason, string $template)
    {
        $this->reason = $reason;
        $this->template = $template;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $button = str_contains($this->template, '_un') ? route('front.client.index') : route('front.support.create');
        $context = [
            'reason' => $this->reason,
            'customer' => $notifiable,
        ];

        return EmailTemplate::getMailMessage($this->template, $button, $context, $notifiable);
    }
}
