<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-kirri-900 tracking-tight">Minha Conta</h1>
            <p class="text-sm text-kirri-500 mt-1">Gerir dados pessoais e segurança</p>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="flex flex-col sm:flex-row gap-6">
            <nav class="sm:w-56 shrink-0">
                <div class="kirri-panel p-2 space-y-0.5">
                    <a href="{{ route('profile.edit', ['tab' => 'profile']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium kirri-motion {{ $tab === 'profile' ? 'bg-kirri-primary text-white shadow-sm' : 'text-kirri-600 hover:bg-kirri-50 hover:shadow-sm active:scale-[0.98]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Dados pessoais
                    </a>
                    <a href="{{ route('profile.edit', ['tab' => 'password']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium kirri-motion {{ $tab === 'password' ? 'bg-kirri-primary text-white shadow-sm' : 'text-kirri-600 hover:bg-kirri-50 hover:shadow-sm active:scale-[0.98]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Alterar senha
                    </a>
                </div>
            </nav>

            <div class="flex-1 min-w-0">
                <div class="kirri-panel p-6">
                    @if($tab === 'profile')
                        @include('profile.partials.update-profile-information-form')
                    @else
                        @include('profile.partials.update-password-form')
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
