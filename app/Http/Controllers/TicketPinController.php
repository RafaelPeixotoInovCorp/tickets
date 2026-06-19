<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketPinController extends Controller
{
    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('view', $ticket);

        $user = $request->user();

        if (! $user->pinnedTickets()->whereKey($ticket->id)->exists()) {
            $user->pinnedTickets()->attach($ticket->id);
        }

        return back()->with('success', 'Ticket afixado.');
    }

    public function destroy(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('view', $ticket);

        $request->user()->pinnedTickets()->detach($ticket->id);

        return back()->with('success', 'Ticket removido dos afixados.');
    }
}
