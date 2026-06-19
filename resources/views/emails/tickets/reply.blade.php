<x-mail::message>
# Nova Resposta: {{ $ticket->number }}

**Assunto:** {{ $ticket->subject }}

**De:** {{ $reply->authorName() }}

---

{{ Str::limit(strip_tags($reply->body), 300) }}

<x-mail::button :url="$url">
Ver Ticket
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
