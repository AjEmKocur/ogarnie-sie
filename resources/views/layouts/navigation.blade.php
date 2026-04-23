@php
    $ticketNotifications = \App\Support\TicketNotificationCenter::forUser(Auth::user());
@endphp

<nav class="og-topbar sticky top-0 z-40 border-b border-gray-200 bg-slate-950/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('public.home') }}" class="flex items-center">
            <x-application-logo class="h-8 w-auto" />
        </a>

        <div class="hidden items-center gap-6 text-base font-medium md:flex">
            <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Start</a>
            <a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">O nas</a>
            <a href="{{ route('public.services') }}" class="{{ request()->routeIs('public.services*') || request()->routeIs('public.pricing') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Usługi i cennik</a>
            <a href="{{ route('public.testimonials') }}" class="{{ request()->routeIs('public.testimonials') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Opinie</a>
            <a href="{{ route('public.news') }}" class="{{ request()->routeIs('public.news*') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Aktualności</a>
            <a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'text-blue-300' : 'text-slate-300 hover:text-white' }}">Kontakt</a>
        </div>

        <div class="flex items-center gap-2">
            <div data-ticket-notifications-root data-url="{{ route('notifications.tickets') }}">
            <x-dropdown align="right" width="72">
                <x-slot name="trigger">
                    <button class="relative inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800" title="Powiadomienia">
                        <span class="text-base leading-none">🔔</span>
                        @if (($ticketNotifications['total'] ?? 0) > 0)
                            <span data-ticket-notifications-badge class="absolute -right-2 -top-2 inline-flex min-h-[18px] min-w-[18px] items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-bold text-slate-950">
                                {{ min(99, (int) $ticketNotifications['total']) }}
                            </span>
                        @else
                            <span data-ticket-notifications-badge class="absolute -right-2 -top-2 hidden min-h-[18px] min-w-[18px] items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-bold text-slate-950">0</span>
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
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ms-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('dashboard')">Panel</x-dropdown-link>

                    @if (Auth::user()->isAdmin())
                        <x-dropdown-link :href="route('admin.cms.dashboard')">Centrum CMS</x-dropdown-link>
                        @if (Auth::user()->hasAdminPermission('tickets'))
                            <x-dropdown-link :href="route('admin.tickets.index')">Zgłoszenia</x-dropdown-link>
                        @endif
                        @if (Auth::user()->hasAdminPermission('testimonials_moderation'))
                            <x-dropdown-link :href="route('admin.testimonials.index')">Moderacja opinii</x-dropdown-link>
                        @endif
                        @if (Auth::user()->isMainAdmin())
                            <x-dropdown-link :href="route('admin.team.index')">Operatorzy</x-dropdown-link>
                        @endif
                    @else
                        <x-dropdown-link :href="route('client.tickets.index')">Moje zgłoszenia</x-dropdown-link>
                        <x-dropdown-link :href="route('client.contact.index')">Wiadomosci kontaktowe</x-dropdown-link>
                        @if (Auth::user()->hasClosedTicketsWithoutTestimonial())
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
        </div>
    </div>
</nav>
