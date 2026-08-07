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

namespace App\Mail;

use App\Models\Admin\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class MailTested extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Admin $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Hostclient Test Message')
            ->greeting('Hello '.$this->admin->username.'!')
            ->metadata('disable_save', true)
            ->line('This is a test of the Hostclient mail system. You\'re good to go!');
    }
}
