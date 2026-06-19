<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-kirri-500 mt-1">Visão geral do desempenho e tickets recentes</p>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <x-kirri.stat-card label="Total de Tickets" :value="$stats['total']" hint="Todos os tickets visíveis">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            </x-slot:icon>
        </x-kirri.stat-card>
        <x-kirri.stat-card label="Tickets Abertos" :value="$stats['open']" hint="Aguardam resolução">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-kirri.stat-card>
        @if($user->isOperator())
            <x-kirri.stat-card label="Atribuídos a mim" :value="$stats['assigned']" hint="Tickets sob sua responsabilidade">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </x-slot:icon>
            </x-kirri.stat-card>
        @endif
    </div>

    <div class="kirri-panel">
        <div class="px-6 py-4 border-b border-kirri-200 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-kirri-900">Tickets Recentes</h2>
                <p class="text-xs text-kirri-400 mt-0.5">Últimos pedidos registados no sistema</p>
            </div>
            <a href="{{ route('tickets.index') }}" class="kirri-link text-sm font-medium">Ver todos</a>
        </div>
        <div class="divide-y divide-kirri-100">
            @forelse($recentTickets as $ticket)
                <a href="{{ route('tickets.show', $ticket) }}" class="kirri-row-hover flex items-center gap-4 px-6 py-4 group active:scale-[0.995]">
                    <div class="w-10 h-10 rounded-full bg-kirri-100 text-kirri-700 flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ substr($ticket->entity->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-xs font-mono text-kirri-accent font-medium">{{ $ticket->number }}</span>
                            <x-status-badge :status="$ticket->ticketStatus" />
                        </div>
                        <p class="font-medium text-kirri-900 truncate kirri-motion group-hover:text-kirri-primary">{{ $ticket->subject }}</p>
                        <p class="text-sm text-kirri-500">{{ $ticket->entity->name }} · {{ $ticket->inbox->name }}</p>
                    </div>
                    <span class="text-xs text-kirri-400 shrink-0">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-kirri-500">Nenhum ticket encontrado.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
