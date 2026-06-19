<?php

namespace App\Services;

use App\Mail\TicketCreatedMail;
use App\Mail\TicketReplyMail;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Mail;

class TicketNotificationService
{
    public function notifyTicketCreated(Ticket $ticket): void
    {
        $recipients = $this->ticketRecipients($ticket);

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new TicketCreatedMail($ticket));
        }
    }

    public function notifyTicketReply(Ticket $ticket, TicketReply $reply): void
    {
        if ($reply->is_internal) {
            return;
        }

        $recipients = $this->ticketRecipients($ticket, $reply);

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new TicketReplyMail($ticket, $reply));
        }
    }

    private function ticketRecipients(Ticket $ticket, ?TicketReply $reply = null): array
    {
        $emails = [];

        if ($ticket->contact?->email) {
            $emails[] = $ticket->contact->email;
        }

        foreach ($ticket->allCcEmails() as $cc) {
            if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $cc;
            }
        }

        if ($ticket->operator?->email) {
            $emails[] = $ticket->operator->email;
        }

        if ($reply) {
            $authorEmail = $reply->user?->email ?? $reply->contact?->email;
            $emails = array_filter($emails, fn ($email) => $email !== $authorEmail);
        }

        return array_values(array_unique($emails));
    }
}
