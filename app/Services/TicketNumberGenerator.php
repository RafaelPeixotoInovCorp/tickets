<?php

namespace App\Services;

use App\Models\Ticket;

class TicketNumberGenerator
{
    public function generate(): string
    {
        $lastNumber = Ticket::query()
            ->orderByDesc('id')
            ->value('number');

        $sequence = 1;

        if ($lastNumber && preg_match('/^TC-(\d+)$/', $lastNumber, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return 'TC-'.$sequence;
    }
}
