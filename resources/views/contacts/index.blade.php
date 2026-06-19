<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Contactos</h1>
                <p class="text-sm text-kirri-500 mt-1">Gerir pessoas associadas às entidades</p>
            </div>
            <a href="{{ route('contacts.create') }}" class="kirri-btn-primary shrink-0">Novo Contacto</a>
        </div>
    </x-slot>

    <div class="kirri-panel overflow-hidden">
        <table class="kirri-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Função</th>
                    <th>Email</th>
                    <th>Entidades</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td><a href="{{ route('contacts.show', $contact) }}" class="kirri-link font-medium">{{ $contact->name }}</a></td>
                        <td>{{ $contact->contactRole?->name ?? '—' }}</td>
                        <td>{{ $contact->email ?? '—' }}</td>
                        <td>{{ $contact->entities->pluck('name')->join(', ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($contacts->hasPages())
            <div class="kirri-table-footer">{{ $contacts->links() }}</div>
        @endif
    </div>
</x-app-layout>
