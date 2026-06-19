<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Tickets Afixados</h1>
            <p class="text-sm text-kirri-500 mt-1">Tickets que marcou para acesso rápido</p>
        </div>
    </x-slot>

    <div class="kirri-panel overflow-hidden">
        @include('tickets._list', [
            'emptyTitle' => 'Nenhum ticket afixado',
            'emptyHint' => 'Afixe tickets na lista ou no detalhe para ver aqui',
        ])
        @if($tickets->hasPages())
            <div class="px-5 py-3 border-t border-kirri-200 bg-kirri-50/50">{{ $tickets->links() }}</div>
        @endif
    </div>
</x-app-layout>
