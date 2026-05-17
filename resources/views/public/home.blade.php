@extends('layouts.public')

@section('content')
    <section class="hero-surface relative overflow-hidden border-b border-gray-200">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-20 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div>
                <h1 class="text-4xl font-bold leading-tight sm:text-5xl">
                    Naprawa komputerów i laptopów, konfiguracja sieci oraz monitoring.
                </h1>
                <p class="mt-5 max-w-xl text-lg text-slate-300">
                    Zgłoś usterkę online, wrzuć zdjęcie problemu i śledź status realizacji w panelu klienta.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.cms.dashboard') }}" class="og-cta-primary rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                                Centrum CMS
                            </a>
                        @else
                            <a href="{{ route('client.tickets.create') }}" class="og-cta-primary rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                                Nowe zgłoszenie
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="og-cta-primary rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                            Załóż konto
                        </a>
                        <a href="{{ route('public.contact') }}" class="rounded-md border border-gray-300 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                            Szybki kontakt bez konta
                        </a>
                    @endauth

                    <a href="{{ route('public.services') }}" class="rounded-md border border-gray-300 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                        Zobacz usługi
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-400/40 bg-slate-900/70 p-6 shadow-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-200">Jak działamy?</p>
                <h2 class="mt-3 text-2xl font-bold text-white">Prosty proces obsługi zgłoszenia</h2>
                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Opisujesz problem, a dalszą diagnozę, wycenę i komunikację prowadzimy już w panelu klienta.
                </p>

                <ol class="mt-6 space-y-4">
                    <li class="flex gap-4 rounded-lg border border-blue-400/30 bg-slate-950/40 p-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-500/25">1</span>
                        <div>
                            <h3 class="font-semibold text-white">Zgłaszasz problem</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-300">Wypełniasz formularz i możesz dodać zdjęcia usterki.</p>
                        </div>
                    </li>
                    <li class="flex gap-4 rounded-lg border border-blue-400/30 bg-slate-950/40 p-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-500/25">2</span>
                        <div>
                            <h3 class="font-semibold text-white">Dostajesz informację zwrotną</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-300">Serwis aktualizuje status zgłoszenia i podaje szczegóły realizacji.</p>
                        </div>
                    </li>
                    <li class="flex gap-4 rounded-lg border border-blue-400/30 bg-slate-950/40 p-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-500/25">3</span>
                        <div>
                            <h3 class="font-semibold text-white">Odbierasz gotowe rozwiązanie</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-300">Po zakończeniu realizacji widzisz potwierdzenie i możesz wystawić opinię.</p>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold">Najczęściej wybierane usługi</h2>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse ($featuredServices as $service)
                <article class="service-card rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="font-semibold">{{ $service->name }}</h3>
                    <p class="mt-2 text-sm text-slate-300">
                        {{ $service->description ?: 'Opis usługi w przygotowaniu.' }}
                    </p>
                </article>
            @empty
                <article class="rounded-xl border border-gray-200 bg-white p-5 md:col-span-3">
                    <h3 class="font-semibold">Brak dodanych usług</h3>
                    <p class="mt-2 text-sm text-slate-300">Dodaj usługi w panelu admina: CMS -> Usługi.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
