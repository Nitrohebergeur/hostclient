<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id || $user->hasRole('admin');
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}
