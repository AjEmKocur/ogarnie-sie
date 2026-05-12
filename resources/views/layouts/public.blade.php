<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/ogarnie-sie-logo.png') }}">
    <title>{{ $title ?? config('app.name', 'Ogarnie się') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
    <div class="min-h-screen">
        <header class="og-topbar sticky top-0 z-40 border-b border-gray-200 bg-slate-950/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('public.home') }}" class="flex items-center">
                    <x-application-logo class="h-8 w-auto" />
                </a>

                <nav class="hidden items-center gap-6 text-base font-medium md:flex">
                    <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Start</a>
                    <a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">O nas</a>
                    <a href="{{ route('public.services') }}" class="{{ request()->routeIs('public.services*') || request()->routeIs('public.pricing') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Usługi i cennik</a>
                    <a href="{{ route('public.testimonials') }}" class="{{ request()->routeIs('public.testimonials') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Opinie</a>
                    <a href="{{ route('public.news') }}" class="{{ request()->routeIs('public.news*') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Aktualności</a>
                    <a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Kontakt</a>
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        @php
                            $ticketNotifications = \App\Support\TicketNotificationCenter::forUser(auth()->user());
                        @endphp

                        <div data-ticket-notifications-root data-url="{{ route('notifications.tickets') }}">
                        <x-dropdown align="right" width="72">
                            <x-slot name="trigger">
                                <button data-ticket-bell class="relative inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold {{ (($ticketNotifications['total'] ?? 0) > 0) ? 'text-white drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]' : 'text-slate-400' }} hover:bg-slate-800" title="Powiadomienia">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17H9.143m5.714 0H18a1 1 0 0 0 1-1v-1.108a1 1 0 0 0-.293-.707L17 12.478V10a5 5 0 1 0-10 0v2.478l-1.707 1.707a1 1 0 0 0-.293.707V16a1 1 0 0 0 1 1h3.143m5.714 0a2.857 2.857 0 1 1-5.714 0"/>
                                    </svg>
                                    @if (($ticketNotifications['total'] ?? 0) > 0)
                                        <span data-ticket-notifications-badge style="background:#ef4444;color:#fff;border:1px solid rgba(255,255,255,.75);" class="absolute -right-2 -top-2 inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full px-1 text-[10px] font-bold leading-none shadow-sm">
                                            {{ min(99, (int) $ticketNotifications['total']) }}
                                        </span>
                                    @else
                                        <span data-ticket-notifications-badge style="background:#ef4444;color:#fff;border:1px solid rgba(255,255,255,.75);" class="absolute -right-2 -top-2 hidden h-[18px] min-w-[18px] items-center justify-center rounded-full px-1 text-[10px] font-bold leading-none shadow-sm">0</span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 text-xs uppercase tracking-wider text-slate-400">
                                    Powiadomienia
                                </div>
                                <div data-ticket-notifications-list>
                                    @forelse (($ticketNotifications['items'] ?? []) as $item)
                                        <x-dropdown-link :href="$item['url']">
                                            <div class="flex flex-col gap-1">
                                                <span class="font-semibold">{{ $item['title'] }}</span>
                                                @if (!empty($item['time']))
                                                    <span class="text-xs text-slate-400">{{ $item['time'] }}</span>
                                                @endif
                                            </div>
                                        </x-dropdown-link>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-slate-400">Brak nowych powiadomień.</div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>
                        </div>

                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="ms-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('dashboard')">Panel</x-dropdown-link>
                                @if (auth()->user()->isAdmin())
                                    <x-dropdown-link :href="route('admin.cms.dashboard')">Centrum CMS</x-dropdown-link>
                                    @if (auth()->user()->hasAdminPermission('tickets'))
                                        <x-dropdown-link :href="route('admin.tickets.index')">Zgłoszenia</x-dropdown-link>
                                    @endif
                                    @if (auth()->user()->hasAdminPermission('testimonials_moderation'))
                                        <x-dropdown-link :href="route('admin.testimonials.index')">Moderacja opinii</x-dropdown-link>
                                    @endif
                                    @if (auth()->user()->isMainAdmin())
                                        <x-dropdown-link :href="route('admin.team.index')">Operatorzy</x-dropdown-link>
                                    @endif
                                @else
                                    <x-dropdown-link :href="route('client.tickets.index')">Moje zgłoszenia</x-dropdown-link>
                                    @if (auth()->user()->hasClosedTicketsWithoutTestimonial())
                                        <x-dropdown-link :href="route('client.testimonials.create')">Wystaw opinię</x-dropdown-link>
                                    @endif
                                @endif
                                <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Wyloguj
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('public.contact') }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                            Szybki kontakt
                        </a>
                        <a href="{{ route('login', ['return' => url()->full()]) }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                            Logowanie
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            @if (session('status'))
                <div class="mx-auto mt-4 max-w-7xl px-5 sm:px-6 lg:px-8">
                    <div class="rounded-lg border border-green-400/40 bg-green-500/10 px-4 py-3 text-sm text-green-200">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="og-footer border-t border-gray-200">
            <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-3 px-5 py-6 text-sm text-slate-400 sm:flex-row sm:items-center sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Ogarnie się. Serwis komputerowy.</p>
                <p>Godziny pracy: Pn-Pt 9:00-18:00</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roots = document.querySelectorAll('[data-ticket-notifications-root]');
            if (!roots.length) {
                return;
            }

            const itemClass = 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-200 hover:bg-slate-800 focus:outline-none focus:bg-slate-800 transition duration-150 ease-in-out';

            const renderRoot = (root, payload) => {
                const bell = root.querySelector('[data-ticket-bell]');
                const badge = root.querySelector('[data-ticket-notifications-badge]');
                const list = root.querySelector('[data-ticket-notifications-list]');
                if (!bell || !badge || !list) return;

                const total = Number(payload?.total || 0);
                if (total > 0) {
                    bell.classList.add('text-white', 'drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]');
                    bell.classList.remove('text-slate-400');
                    badge.textContent = String(Math.min(99, total));
                    badge.classList.remove('hidden');
                    badge.classList.add('inline-flex');
                } else {
                    bell.classList.remove('text-white', 'drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]');
                    bell.classList.add('text-slate-400');
                    badge.textContent = '0';
                    badge.classList.add('hidden');
                    badge.classList.remove('inline-flex');
                }

                list.innerHTML = '';
                const items = Array.isArray(payload?.items) ? payload.items : [];
                if (!items.length) {
                    const empty = document.createElement('div');
                    empty.className = 'px-4 py-2 text-sm text-slate-400';
                    empty.textContent = 'Brak nowych powiadomień.';
                    list.appendChild(empty);
                    return;
                }

                items.forEach((item) => {
                    const link = document.createElement('a');
                    link.href = item.url || '#';
                    link.className = itemClass;

                    const wrap = document.createElement('div');
                    wrap.className = 'flex flex-col gap-1';

                    const title = document.createElement('span');
                    title.className = 'font-semibold';
                    title.textContent = item.title || 'Powiadomienie';
                    wrap.appendChild(title);

                    if (item.time) {
                        const time = document.createElement('span');
                        time.className = 'text-xs text-slate-400';
                        time.textContent = item.time;
                        wrap.appendChild(time);
                    }

                    link.appendChild(wrap);
                    list.appendChild(link);
                });
            };

            const refreshRoot = async (root) => {
                const url = root.getAttribute('data-url');
                if (!url) return;

                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (!response.ok) return;
                    const data = await response.json();
                    renderRoot(root, data);
                } catch (error) {
                    // Cicho pomijamy chwilowe błędy sieci.
                }
            };

            const refreshAll = () => roots.forEach((root) => void refreshRoot(root));

            setInterval(refreshAll, 15000);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    refreshAll();
                }
            });
        });
    </script>
</body>
</html>
