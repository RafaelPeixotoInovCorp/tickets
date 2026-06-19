<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">{{ $entity->name }}</h1>
            <a href="{{ route('entities.edit', $entity) }}" class="kirri-btn-secondary shrink-0">Editar</a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="kirri-panel p-6">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-kirri-500">NIF</dt><dd class="font-medium text-kirri-900">{{ $entity->nif ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Email</dt><dd class="text-kirri-700">{{ $entity->email ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Telefone</dt><dd class="text-kirri-700">{{ $entity->phone ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Telemóvel</dt><dd class="text-kirri-700">{{ $entity->mobile ?? '—' }}</dd></div>
                <div><dt class="text-kirri-500">Website</dt><dd class="text-kirri-700">{{ $entity->website ?? '—' }}</dd></div>
                @if($entity->internal_notes)
                    <div><dt class="text-kirri-500">Notas Internas</dt><dd class="text-kirri-700">{{ $entity->internal_notes }}</dd></div>
                @endif
            </dl>
        </div>

        <div class="space-y-6">
            <div class="kirri-panel p-6">
                <h3 class="font-medium text-kirri-900 mb-3">Contactos</h3>
                @forelse($entity->contacts as $contact)
                    <a href="{{ route('contacts.show', $contact) }}" class="block py-2 kirri-link">{{ $contact->name }}</a>
                @empty
                    <p class="text-kirri-500 text-sm">Sem contactos.</p>
                @endforelse
            </div>

            <div class="kirri-panel p-6">
                <h3 class="font-medium text-kirri-900 mb-3">Tickets Recentes</h3>
                @forelse($entity->tickets as $ticket)
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
