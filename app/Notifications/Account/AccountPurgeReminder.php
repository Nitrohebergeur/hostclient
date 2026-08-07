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

namespace App\Notifications\Account;

use App\Models\Admin\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Pre-purge reminder for inactive accounts. Sent at D-30,
 * D-7 and D-1 by {@see \App\Console\Commands\Purge\PurgeInactiveAccountsCommand}.
 *
 * The mail uses the standard Notification channel so operators that
 * have customised the email layout (notifications::custom view) keep
 * their branding. The CTA points to /login so the customer can prove
 * activity and reset the timer.
 */
class AccountPurgeReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $daysLeft) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'Hostclient');
        $loginUrl = url('/login');

        return EmailTemplate::getMailMessage('account_purge_reminder', $loginUrl, [
            'days' => $this->daysLeft,
            'app' => $appName,
            'customer' => $notifiable,
        ], $notifiable);
    }
}
