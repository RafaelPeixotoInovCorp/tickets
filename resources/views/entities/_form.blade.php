<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $entity->name ?? '') }}" required class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">NIF</label>
        <input type="text" name="nif" value="{{ old('nif', $entity->nif ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $entity->email ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Telefone</label>
        <input type="text" name="phone" value="{{ old('phone', $entity->phone ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Telemóvel</label>
        <input type="text" name="mobile" value="{{ old('mobile', $entity->mobile ?? '') }}" class="kirri-input w-full">
    </div>
    <div>
        <label class="block text-sm font-medium text-kirri-600 mb-1">Website</label>
        <input type="text" name="website" value="{{ old('website', $entity->website ?? '') }}" class="kirri-input w-full">
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-kirri-600 mb-1">Notas Internas</label>
    <textarea name="internal_notes" rows="3" class="kirri-input w-full">{{ old('internal_notes', $entity->internal_notes ?? '') }}</textarea>
</div>
