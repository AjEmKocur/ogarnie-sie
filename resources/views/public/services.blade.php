@extends('layouts.public')

@section('title', 'Usługi - Kocur Serwis Komputerowy')
@section('meta_description', 'Usługi komputerowe w Jarosławiu i okolicach: składanie PC, modernizacja, diagnostyka laptopów, instalacja systemu, konfiguracja sieci domowej i dojazd do klienta.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Usługi</p>
                <h1 class="mt-3 text-4xl font-black leading-tight text-white sm:text-5xl">
                    Konkretna pomoc komputerowa.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                    Sprawdź najczęstsze tematy albo opisz problem po swojemu. Dokładny zakres, termin i koszt ustalam indywidualnie przed rozpoczęciem pracy.
                </p>
            </div>
        </div>

        @if ($serviceCategories->isEmpty() && $uncategorizedServices->isEmpty())
            <article class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-7">
                <h2 class="text-2xl font-bold text-white">Usługi pojawią się wkrótce</h2>
                <p class="mt-3 text-slate-300">
                    W międzyczasie możesz opisać problem przez formularz kontaktowy.
                </p>
            </article>
        @endif

        <div class="mt-12 space-y-10">
            @foreach ($serviceCategories as $category)
                <section class="border-t border-amber-300/20 pt-7">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-4">
                            <p class="mt-1 text-sm font-black text-amber-300">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <div>
                                <h2 class="text-2xl font-black leading-tight text-white">{{ $category->name }}</h2>
                                @if ($category->description)
                                    <p class="mt-2 max-w-3xl text-sm leading-7 text-zinc-400">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 divide-y divide-white/10 border-y border-white/10 bg-white/[0.025]">
                        @foreach ($category->services as $service)
                            <article class="group py-4 transition hover:bg-amber-300/[0.035] sm:px-4">
                                <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
                                    <div class="min-w-0">
                                        <h3 class="text-[1.05rem] font-bold leading-snug text-zinc-50 transition group-hover:text-amber-100">{{ $service->name }}</h3>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 md:justify-end">
                                        @if ($service->price_from !== null)
                                            <p class="text-sm font-black uppercase tracking-wider text-amber-200">
                                                od {{ number_format((float) $service->price_from, 0, ',', ' ') }} zł
                                            </p>
                                        @endif
                                        <a href="{{ route('public.services.show', $service) }}" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-zinc-300 transition hover:border-amber-300/50 hover:text-amber-100">
                                            Szczegóły
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if ($uncategorizedServices->isNotEmpty())
                <section class="border-t border-amber-300/20 pt-7">
                    <div class="pb-2">
                        <div class="flex items-start gap-4">
                            <p class="mt-1 text-sm font-black text-amber-300">+</p>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">Pozostałe</p>
                                <h2 class="mt-2 text-2xl font-black leading-tight text-white">Pozostałe usługi</h2>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 divide-y divide-white/10 border-y border-white/10 bg-white/[0.025]">
                        @foreach ($uncategorizedServices as $service)
                            <article class="group py-4 transition hover:bg-amber-300/[0.035] sm:px-4">
                                <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
                                    <div class="min-w-0">
                                        <h3 class="text-[1.05rem] font-bold leading-snug text-zinc-50 transition group-hover:text-amber-100">{{ $service->name }}</h3>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 md:justify-end">
                                        @if ($service->price_from !== null)
                                            <p class="text-sm font-black uppercase tracking-wider text-amber-200">
                                                od {{ number_format((float) $service->price_from, 0, ',', ' ') }} zł
                                            </p>
                                        @endif
                                        <a href="{{ route('public.services.show', $service) }}" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-zinc-300 transition hover:border-amber-300/50 hover:text-amber-100">
                                            Szczegóły
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="mt-8 border-t border-white/10 pt-7">
            <p class="text-lg font-bold text-white">Brak usługi na liście?</p>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-300">
                Napisz krótko, jaki masz sprzęt i co się dzieje. Sprawdzę temat i powiem, czy mogę pomóc albo jaki zakres będzie miał sens.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.22)] transition hover:bg-amber-300">
                    Opisz problem
                </a>
                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-md border border-white/25 bg-black/30 px-5 py-3 text-sm font-bold text-slate-100 transition hover:border-amber-300/70 hover:bg-white/10">
                    Szybki kontakt
                </a>
            </div>
        </div>
    </section>
@endsection
