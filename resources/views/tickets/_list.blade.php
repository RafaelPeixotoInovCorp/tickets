<div class="divide-y divide-kirri-100">
    @forelse($tickets as $ticket)
        <div class="kirri-row-hover flex items-center gap-3 px-5 py-4 group active:scale-[0.995]">
            <a href="{{ route('tickets.show', $ticket) }}" class="flex items-center gap-4 flex-1 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-kirri-50 border border-kirri-200 flex items-center justify-center text-sm font-semibold text-kirri-primary shrink-0">
                    {{ substr($ticket->contact->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0 grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-2 lg:gap-6 items-center">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @if($ticket->is_pinned ?? false)
                                <x-icons.pin filled class="w-3.5 h-3.5 text-kirri-primary shrink-0" />
                            @endif
                            <span class="text-xs font-mono font-semibold text-kirri-accent">{{ $ticket->number }}</span>
                            <span class="text-xs text-kirri-400 hidden sm:inline">·</span>
                            <span class="text-xs text-kirri-500 truncate">{{ $ticket->entity->name }}</span>
                        </div>
                        <p class="font-semibold text-kirri-900 truncate kirri-motion group-hover:text-kirri-primary">{{ $ticket->subject }}</p>
                        <div class="flex items-center gap-2 mt-1 text-xs text-kirri-400">
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $ticket->inbox->color }}"></span>
                                {{ $ticket->inbox->name }}
                            </span>
                            <span>·</span>
                            <span>{{ $ticket->ticketType->name }}</span>
                            @if($ticket->operator)
                                <span>·</span>
                                <span>{{ $ticket->operator->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <x-status-badge :status="$ticket->ticketStatus" />
                        <span class="text-xs text-kirri-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </a>
            <x-tickets.pin-button :ticket="$ticket" />
        </div>
    @empty
        <div class="px-6 py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-kirri-50 border border-kirri-200 flex items-center justify-center mx-auto mb-4 text-kirri-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            </div>
            <p class="text-kirri-600 font-medium">{{ $emptyTitle ?? 'Nenhum ticket encontrado' }}</p>
            <p class="text-sm text-kirri-400 mt-1">{{ $emptyHint ?? 'Ajuste os filtros ou crie um novo ticket' }}</p>
        </div>
    @endforelse
</div>
