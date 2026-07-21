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
                    Wybierz kategorię albo opisz problem po swojemu. Dokładny zakres i koszt ustalam indywidualnie przed rozpoczęciem pracy.
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

        <div class="mt-12 grid gap-7 lg:grid-cols-2">
            @foreach ($serviceCategories as $category)
                <section class="rounded-2xl border border-amber-300/40 bg-black/85 p-6 shadow-[0_24px_70px_rgba(0,0,0,0.42)]">
                    <div class="border-b border-amber-300/25 pb-5">
                        <div class="flex items-start gap-4">
                            <p class="inline-flex size-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-xs font-black text-black">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <div>
                                <h2 class="text-2xl font-black leading-tight text-white">{{ $category->name }}</h2>
                                @if ($category->description)
                                    <p class="mt-3 max-w-3xl text-[15px] leading-7 text-slate-200">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ($category->services as $service)
                            <article class="rounded-xl bg-white/[0.035] p-4 transition hover:bg-white/[0.055]">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="border-l-2 border-amber-300/45 pl-4">
                                        <h3 class="text-base font-extrabold leading-snug text-white">{{ $service->name }}</h3>
                                        <p class="mt-2 text-[15px] leading-7 text-slate-300">
                                            {{ $service->description ?: 'Zakres usługi ustalany indywidualnie.' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('public.services.show', $service) }}" class="inline-flex shrink-0 items-center justify-center rounded-md border border-white/15 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-100 transition hover:border-amber-300/70 hover:bg-amber-400/10 hover:text-amber-100">
                                        Szczegóły
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if ($uncategorizedServices->isNotEmpty())
                <section class="rounded-2xl border border-amber-300/40 bg-black/85 p-6 shadow-[0_24px_70px_rgba(0,0,0,0.42)] lg:col-span-2">
                    <div class="border-b border-amber-300/25 pb-5">
                        <div class="flex items-start gap-4">
                            <p class="inline-flex size-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-xs font-black text-black">+</p>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">Pozostałe</p>
                                <h2 class="mt-2 text-2xl font-black leading-tight text-white">Pozostałe usługi</h2>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        @foreach ($uncategorizedServices as $service)
                            <article class="rounded-xl bg-white/[0.035] p-4 transition hover:bg-white/[0.055]">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="border-l-2 border-amber-300/45 pl-4">
                                        <h3 class="text-base font-extrabold leading-snug text-white">{{ $service->name }}</h3>
                                        <p class="mt-2 text-[15px] leading-7 text-slate-300">
                                            {{ $service->description ?: 'Zakres usługi ustalany indywidualnie.' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('public.services.show', $service) }}" class="inline-flex shrink-0 items-center justify-center rounded-md border border-white/15 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-100 transition hover:border-amber-300/70 hover:bg-amber-400/10 hover:text-amber-100">
                                        Szczegóły
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="mt-14 rounded-2xl border border-amber-300/20 bg-black/45 p-5 text-sm leading-7 text-slate-300">
            <p class="font-semibold text-white">Jak rozliczamy części?</p>
            <p class="mt-2">
                Przy składaniu i modernizacji najczęściej pomagam dobrać podzespoły, a klient kupuje części na swoje dane.
                Dzięki temu gwarancja i dokument zakupu są bezpośrednio po stronie klienta. Ewentualny zakup części przeze
                mnie jest ustalany indywidualnie przed realizacją.
            </p>
        </div>

        <div class="mt-6 rounded-2xl border border-amber-300/20 bg-black/50 p-6">
            <p class="text-lg font-bold text-white">Nie wiesz, którą usługę wybrać?</p>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-300">
                Napisz krótko, jaki masz sprzęt i co się dzieje. Dobiorę właściwy zakres po krótkiej rozmowie.
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
