<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;

class ActivityLogger
{
    public function log(Ticket $ticket, string $action, string $description, ?User $user = null, ?array $properties = null): ActivityLog
    {
        return ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user?->id,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
