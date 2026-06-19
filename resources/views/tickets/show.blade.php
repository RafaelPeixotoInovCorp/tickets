<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-1 text-sm text-kirri-500 kirri-motion hover:text-kirri-primary mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Voltar aos tickets
                </a>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl font-semibold text-kirri-900">{{ $ticket->subject }}</h1>
                    <x-status-badge :status="$ticket->ticketStatus" />
                </div>
                <p class="text-sm text-kirri-500 mt-1 font-mono">{{ $ticket->number }} · {{ $ticket->entity->name }}</p>
            </div>
            <x-tickets.pin-button :ticket="$ticket" :pinned="$isPinned" />
        </div>
    </x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-5 items-start">
        {{-- Conversation panel --}}
        <div class="kirri-panel flex flex-col min-h-[600px]">
            <div class="px-5 py-3 border-b border-kirri-200 flex items-center gap-1">
                <span class="px-3 py-1.5 text-sm font-semibold text-kirri-primary bg-kirri-primary/10 rounded-lg">Conversa</span>
            </div>

            <div class="flex-1 p-5 space-y-5 kirri-conversation-bg">
                <x-kirri.message
                    :author="$ticket->contact->name"
                    :time="$ticket->created_at->format('d/m/Y H:i')"
                    badge="Mensagem inicial"
                    :attachments="$ticket->attachments->whereNull('ticket_reply_id')">
                    {{ $ticket->body }}
                </x-kirri.message>

                @foreach($visibleReplies as $reply)
                    <x-kirri.message
                        :author="$reply->authorName()"
                        :time="$reply->created_at->format('d/m/Y H:i')"
                        :is-operator="$reply->isFromOperator()"
                        :is-internal="$reply->is_internal"
                        :attachments="$reply->attachments">
                        {{ $reply->body }}
                    </x-kirri.message>
                @endforeach
            </div>

            @if(!$ticket->ticketStatus->is_closed)
                <div class="p-5 border-t border-kirri-200 kirri-panel" style="border-top-left-radius: 0; border-top-right-radius: 0; border-top: 1px solid var(--kirri-border); background: var(--kirri-surface);">
                    <form method="POST" action="{{ route('tickets.replies.store', $ticket) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <textarea name="body" rows="4" required placeholder="Escreva a sua resposta ao cliente..."
                                  class="kirri-input resize-none"></textarea>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-2 text-sm text-kirri-600 cursor-pointer hover:text-kirri-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <input type="file" name="attachments[]" multiple class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files.length ? this.files.length + ' ficheiro(s)' : 'Anexar ficheiros'">
                                    <span>Anexar ficheiros</span>
                                </label>
                                @if($user->isOperator())
                                    <label class="flex items-center gap-2 text-sm text-kirri-600">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-kirri-300 text-amber-600 focus:ring-amber-500">
                                        Nota interna
                                    </label>
                                @endif
                            </div>
                            <button type="submit" class="kirri-btn-primary">Enviar resposta</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-4 border-t border-kirri-200 bg-kirri-50 text-sm text-kirri-500 text-center">
                    Este ticket está fechado. Não é possível adicionar novas respostas.
                </div>
            @endif
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-5" x-data="{ tab: 'details' }">
            {{-- Customer card --}}
            <div class="kirri-panel p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-kirri-primary text-white flex items-center justify-center text-lg font-semibold">
                        {{ substr($ticket->contact->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-kirri-900 truncate">{{ $ticket->contact->name }}</p>
                        <p class="text-sm text-kirri-500 truncate">{{ $ticket->contact->email ?? 'Sem email' }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between gap-2">
                        <span class="text-kirri-500">Entidade</span>
                        <span class="font-medium text-kirri-900 text-right">{{ $ticket->entity->name }}</span>
                    </div>
                    @if($ticket->operator)
                        <div class="flex justify-between gap-2">
                            <span class="text-kirri-500">Agente</span>
                            <span class="font-medium text-kirri-900">{{ $ticket->operator->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabbed panel --}}
            <div class="kirri-panel">
                <div class="flex border-b border-kirri-200">
                    <button @click="tab = 'details'" :class="tab === 'details' ? 'kirri-tab-active' : ''" class="kirri-tab">Detalhes</button>
                    @if($user->isOperator())
                        <button @click="tab = 'manage'" :class="tab === 'manage' ? 'kirri-tab-active' : ''" class="kirri-tab">Gestão</button>
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'kirri-tab-active' : ''" class="kirri-tab">Atividade</button>
                    @endif
                </div>

                <div class="p-5">
                    <div x-show="tab === 'details'"
                         x-transition:enter="kirri-tab-panel-enter"
                         x-transition:enter-start="kirri-tab-panel-enter-start"
                         x-transition:enter-end="kirri-tab-panel-enter-end">
                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="text-xs font-medium text-kirri-400 uppercase tracking-wide mb-1">Inbox</dt>
                                <dd class="font-medium flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $ticket->inbox->color }}"></span>
                                    {{ $ticket->inbox->name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-kirri-400 uppercase tracking-wide mb-1">Tipo</dt>
                                <dd class="font-medium text-kirri-900">{{ $ticket->ticketType->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-kirri-400 uppercase tracking-wide mb-1">Criado em</dt>
                                <dd class="text-kirri-700">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($ticket->allCcEmails())
                                <div>
                                    <dt class="text-xs font-medium text-kirri-400 uppercase tracking-wide mb-1">Conhecimento</dt>
                                    @foreach($ticket->allCcEmails() as $cc)
                                        <dd class="text-kirri-700">{{ $cc }}</dd>
                                    @endforeach
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if($user->isOperator())
                        <div x-show="tab === 'manage'" x-cloak
                             x-transition:enter="kirri-tab-panel-enter"
                             x-transition:enter-start="kirri-tab-panel-enter-start"
                             x-transition:enter-end="kirri-tab-panel-enter-end">
                            <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-4">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-xs font-medium text-kirri-500 mb-1">Estado</label>
                                    <select name="ticket_status_id" class="kirri-select w-full">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" @selected($ticket->ticket_status_id == $status->id)>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-kirri-500 mb-1">Atribuir agente</label>
                                    <select name="operator_id" class="kirri-select w-full">
                                        <option value="">Sem atribuição</option>
                                        @foreach($operators as $op)
                                            <option value="{{ $op->id }}" @selected($ticket->operator_id == $op->id)>{{ $op->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="kirri-btn-primary w-full">Atualizar ticket</button>
                            </form>
                        </div>

                        <div x-show="tab === 'activity'" x-cloak
                             x-transition:enter="kirri-tab-panel-enter"
                             x-transition:enter-start="kirri-tab-panel-enter-start"
                             x-transition:enter-end="kirri-tab-panel-enter-end">
                            @forelse($ticket->activityLogs as $log)
                                <div class="py-3 border-b border-kirri-100 last:border-0">
                                    <p class="text-sm text-kirri-800">{{ $log->description }}</p>
                                    <p class="text-xs text-kirri-400 mt-1">{{ $log->user?->name ?? 'Sistema' }} · {{ $log->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-kirri-500">Sem atividade registada.</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
