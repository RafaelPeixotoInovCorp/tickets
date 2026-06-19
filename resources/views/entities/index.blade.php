<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Entidades</h1>
                <p class="text-sm text-kirri-500 mt-1">Gerir empresas e organizações clientes</p>
            </div>
            <a href="{{ route('entities.create') }}" class="kirri-btn-primary shrink-0">Nova Entidade</a>
        </div>
    </x-slot>

    <div class="kirri-panel overflow-hidden">
        <table class="kirri-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>NIF</th>
                    <th>Email</th>
                    <th>Contactos</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entities as $entity)
                    <tr>
                        <td><a href="{{ route('entities.show', $entity) }}" class="kirri-link font-medium">{{ $entity->name }}</a></td>
                        <td>{{ $entity->nif ?? '—' }}</td>
                        <td>{{ $entity->email ?? '—' }}</td>
                        <td>{{ $entity->contacts_count }}</td>
                        <td>{{ $entity->tickets_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($entities->hasPages())
            <div class="kirri-table-footer">{{ $entities->links() }}</div>
        @endif
    </div>
</x-app-layout>
