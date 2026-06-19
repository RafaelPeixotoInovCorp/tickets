@props(['author', 'time', 'isOperator' => false, 'isInternal' => false, 'badge' => null, 'attachments' => null])

<div class="flex gap-3 {{ $isOperator ? 'flex-row-reverse' : '' }}">
    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center text-sm font-semibold {{ $isOperator ? 'kirri-message-avatar-operator' : 'kirri-message-avatar-client' }}">
        {{ substr($author, 0, 1) }}
    </div>
    <div class="flex-1 min-w-0 max-w-[85%] {{ $isOperator ? 'text-right' : '' }}">
        <div class="flex items-center gap-2 mb-1.5 {{ $isOperator ? 'justify-end' : '' }}">
            <span class="text-sm font-semibold" style="color: var(--kirri-text)">{{ $author }}</span>
            <span class="text-xs" style="color: var(--kirri-text-muted)">{{ $time }}</span>
            @if($badge)
                <span class="text-xs px-2 py-0.5 rounded-md kirri-badge-default">{{ $badge }}</span>
            @endif
            @if($isInternal)
                <span class="text-xs px-2 py-0.5 rounded-md kirri-badge-internal">Nota interna</span>
            @endif
        </div>
        <div class="rounded-2xl px-4 py-3 text-sm whitespace-pre-wrap {{ $isInternal ? 'kirri-message-bubble-internal' : ($isOperator ? 'kirri-message-bubble-operator' : 'kirri-message-bubble-client') }}">
            {{ $slot }}
        </div>
        @if($attachments && $attachments->count())
            <div class="mt-2 flex flex-wrap gap-2 {{ $isOperator ? 'justify-end' : '' }}">
                @foreach($attachments as $attachment)
                    <a href="{{ $attachment->url() }}" target="_blank"
                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs kirri-motion kirri-badge-default border border-[var(--kirri-border)] hover:border-[var(--kirri-border-hover)]">
                        {{ $attachment->filename }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
