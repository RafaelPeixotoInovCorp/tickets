<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $contact->name ?? '') }}" required class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Função</label>
        <select name="contact_role_id" class="kirri-select w-full">
            <option value="">—</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected(old('contact_role_id', $contact->contact_role_id ?? '') == $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $contact->email ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Telefone</label>
        <input type="text" name="phone" value="{{ old('phone', $contact->phone ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Telemóvel</label>
        <input type="text" name="mobile" value="{{ old('mobile', $contact->mobile ?? '') }}" class="kirri-input w-full">
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-kirri-600 mb-1">Entidades</label>
    <select name="entity_ids[]" multiple class="kirri-select w-full" size="5">
        @foreach($entities as $entity)
            <option value="{{ $entity->id }}" @selected(in_array($entity->id, old('entity_ids', isset($contact) ? $contact->entities->pluck('id')->all() : [])))>{{ $entity->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-kirri-600 mb-1">Notas Internas</label>
    <textarea name="internal_notes" rows="3" class="kirri-input w-full">{{ old('internal_notes', $contact->internal_notes ?? '') }}</textarea>
</div>
