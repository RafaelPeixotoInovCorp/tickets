<x-mail::message>
# Novo Ticket: {{ $ticket->number }}

**Assunto:** {{ $ticket->subject }}

**Entidade:** {{ $ticket->entity->name }}

**Inbox:** {{ $ticket->inbox->name }}

---

{{ Str::limit(strip_tags($ticket->body), 300) }}

<x-mail::button :url="$url">
Ver Ticket
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
