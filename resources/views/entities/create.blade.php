<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Nova Entidade</h1>
    </x-slot>

    <div class="max-w-2xl kirri-panel p-6">
        <form method="POST" action="{{ route('entities.store') }}" class="space-y-4">
            @csrf
            @include('entities._form')
            <div class="flex gap-3">
                <button type="submit" class="kirri-btn-primary">Criar</button>
                <a href="{{ route('entities.index') }}" class="kirri-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
