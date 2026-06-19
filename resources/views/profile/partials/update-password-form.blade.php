<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-kirri-900">Alterar senha</h2>
        <p class="mt-1 text-sm text-kirri-500">Use uma senha forte e única para proteger a sua conta.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-kirri-700 mb-1">Senha atual</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="kirri-input">
            @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-kirri-700 mb-1">Nova senha</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="kirri-input">
            @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-kirri-700 mb-1">Confirmar nova senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="kirri-input">
            @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="kirri-btn-primary">Atualizar senha</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium">
                    Senha atualizada.
                </p>
            @endif
        </div>
    </form>
</section>
