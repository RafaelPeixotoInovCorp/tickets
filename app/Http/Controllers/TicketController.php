<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Entity;
use App\Models\Inbox;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\AttachmentService;
use App\Services\TicketNotificationService;
use App\Services\TicketNumberGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        private TicketNumberGenerator $numberGenerator,
        private ActivityLogger $activityLogger,
        private AttachmentService $attachmentService,
        private TicketNotificationService $notificationService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $this->ticketsQueryForUser($user)
            ->withExists(['pinnedByUsers as is_pinned' => fn ($q) => $q->whereKey($user->id)]);

        if ($request->filled('inbox')) {
            $query->where('inbox_id', $request->inbox);
        }

        if ($request->filled('status')) {
            $query->where('ticket_status_id', $request->status);
        }

        if ($request->filled('operator') && $user->isOperator()) {
            $query->where('operator_id', $request->operator);
        }

        if ($request->filled('type')) {
            $query->where('ticket_type_id', $request->type);
        }

        if ($request->filled('entity')) {
            $query->where('entity_id', $request->entity);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('entity', fn ($e) => $e->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('contact', fn ($c) => $c->where('email', 'like', "%{$search}%"));
            });
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        return view('tickets.index', $this->indexViewData($user, $tickets, $request));
    }

    public function pinned(Request $request): View
    {
        $user = $request->user();

        $query = $user->pinnedTickets()
            ->with(['entity', 'contact', 'ticketStatus', 'operator', 'inbox', 'ticketType']);

        if ($user->isOperator()) {
            $query->whereIn('inbox_id', $user->accessibleInboxIds());
        } else {
            $query->whereIn('entity_id', $user->accessibleEntityIds());
        }

        $tickets = $query
            ->orderByPivot('created_at', 'desc')
            ->paginate(20);

        $tickets->getCollection()->transform(function (Ticket $ticket) {
            $ticket->is_pinned = true;

            return $ticket;
        });

        return view('tickets.pinned', $this->indexViewData($user, $tickets, $request));
    }

    private function ticketsQueryForUser(User $user): Builder
    {
        $query = Ticket::query()
            ->with(['entity', 'contact', 'ticketStatus', 'operator', 'inbox', 'ticketType']);

        if ($user->isOperator()) {
            $query->whereIn('inbox_id', $user->accessibleInboxIds());
        } else {
            $query->whereIn('entity_id', $user->accessibleEntityIds());
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function indexViewData(User $user, $tickets, Request $request): array
    {
        $inboxes = $user->isOperator()
            ? $user->inboxes
            : Inbox::where('is_active', true)->get();

        $statuses = TicketStatus::orderBy('sort_order')->get();
        $types = TicketType::orderBy('name')->get();
        $entities = Entity::orderBy('name')->get();
        $operators = $user->isOperator()
            ? User::where('role', 'operator')->orderBy('name')->get()
            : collect();

        return compact(
            'tickets', 'inboxes', 'statuses', 'types', 'entities', 'operators', 'user'
        );
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        $inboxes = $user->isOperator()
            ? $user->inboxes()->with('ticketTypes')->get()
            : Inbox::where('is_active', true)->with('ticketTypes')->get();

        $entities = $user->isOperator()
            ? Entity::orderBy('name')->get()
            : Entity::whereIn('id', $user->accessibleEntityIds())->orderBy('name')->get();

        $contacts = $user->isOperator()
            ? Contact::orderBy('name')->get()
            : collect([$user->contact])->filter();

        $statuses = TicketStatus::orderBy('sort_order')->get();
        $operators = User::where('role', 'operator')->orderBy('name')->get();

        return view('tickets.create', compact('inboxes', 'entities', 'contacts', 'statuses', 'operators', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'inbox_id' => ['required', 'exists:inboxes,id'],
            'ticket_type_id' => ['required', 'exists:ticket_types,id'],
            'entity_id' => ['required', 'exists:entities,id'],
            'contact_id' => ['required', 'exists:contacts,id'],
            'body' => ['required', 'string'],
            'cc_emails' => ['nullable', 'string'],
            'operator_id' => ['nullable', 'exists:users,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        if ($user->isClient()) {
            $validated['contact_id'] = $user->contact_id;
            $validated['operator_id'] = null;

            if (! in_array($validated['entity_id'], $user->accessibleEntityIds())) {
                abort(403);
            }
        }

        $defaultStatus = TicketStatus::where('is_closed', false)->orderBy('sort_order')->first();

        $ccEmails = collect(explode(',', $validated['cc_emails'] ?? ''))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->values()
            ->all();

        $ticket = Ticket::create([
            'number' => $this->numberGenerator->generate(),
            'subject' => $validated['subject'],
            'ticket_type_id' => $validated['ticket_type_id'],
            'cc_emails' => $ccEmails,
            'ticket_status_id' => $defaultStatus->id,
            'operator_id' => $validated['operator_id'] ?? null,
            'entity_id' => $validated['entity_id'],
            'contact_id' => $validated['contact_id'],
            'inbox_id' => $validated['inbox_id'],
            'body' => $validated['body'],
        ]);

        if ($request->hasFile('attachments')) {
            $this->attachmentService->storeForTicket($ticket, $request->file('attachments'));
        }

        $this->activityLogger->log($ticket, 'created', 'Ticket criado', $user);
        $this->notificationService->notifyTicketCreated($ticket);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket criado com sucesso.');
    }

    public function show(Request $request, Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load([
            'entity',
            'contact',
            'ticketStatus',
            'ticketType',
            'operator',
            'inbox',
            'replies.user',
            'replies.contact',
            'replies.attachments',
            'attachments',
            'activityLogs.user',
        ]);

        $user = $request->user();

        $statuses = $user->isOperator()
            ? TicketStatus::orderBy('sort_order')->get()
            : collect();

        $operators = $user->isOperator()
            ? User::where('role', 'operator')->orderBy('name')->get()
            : collect();

        $isPinned = $ticket->isPinnedBy($user);

        $visibleReplies = $ticket->replies->filter(function ($reply) use ($user) {
            if ($user->isOperator()) {
                return true;
            }

            return ! $reply->is_internal;
        });

        return view('tickets.show', compact('ticket', 'statuses', 'operators', 'user', 'visibleReplies', 'isPinned'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'ticket_status_id' => ['nullable', 'exists:ticket_statuses,id'],
            'operator_id' => ['nullable', 'exists:users,id'],
        ]);

        $user = $request->user();
        $changes = [];

        if (isset($validated['ticket_status_id']) && $validated['ticket_status_id'] !== $ticket->ticket_status_id) {
            $oldStatus = $ticket->ticketStatus->name;
            $newStatus = TicketStatus::find($validated['ticket_status_id'])->name;
            $changes[] = "Estado alterado de {$oldStatus} para {$newStatus}";
            $ticket->ticket_status_id = $validated['ticket_status_id'];
        }

        if (array_key_exists('operator_id', $validated) && $validated['operator_id'] !== $ticket->operator_id) {
            $operatorName = $validated['operator_id']
                ? User::find($validated['operator_id'])->name
                : 'ninguém';
            $changes[] = "Operador atribuído: {$operatorName}";
            $ticket->operator_id = $validated['operator_id'];
        }

        if ($changes) {
            $ticket->save();

            foreach ($changes as $change) {
                $this->activityLogger->log($ticket, 'updated', $change, $user);
            }
        }

        return back()->with('success', 'Ticket atualizado.');
    }
}
