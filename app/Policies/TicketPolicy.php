<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isOperator()) {
            return in_array($ticket->inbox_id, $user->accessibleInboxIds());
        }

        return in_array($ticket->entity_id, $user->accessibleEntityIds());
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket) && $user->isOperator();
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        if ($ticket->ticketStatus?->is_closed) {
            return false;
        }

        return $this->view($user, $ticket);
    }
}
