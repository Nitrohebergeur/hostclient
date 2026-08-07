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

namespace App\Mail\Helpdesk;

use App\Models\Admin\EmailTemplate;
use App\Models\Helpdesk\SupportTicket;
use App\Services\Helpdesk\InboundReplyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class NotifyCustomerEmail extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    private string $message;

    private SupportTicket $ticket;

    public function __construct(SupportTicket $ticket, string $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $ticketUrl = route('front.support.show', $this->ticket->id);
        $replyAddress = InboundReplyService::replyAddress($this->ticket);

        $mail = EmailTemplate::getMailMessage('support_customer_ticket_reply', $ticketUrl, [
            'ticket' => $this->ticket,
            'message' => (string) $this->message,
            'reply_address' => $replyAddress,
        ], $notifiable)->replyTo($replyAddress);
    }
}
