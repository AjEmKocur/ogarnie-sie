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
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-amber-200">Serwis komputerowy</p>
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
                        <p class="mt-1">Pomoc z komputerem, internetem, Wi-Fi i drukarką na miejscu.</p>
                    </div>
                </div>
            </div>

            <div class="relative lg:hidden">
                <img src="{{ asset('images/home-aquarium-pc.png') }}" alt="Komputer typu akwarium na biurku" class="aspect-[4/3] w-full rounded-2xl object-cover shadow-2xl shadow-black/50">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Oferta na start</p>
            <h2 class="mt-3 text-3xl font-black text-white">Co mogę zrobić dla Ciebie?</h2>
            <p class="mt-4 text-base leading-7 text-slate-300">
                Na start skupiam się na usługach, które realnie wykonuję sam: składanie zestawów, modernizacja, diagnostyka, systemy i podstawowa konfiguracja sieci domowych.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
            <article class="service-card rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">01</p>
                <h3 class="mt-3 text-lg font-bold text-white">Składanie komputerów</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Dobór części, montaż, konfiguracja BIOS/UEFI i test stabilności.</p>
            </article>
            <article class="service-card rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">02</p>
                <h3 class="mt-3 text-lg font-bold text-white">Modernizacja sprzętu</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Wymiana dysku, rozbudowa RAM, czyszczenie i klonowanie danych.</p>
            </article>
            <article class="service-card rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">03</p>
                <h3 class="mt-3 text-lg font-bold text-white">Diagnostyka</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Sprawdzenie dysku, pamięci RAM i problemów z uruchamianiem.</p>
            </article>
            <article class="service-card rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">04</p>
                <h3 class="mt-3 text-lg font-bold text-white">Systemy</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Instalacja systemu, sterowniki, aktualizacje i przygotowanie komputera.</p>
            </article>
            <article class="service-card rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">05</p>
                <h3 class="mt-3 text-lg font-bold text-white">Sieci domowe</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Router, Wi-Fi, repeater, switch i podstawowa konfiguracja internetu.</p>
            </article>
        </div>
    </section>

    <section class="border-y border-white/10 bg-black/85">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-16 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Jak wygląda współpraca?</p>
                <h2 class="mt-3 text-3xl font-black text-white">Prosty proces, bez zgadywania.</h2>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    Najpierw ustalam z Tobą problem albo potrzebę, potem zakres i orientacyjny koszt. Dzięki temu wiesz, czego się spodziewać przed realizacją.
                </p>
            </div>

            <ol class="grid gap-4 md:grid-cols-2">
                <li class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-black">1</span>
                    <h3 class="mt-4 font-bold text-white">Opisujesz temat</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Piszesz, czy chodzi o nowy komputer, modernizację, problem ze sprzętem albo sieć.</p>
                </li>
                <li class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-black">2</span>
                    <h3 class="mt-4 font-bold text-white">Ustalam zakres</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Dopytuję o szczegóły i podaję orientacyjny koszt oraz możliwy termin.</p>
                </li>
                <li class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-black">3</span>
                    <h3 class="mt-4 font-bold text-white">Realizuję usługę</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Składam zestaw, diagnozuję sprzęt, konfiguruję system albo ogarniam sieć.</p>
                </li>
                <li class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-black">4</span>
                    <h3 class="mt-4 font-bold text-white">Potwierdzamy koniec</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Po wykonaniu usługi dostajesz informację, co zostało zrobione.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-8 px-5 py-16 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8">
        <div class="rounded-2xl border border-amber-300/20 bg-slate-950/70 p-7">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Dojazd</p>
            <h2 class="mt-3 text-3xl font-black text-white">Pomoc techniczna u klienta.</h2>
            <p class="mt-4 text-base leading-7 text-slate-300">
                Przy prostszych tematach mogę dojechać do klienta, np. przy konfiguracji routera, Wi-Fi, switcha, drukarki albo podstawowej diagnostyce.
            </p>
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-lg border border-amber-300/20 bg-black/45 p-4">
                    <p class="text-2xl font-black text-white">15 km</p>
                    <p class="mt-1 text-sm text-slate-300">orientacyjny promień od Jarosławia</p>
                </div>
                <div class="rounded-lg border border-amber-300/20 bg-black/45 p-4">
                    <p class="text-2xl font-black text-white">1 zł/km</p>
                    <p class="mt-1 text-sm text-slate-300">orientacyjny koszt dojazdu</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-300/20 bg-slate-950/70 p-7">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Najczęściej wybierane</p>
            <h2 class="mt-3 text-3xl font-black text-white">Usługi</h2>

            <div class="mt-6 grid gap-4">
                @forelse ($featuredServices as $service)
                    <article class="service-card rounded-xl border border-amber-300/20 bg-black/45 p-5">
                        <h3 class="font-bold text-white">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">
                            {{ $service->description ?: 'Zakres ustalany indywidualnie po kontakcie.' }}
                        </p>
                        <p class="mt-4 text-sm font-bold text-slate-400">Wycena indywidualna po kontakcie</p>
                    </article>
                @empty
                    <article class="rounded-xl border border-amber-300/20 bg-black/45 p-5">
                        <h3 class="font-bold text-white">Wycena po krótkim kontakcie</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Opisz sprzęt albo problem, a podam orientacyjny koszt i możliwy termin.</p>
                    </article>
                @endforelse
            </div>

            <a href="{{ route('public.services') }}" class="mt-6 inline-flex items-center justify-center rounded-md border border-amber-300/50 bg-amber-400/10 px-5 py-3 text-sm font-bold text-amber-100 transition hover:border-amber-200 hover:bg-amber-400/20">
                Zobacz usługi
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 pb-16 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-amber-300/20 bg-gradient-to-r from-white/10 via-slate-950/90 to-amber-500/10 p-7 sm:p-9">
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
