<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $ticketQuery = Ticket::query()
            ->with(['entity', 'contact', 'ticketStatus', 'operator', 'inbox']);

        if ($user->isOperator()) {
            $inboxIds = $user->accessibleInboxIds();
            $ticketQuery->whereIn('inbox_id', $inboxIds);
        } else {
            $entityIds = $user->accessibleEntityIds();
            $ticketQuery->whereIn('entity_id', $entityIds);
        }

        $stats = [
            'total' => (clone $ticketQuery)->count(),
            'open' => (clone $ticketQuery)->whereHas('ticketStatus', fn ($q) => $q->where('is_closed', false))->count(),
            'assigned' => $user->isOperator()
                ? (clone $ticketQuery)->where('operator_id', $user->id)->count()
                : 0,
        ];

        $recentTickets = (clone $ticketQuery)
            ->latest()
            ->limit(8)
            ->get();

        $inboxes = $user->isOperator()
            ? $user->inboxes()->withCount('tickets')->get()
            : Inbox::where('is_active', true)->get();

        return view('dashboard', compact('stats', 'recentTickets', 'inboxes', 'user'));
    }
}
