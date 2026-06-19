<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Novo Ticket</h1>
            <p class="text-sm text-kirri-500 mt-1">Criar um novo pedido de suporte</p>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="kirri-panel p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-kirri-700 mb-1">Assunto *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required class="kirri-input">
                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-kirri-700 mb-1">Inbox *</label>
                    <select name="inbox_id" id="inbox_id" required class="kirri-select w-full">
                        @foreach($inboxes as $inbox)
                            <option value="{{ $inbox->id }}" @selected(old('inbox_id') == $inbox->id)>{{ $inbox->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-kirri-700 mb-1">Tipo *</label>
                    <select name="ticket_type_id" id="ticket_type_id" required class="kirri-select w-full">
                        @foreach($inboxes as $inbox)
                            @foreach($inbox->ticketTypes as $type)
                                <option value="{{ $type->id }}" data-inbox="{{ $inbox->id }}" @selected(old('ticket_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-kirri-700 mb-1">Entidade *</label>
                    <select name="entity_id" required class="kirri-select w-full">
                        @foreach($entities as $entity)
                            <option value="{{ $entity->id }}" @selected(old('entity_id') == $entity->id)>{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($user->isOperator())
                    <div>
                        <label class="block text-sm font-medium text-kirri-700 mb-1">Contacto Criador *</label>
                        <select name="contact_id" required class="kirri-select w-full">
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}" @selected(old('contact_id') == $contact->id)>{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="contact_id" value="{{ $user->contact_id }}">
                @endif
            </div>

            @if($user->isOperator())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-kirri-700 mb-1">Agente</label>
                        <select name="operator_id" class="kirri-select w-full">
                            <option value="">Sem atribuição</option>
                            @foreach($operators as $op)
                                <option value="{{ $op->id }}" @selected(old('operator_id') == $op->id)>{{ $op->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-kirri-700 mb-1">Conhecimento (CC)</label>
                        <input type="text" name="cc_emails" value="{{ old('cc_emails') }}" placeholder="email1@exemplo.pt, email2@exemplo.pt" class="kirri-input">
                    </div>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-kirri-700 mb-1">Conhecimento (CC)</label>
                    <input type="text" name="cc_emails" value="{{ old('cc_emails') }}" placeholder="email1@exemplo.pt, email2@exemplo.pt" class="kirri-input">
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-kirri-700 mb-1">Mensagem *</label>
                <textarea name="body" rows="8" required class="kirri-input resize-none">{{ old('body') }}</textarea>
                @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-kirri-700 mb-1">Anexos</label>
                <input type="file" name="attachments[]" multiple class="text-sm text-kirri-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-kirri-50 file:text-kirri-700 hover:file:bg-kirri-100">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="kirri-btn-primary">Criar Ticket</button>
                <a href="{{ route('tickets.index') }}" class="kirri-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('inbox_id').addEventListener('change', function() {
            const inboxId = this.value;
            const typeSelect = document.getElementById('ticket_type_id');
            typeSelect.querySelectorAll('option').forEach(opt => {
                opt.hidden = opt.dataset.inbox !== inboxId;
            });
            const firstVisible = typeSelect.querySelector('option:not([hidden])');
            if (firstVisible) typeSelect.value = firstVisible.value;
        });
        document.getElementById('inbox_id').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
