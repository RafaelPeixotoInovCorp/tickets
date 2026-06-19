<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-semibold tracking-tight text-kirri-900">Bem-vindo</h2>
        <p class="text-sm text-kirri-500 mt-1.5">Entre com o seu email para aceder ao painel</p>
    </div>

    @if (session('status'))
        <div class="kirri-login-alert kirri-login-alert-success mb-5">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-kirri-700 mb-1.5">Email</label>
            <div class="kirri-field">
                <svg class="kirri-field-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="nome@empresa.pt"
                       class="kirri-input kirri-input-with-icon py-2.5 px-3">
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-kirri-700 mb-1.5">Senha</label>
            <div class="kirri-field">
                <svg class="kirri-field-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="kirri-input kirri-input-with-icon py-2.5 px-3">
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between gap-3 pt-1">
            <label class="flex items-center gap-2.5 text-sm text-kirri-600 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="kirri-checkbox rounded border-kirri-300 text-kirri-primary focus:ring-kirri-accent">
                Lembrar-me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-kirri-primary hover:text-kirri-primary-dark kirri-motion shrink-0">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <button type="submit" class="kirri-btn-primary w-full py-3 text-base font-semibold mt-2">
            Entrar
        </button>
    </form>
</x-guest-layout>
