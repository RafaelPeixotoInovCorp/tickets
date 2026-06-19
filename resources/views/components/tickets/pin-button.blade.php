@props(['ticket', 'pinned' => null])

@php
    $isPinned = $pinned ?? ($ticket->is_pinned ?? false);
@endphp

@if($isPinned)
    <form method="POST" action="{{ route('tickets.pin.destroy', $ticket) }}" class="shrink-0">
        @csrf
        @method('DELETE')
        <button type="submit" class="kirri-pin-btn kirri-pin-btn-active" title="Remover afixação" aria-label="Remover afixação">
            <x-icons.pin filled class="w-4 h-4" />
        </button>
    </form>
@else
    <form method="POST" action="{{ route('tickets.pin.store', $ticket) }}" class="shrink-0">
        @csrf
        <button type="submit" class="kirri-pin-btn" title="Afixar ticket" aria-label="Afixar ticket">
            <x-icons.pin class="w-4 h-4" />
        </button>
    </form>
@endif
