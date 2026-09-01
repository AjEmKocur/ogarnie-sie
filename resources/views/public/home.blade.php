@extends('layouts.public')

@section('title', 'Kocur Serwis Komputerowy - składanie i naprawa komputerów')
@section('meta_description', 'Składanie komputerów, diagnostyka laptopów, modernizacja sprzętu, instalacja systemów i pomoc z siecią domową w Jarosławiu i okolicach.')

@section('content')
    <section class="relative isolate overflow-hidden border-b border-white/10 bg-black">
        <div class="absolute inset-y-0 right-0 hidden w-[58%] lg:block">
            <img src="{{ asset('images/home-aquarium-pc.png') }}" alt="Komputer typu akwarium na biurku" class="h-full w-full object-cover object-center opacity-100">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/72 to-black/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/18"></div>
        </div>

        <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_10%,rgba(255,255,255,0.08),transparent_28%),radial-gradient(circle_at_78%_18%,rgba(245,158,11,0.12),transparent_28%)]"></div>

        <div class="relative mx-auto grid min-h-[660px] max-w-7xl items-center gap-10 px-5 py-16 sm:px-6 lg:grid-cols-[0.88fr_1.12fr] lg:px-8">
            <div class="max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-amber-200">Serwis komputerowy z pazurem</p>
                <h1 class="mt-5 text-4xl font-black leading-[1.02] text-white sm:text-5xl lg:text-[3.55rem]">
                    Składanie i naprawa komputerów bez kombinowania.
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">
                    Diagnozuję i naprawiam komputery oraz laptopy, składam zestawy PC, modernizuję sprzęt, instaluję systemy i pomagam z siecią domową. Lokalnie, konkretnie i bez wciskania niepotrzebnych części.
                </p>

                <div class="mt-9 flex flex-wrap gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.28)] transition hover:bg-amber-300">
                                Zgłoszenia
                            </a>
                        @else
                            <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.28)] transition hover:bg-amber-300">
                                Opisz problem
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.28)] transition hover:bg-amber-300">
                            Opisz problem
                        </a>
                    @endauth
                    <a href="{{ route('public.services') }}" class="inline-flex items-center justify-center rounded-md border border-white/30 bg-black/25 px-5 py-3 text-sm font-bold text-slate-100 transition hover:border-amber-300/70 hover:bg-white/10">
                        Zobacz usługi
                    </a>
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-md border border-amber-300/55 bg-amber-400/10 px-5 py-3 text-sm font-bold text-amber-100 transition hover:border-amber-200 hover:bg-amber-400/20">
                        Szybki kontakt
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 text-sm font-semibold text-slate-300">
                    <span class="text-amber-200">Jarosław i okolice</span>
                    <span class="text-slate-600">/</span>
                    <span>Dojazd do klienta</span>
                    <span class="text-slate-600">/</span>
                    <span>Wycena przed usługą</span>
                </div>

                <div class="mt-10 grid gap-3 text-sm text-slate-300 sm:grid-cols-3">
                    <div class="rounded-lg border border-amber-300/25 bg-black/55 p-4 shadow-[0_18px_45px_rgba(0,0,0,0.28)]">
                        <span class="mb-3 block h-1 w-10 rounded-full bg-amber-400"></span>
                        <p class="font-bold text-white">Składanie PC</p>
                        <p class="mt-1">Zestawy gamingowe, biurowe i estetyczne buildy na zamówienie.</p>
                    </div>
                    <div class="rounded-lg border border-amber-300/20 bg-black/55 p-4 shadow-[0_18px_45px_rgba(0,0,0,0.28)]">
                        <span class="mb-3 block h-1 w-10 rounded-full bg-amber-400"></span>
                        <p class="font-bold text-white">Modernizacja</p>
                        <p class="mt-1">Wymiana dysku, RAM-u, czyszczenie, poprawa temperatur i przyspieszenie sprzętu.</p>
                    </div>
                    <div class="rounded-lg border border-amber-300/20 bg-black/55 p-4 shadow-[0_18px_45px_rgba(0,0,0,0.28)]">
                        <span class="mb-3 block h-1 w-10 rounded-full bg-amber-400"></span>
                        <p class="font-bold text-white">Dojazd do klienta</p>
                        <p class="mt-1">Pomoc z komputerem, internetem, WiFi i drukarką na miejscu.</p>
                    </div>
                </div>
            </div>

            <div class="relative lg:hidden">
                <img src="{{ asset('images/home-aquarium-pc.png') }}" alt="Komputer typu akwarium na biurku" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-2xl shadow-black/50">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
            </div>
        </div>
    </section>

    <section class="og-section-band py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-6 lg:grid-cols-[0.75fr_1.25fr] lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Oferta</p>
                <h2 class="mt-3 text-3xl font-black text-white">Co mogę zrobić dla Ciebie?</h2>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    Pomagam przy składaniu zestawów PC, modernizacji sprzętu, diagnostyce komputerów i laptopów, instalacji systemów oraz podstawowej konfiguracji sieci domowych.
                </p>
                <a href="{{ route('public.services') }}" class="mt-7 inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.22)] transition hover:bg-amber-300">
                    Zobacz pełną ofertę
                </a>
            </div>

            <div class="grid gap-x-10 gap-y-6 sm:grid-cols-2">
                <article class="relative pl-5">
                    <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                    <h3 class="text-lg font-bold text-white">Składanie komputerów</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Dobór części, montaż, konfiguracja BIOS/UEFI i test stabilności.</p>
                </article>
                <article class="relative pl-5">
                    <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                    <h3 class="text-lg font-bold text-white">Modernizacja sprzętu</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Wymiana dysku, rozbudowa RAM, czyszczenie i klonowanie danych.</p>
                </article>
                <article class="relative pl-5">
                    <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                    <h3 class="text-lg font-bold text-white">Diagnostyka</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Sprawdzenie dysku, pamięci RAM i problemów z uruchamianiem.</p>
                </article>
                <article class="relative pl-5">
                    <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                    <h3 class="text-lg font-bold text-white">Systemy</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Instalacja systemu, sterowniki, aktualizacje i przygotowanie komputera.</p>
                </article>
                <article class="relative pl-5 sm:col-span-2">
                    <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                    <h3 class="text-lg font-bold text-white">Sieci domowe i dojazd</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Router, WiFi, repeater, switch, drukarka i prosta pomoc techniczna na miejscu.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Jak wygląda współpraca?</p>
                <h2 class="mt-3 text-3xl font-black text-white">Prosty proces, bez zgadywania.</h2>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    Najpierw ustalam problem, zakres i orientacyjny koszt. Dzięki temu przed rozpoczęciem prac wiadomo, czego dotyczy usługa i jaki jest plan działania.
                </p>
            </div>

            <ol class="grid gap-5 sm:grid-cols-2">
                <li>
                    <h3 class="text-lg font-bold text-white">Opis problemu</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">W formularzu podajesz sprzęt, objawy albo zakres pomocy, której potrzebujesz.</p>
                </li>
                <li>
                    <h3 class="text-lg font-bold text-white">Ustalenie zakresu</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Dopytuję o szczegóły i podaję orientacyjny koszt oraz możliwy termin.</p>
                </li>
                <li>
                    <h3 class="text-lg font-bold text-white">Realizacja usługi</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Wykonuję ustalone prace: montaż, diagnostykę, konfigurację systemu lub sieci.</p>
                </li>
                <li>
                    <h3 class="text-lg font-bold text-white">Podsumowanie prac</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Po zakończeniu przekazuję informację, co zostało zrobione i na co warto zwrócić uwagę.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="og-section-band py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Dojazd</p>
                <h2 class="mt-3 text-3xl font-black text-white">Pomoc techniczna u klienta.</h2>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    Przy prostszych tematach mogę dojechać do klienta, np. przy konfiguracji routera, WiFi, switcha, drukarki albo podstawowej diagnostyce.
                </p>
                <p class="mt-6 text-2xl font-black text-white">Jarosław i okolice</p>
                <p class="mt-2 text-sm text-slate-300">Dojazd jest ustalany indywidualnie przed usługą.</p>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Najczęściej wybierane</p>
                <h2 class="mt-3 text-3xl font-black text-white">Popularne usługi</h2>

                <div class="mt-6 grid gap-4">
                    @forelse ($featuredServices as $service)
                        <article class="relative pl-5">
                            <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                            <h3 class="font-bold text-white">{{ $service->name }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                {{ $service->description ?: 'Zakres ustalimy po krótkim opisie problemu.' }}
                            </p>
                        </article>
                    @empty
                        <article class="relative pl-5">
                            <span class="absolute left-0 top-2 h-2 w-2 rounded-full bg-amber-300"></span>
                            <h3 class="font-bold text-white">Zakres przed usługą</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Opisz sprzęt albo problem, a ustalimy orientacyjny koszt i możliwy termin.</p>
                        </article>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="og-panel-depth rounded-2xl border border-amber-300/20 bg-gradient-to-r from-white/10 via-black/90 to-amber-500/10 p-7 sm:p-9">
            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Nie wiesz od czego zacząć?</p>
                    <h2 class="mt-3 text-3xl font-black text-white">Napisz krótko, co chcesz ogarnąć.</h2>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-300">
                        Wystarczy opisać sprzęt, problem albo planowany zestaw. Dalej ustalimy, czy wystarczy szybka wiadomość, czy potrzebne będzie pełne zgłoszenie.
                    </p>
                </div>
                <a href="{{ route('public.contact') }}" class="inline-flex shrink-0 items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.24)] transition hover:bg-amber-300">
                    Szybki kontakt
                </a>
            </div>
        </div>
    </section>
@endsection
