<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tickets' }}</title>
    @include('partials.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        <aside class="kirri-sidebar w-[260px] border-r flex flex-col shrink-0">
            <div class="px-4 py-3 border-b border-kirri-200 shrink-0 overflow-visible" x-data="{ open: false }">
                <div class="relative">
                    <button type="button" @click="open = !open" class="kirri-hover-surface w-full flex items-center gap-3 rounded-xl p-2 active:scale-[0.98]">
                        <div class="w-9 h-9 rounded-full bg-kirri-primary text-white flex items-center justify-center text-sm font-semibold shrink-0 kirri-motion shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-semibold truncate" style="color: var(--kirri-text)">{{ auth()->user()->name }}</p>
                            <p class="text-xs" style="color: var(--kirri-text-muted)">{{ auth()->user()->isOperator() ? 'Agente' : 'Cliente' }}</p>
                        </div>
                        <svg class="w-4 h-4 shrink-0 kirri-chevron" style="color: var(--kirri-text-muted)" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="kirri-dropdown-panel kirri-account-dropdown py-1.5 rounded-xl"
                    >
                        <a href="{{ route('profile.edit', ['tab' => 'profile']) }}" class="kirri-menu-item">Dados pessoais</a>
                        <a href="{{ route('profile.edit', ['tab' => 'password']) }}" class="kirri-menu-item">Alterar senha</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="kirri-menu-item w-full text-left">Sair</button>
                        </form>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
                @php
                    $sidebarUser = auth()->user();
                    $pinnedTicketsCount = $sidebarUser->pinnedTickets()
                        ->when($sidebarUser->isOperator(), fn ($q) => $q->whereIn('inbox_id', $sidebarUser->accessibleInboxIds()))
                        ->when($sidebarUser->isClient(), fn ($q) => $q->whereIn('entity_id', $sidebarUser->accessibleEntityIds()))
                        ->count();
                @endphp
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider" style="color: var(--kirri-text-muted)">Menu</p>
                    <div class="space-y-0.5">
                        <x-kirri.nav-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </x-slot:icon>
                            Dashboard
                        </x-kirri.nav-item>
                        <x-kirri.nav-item :href="route('tickets.index')" :active="request()->routeIs(['tickets.index', 'tickets.create', 'tickets.show'])">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </x-slot:icon>
                            Tickets
                        </x-kirri.nav-item>
                        <x-kirri.nav-item :href="route('tickets.pinned')" :active="request()->routeIs('tickets.pinned')">
                            <x-slot:icon>
                                <x-icons.pin class="w-5 h-5" />
                            </x-slot:icon>
                            <span class="flex items-center justify-between gap-2 w-full min-w-0">
                                <span class="truncate">Afixados</span>
                                @if($pinnedTicketsCount > 0)
                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded-md kirri-badge-default shrink-0">{{ $pinnedTicketsCount }}</span>
                                @endif
                            </span>
                        </x-kirri.nav-item>
                        @if(auth()->user()->isOperator())
                            <x-kirri.nav-item :href="route('entities.index')" :active="request()->routeIs('entities.*')">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </x-slot:icon>
                                Entidades
                            </x-kirri.nav-item>
                            <x-kirri.nav-item :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </x-slot:icon>
                                Contactos
                            </x-kirri.nav-item>
                        @endif
                    </div>
                </div>

                @php
                    $sidebarInboxes = auth()->user()->isOperator()
                        ? auth()->user()->inboxes()->withCount('tickets')->get()
                        : \App\Models\Inbox::where('is_active', true)->get();
                @endphp
                @if($sidebarInboxes->count())
                    <div>
                        <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider" style="color: var(--kirri-text-muted)">Inboxes</p>
                        <div class="space-y-0.5">
                            @foreach($sidebarInboxes as $inbox)
                                <a href="{{ route('tickets.index', ['inbox' => $inbox->id]) }}"
                                   class="kirri-hover-surface flex items-center justify-between px-3 py-2 text-sm active:scale-[0.98] {{ request('inbox') == $inbox->id ? 'kirri-inbox-active font-medium shadow-sm' : '' }}"
                                   style="color: {{ request('inbox') == $inbox->id ? 'var(--kirri-primary)' : 'var(--kirri-text-secondary)' }}">
                                    <span class="flex items-center gap-2 truncate">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $inbox->color }}"></span>
                                        <span class="truncate">{{ $inbox->name }}</span>
                                    </span>
                                    @if(isset($inbox->tickets_count))
                                        <span class="text-xs font-medium px-1.5 py-0.5 rounded-md kirri-badge-default">{{ $inbox->tickets_count }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="kirri-header h-16 border-b px-4 sm:px-6 shrink-0 flex items-center gap-3 sm:gap-4">
                <div class="shrink-0 w-9 flex items-center justify-center">
                    <button type="button" onclick="window.kirriToggleTheme()" class="kirri-theme-toggle-header" title="Alternar tema" aria-label="Alternar tema">
                        <svg class="w-4 h-4 shrink-0 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="w-4 h-4 shrink-0 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 min-w-0 flex justify-center px-2">
                    <form action="{{ route('tickets.index') }}" method="GET" class="w-full max-w-xl">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--kirri-text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="search" name="search" value="{{ request('search') }}"
                                   placeholder="Pesquisar tickets, entidades, emails..."
                                   class="kirri-input kirri-input-search w-full pl-10 py-2.5">
                        </div>
                    </form>
                </div>

                <div class="shrink-0 w-auto flex items-center justify-end">
                    @unless(request()->routeIs('tickets.create'))
                        <a href="{{ route('tickets.create') }}" class="kirri-btn-primary whitespace-nowrap">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="hidden sm:inline">Novo Ticket</span>
                            <span class="sm:hidden">Novo</span>
                        </a>
                    @endunless
                    @isset($headerActions)
                        {{ $headerActions }}
                    @endisset
                </div>
            </header>

            @if(session('success'))
                <div class="kirri-success-banner border-b px-6 py-2.5 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <main class="flex-1 p-6 overflow-auto">
                @isset($header)
                    <div class="mb-6">{{ $header }}</div>
                @endisset
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
