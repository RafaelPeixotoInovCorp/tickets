<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Tickets</h1>
            <p class="text-sm text-kirri-500 mt-1">Gerir e resolver pedidos de clientes</p>
        </div>
    </x-slot>

    {{-- Status tabs --}}
    <div class="flex flex-wrap items-center gap-2 mb-5">
        <a href="{{ route('tickets.index', request()->except('status')) }}"
           class="kirri-pill {{ !request('status') ? 'kirri-pill-active' : 'kirri-pill-idle' }}">
            Todos
        </a>
        @foreach($statuses as $status)
            <a href="{{ route('tickets.index', array_merge(request()->except('status'), ['status' => $status->id])) }}"
               class="kirri-pill {{ request('status') == $status->id ? 'kirri-pill-active' : 'kirri-pill-idle' }}">
                {{ $status->name }}
            </a>
        @endforeach
    </div>

  {{-- Filters --}}
    <div class="kirri-panel p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-kirri-500 mb-1">Inbox</label>
                <select name="inbox" class="kirri-select w-full">
                    <option value="">Todos</option>
                    @foreach($inboxes as $inbox)
                        <option value="{{ $inbox->id }}" @selected(request('inbox') == $inbox->id)>{{ $inbox->name }}</option>
                    @endforeach
                </select>
            </div>
            @if($user->isOperator())
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-kirri-500 mb-1">Operador</label>
                    <select name="operator" class="kirri-select w-full">
                        <option value="">Todos</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}" @selected(request('operator') == $op->id)>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-kirri-500 mb-1">Tipo</label>
                <select name="type" class="kirri-select w-full">
                    <option value="">Todos</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected(request('type') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            @if($user->isOperator())
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-kirri-500 mb-1">Entidade</label>
                    <select name="entity" class="kirri-select w-full">
                        <option value="">Todas</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" @selected(request('entity') == $entity->id)>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="kirri-btn-primary">Aplicar filtros</button>
            @if(request()->hasAny(['search','inbox','status','operator','type','entity']))
                <a href="{{ route('tickets.index') }}" class="kirri-btn-secondary">Limpar</a>
            @endif
        </form>
    </div>

    <div class="kirri-panel overflow-hidden">
        @include('tickets._list')
        @if($tickets->hasPages())
            <div class="px-5 py-3 border-t border-kirri-200 bg-kirri-50/50">{{ $tickets->links() }}</div>
        @endif
    </div>
</x-app-layout>
