<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Editar Contacto</h1>
    </x-slot>

    <div class="max-w-2xl kirri-panel p-6">
        <form method="POST" action="{{ route('contacts.update', $contact) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('contacts._form', ['contact' => $contact])
            <div class="flex gap-3">
                <button type="submit" class="kirri-btn-primary">Guardar</button>
                <a href="{{ route('contacts.show', $contact) }}" class="kirri-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
