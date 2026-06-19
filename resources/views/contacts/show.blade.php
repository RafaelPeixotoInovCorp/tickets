<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">{{ $contact->name }}</h1>
            <a href="{{ route('contacts.edit', $contact) }}" class="kirri-btn-secondary shrink-0">Editar</a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="kirri-panel p-6">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-kirri-500">Função</dt><dd class="font-medium text-kirri-900">{{ $contact->contactRole?->name ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Email</dt><dd class="text-kirri-700">{{ $contact->email ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Telefone</dt><dd class="text-kirri-700">{{ $contact->phone ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Telemóvel</dt><dd class="text-kirri-700">{{ $contact->mobile ?? '—' }}</dd></div>
                @if($contact->internal_notes)
                    <div><dt class="text-kirri-500">Notas Internas</dt><dd class="text-kirri-700">{{ $contact->internal_notes }}</dd></div>
                @endif
            </dl>
        </div>

        <div class="space-y-6">
            <div class="kirri-panel p-6">
                <h3 class="font-medium text-kirri-900 mb-3">Entidades</h3>
                @forelse($contact->entities as $entity)
                    <a href="{{ route('entities.show', $entity) }}" class="block py-2 kirri-link">{{ $entity->name }}</a>
                @empty
                    <p class="text-kirri-500 text-sm">Sem entidades.</p>
                @endforelse
            </div>

            <div class="kirri-panel p-6">
                <h3 class="font-medium text-kirri-900 mb-3">Tickets Recentes</h3>
                @forelse($contact->tickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}" class="block py-2 text-sm text-kirri-700 hover:text-kirri-primary kirri-motion">
                        <span class="font-mono kirri-link">{{ $ticket->number }}</span> — {{ $ticket->subject }}
                    </a>
                @empty
                    <p class="text-kirri-500 text-sm">Sem tickets.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
