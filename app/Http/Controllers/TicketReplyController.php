<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\ActivityLogger;
use App\Services\AttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function __construct(
        private ActivityLogger $activityLogger,
        private AttachmentService $attachmentService,
        private TicketNotificationService $notificationService,
    ) {}

    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('reply', $ticket);

        $user = $request->user();

        $validated = $request->validate([
            'body' => ['required', 'string'],
            'is_internal' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        $isInternal = $user->isOperator() && $request->boolean('is_internal');

        $reply = $ticket->replies()->create([
            'body' => $validated['body'],
            'user_id' => $user->isOperator() ? $user->id : null,
            'contact_id' => $user->isClient() ? $user->contact_id : null,
            'is_internal' => $isInternal,
        ]);

        if ($request->hasFile('attachments')) {
            $this->attachmentService->storeForTicket($ticket, $request->file('attachments'), $reply);
        }

        $this->activityLogger->log(
            $ticket,
            'reply',
            $isInternal ? 'Nota interna adicionada' : 'Nova resposta adicionada',
            $user,
        );

        if (! $isInternal) {
            $this->notificationService->notifyTicketReply($ticket, $reply);
        }

        return back()->with('success', 'Resposta enviada.');
    }
}
