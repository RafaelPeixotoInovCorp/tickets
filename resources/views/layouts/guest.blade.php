<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tickets — Login' }}</title>
    @include('partials.theme-init-guest')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen">
    <div class="min-h-screen flex">
        <div class="hidden lg:flex lg:w-[46%] xl:w-1/2 kirri-login-hero relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-kirri-primary via-kirri-primary-dark to-kirri-900"></div>
            <div class="absolute inset-0 kirri-login-grid opacity-60"></div>
            <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full bg-kirri-accent/20 blur-3xl translate-y-1/3 -translate-x-1/4"></div>

            <div class="relative z-10 flex flex-col justify-between p-10 xl:p-14 text-white w-full">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shadow-lg shadow-black/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-semibold leading-tight">Tickets</span>
                        <p class="text-[10px] uppercase tracking-wider font-medium text-white/60">Sistema de Gestão</p>
                    </div>
                </div>

                <div class="max-w-lg">
                    <h1 class="text-3xl xl:text-4xl font-semibold leading-tight tracking-tight mb-4">
                        Gestão de tickets, do pedido à resolução
                    </h1>
                    <p class="text-white/75 text-lg leading-relaxed mb-10">
                        Centralize conversas com clientes, organize por inbox e acompanhe cada pedido com histórico completo.
                    </p>

                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Conversas organizadas</p>
                                <p class="text-sm text-white/60 mt-0.5">Histórico, respostas e anexos num só lugar</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Inboxes por equipa</p>
                                <p class="text-sm text-white/60 mt-0.5">Comercial, suporte e RH com permissões dedicadas</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </span>
                            <div>
                                <p class="font-medium">Notificações por email</p>
                                <p class="text-sm text-white/60 mt-0.5">Mantenha clientes e operadores informados</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <p class="text-white/45 text-sm">Acesso seguro para operadores e clientes registados</p>
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center p-6 sm:p-10 lg:p-12 kirri-login-side">
            <div class="w-full max-w-[420px]">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-11 h-11 rounded-xl bg-kirri-primary flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-semibold leading-tight" style="color: var(--kirri-text)">Tickets</span>
                        <p class="text-[10px] uppercase tracking-wider font-medium" style="color: var(--kirri-text-muted)">Sistema de Gestão</p>
                    </div>
                </div>

                <div class="kirri-panel kirri-login-card p-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
