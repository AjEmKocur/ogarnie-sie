@extends('layouts.public')

@section('title', 'Usługi i cennik - Kocur Serwis Komputerowy')
@section('meta_description', 'Usługi komputerowe w Rzeszowie i okolicach: składanie PC, modernizacja, diagnostyka laptopów, instalacja systemu, konfiguracja sieci domowej i dojazd do klienta.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.82fr_1.18fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Usługi i cennik</p>
                <h1 class="mt-3 text-4xl font-black leading-tight text-white sm:text-5xl">
                    Konkretna pomoc komputerowa.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">
                    Wybierz kategorię albo opisz problem po swojemu. Ceny są orientacyjne, a dokładny zakres i koszt ustalam przed rozpoczęciem pracy.
                </p>
            </div>

            <div class="rounded-2xl border border-amber-300/20 bg-black/50 p-5">
                <p class="text-sm font-semibold text-white">Nie wiesz, którą usługę wybrać?</p>
                <p class="mt-2 text-sm leading-6 text-slate-300">
                    Napisz krótko, jaki masz sprzęt i co się dzieje. Dobiorę właściwy zakres po kontakcie.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.22)] transition hover:bg-amber-300">
                        Opisz problem
                    </a>
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-md border border-white/25 bg-black/30 px-5 py-3 text-sm font-bold text-slate-100 transition hover:border-amber-300/70 hover:bg-white/10">
                        Szybki kontakt
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-amber-300/20 bg-black/45 p-5 text-sm leading-7 text-slate-300">
            <p class="font-semibold text-white">Jak rozliczamy części?</p>
            <p class="mt-2">
                Przy składaniu i modernizacji najczęściej pomagam dobrać podzespoły, a klient kupuje części na swoje dane.
                Dzięki temu gwarancja i dokument zakupu są bezpośrednio po stronie klienta. Ewentualny zakup części przeze
                mnie jest ustalany indywidualnie przed realizacją.
            </p>
        </div>

        @if ($serviceCategories->isEmpty() && $uncategorizedServices->isEmpty())
            <article class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-7">
                <h2 class="text-2xl font-bold text-white">Oferta w przygotowaniu</h2>
                <p class="mt-3 text-slate-300">
                    Usługi będzie można uzupełnić w panelu administracyjnym.
                </p>
            </article>
        @endif

        <div class="mt-12 space-y-12">
            @foreach ($serviceCategories as $category)
                <section>
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <h2 class="mt-2 text-3xl font-black text-white">{{ $category->name }}</h2>
                            @if ($category->description)
                                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($category->services as $service)
                            <article class="service-card flex h-full flex-col rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                                <h3 class="text-xl font-bold text-white">{{ $service->name }}</h3>
                                <p class="mt-3 flex-1 text-sm leading-6 text-slate-300">
                                    {{ $service->description ?: 'Zakres usługi ustalany indywidualnie.' }}
                                </p>

                                @if ($service->price_from !== null)
                                    <p class="mt-5 text-sm font-bold text-amber-200">
                                        Cena od: {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                                    </p>
                                @else
                                    <p class="mt-5 text-sm font-bold text-slate-400">Wycena po kontakcie</p>
                                @endif

                                <div class="mt-5">
                                    <a href="{{ route('public.services.show', $service) }}" class="inline-flex items-center rounded-md border border-amber-300/40 px-4 py-2 text-xs font-bold uppercase tracking-wider text-amber-100 transition hover:border-amber-200 hover:bg-amber-400/10">
                                        Szczegóły
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if ($uncategorizedServices->isNotEmpty())
                <section>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-200">Pozostałe</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Pozostałe usługi</h2>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($uncategorizedServices as $service)
                            <article class="service-card flex h-full flex-col rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                                <h3 class="text-xl font-bold text-white">{{ $service->name }}</h3>
                                <p class="mt-3 flex-1 text-sm leading-6 text-slate-300">
                                    {{ $service->description ?: 'Zakres usługi ustalany indywidualnie.' }}
                                </p>

                                @if ($service->price_from !== null)
                                    <p class="mt-5 text-sm font-bold text-amber-200">
                                        Cena od: {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                                    </p>
                                @else
                                    <p class="mt-5 text-sm font-bold text-slate-400">Wycena po kontakcie</p>
                                @endif

                                <div class="mt-5">
                                    <a href="{{ route('public.services.show', $service) }}" class="inline-flex items-center rounded-md border border-amber-300/40 px-4 py-2 text-xs font-bold uppercase tracking-wider text-amber-100 transition hover:border-amber-200 hover:bg-amber-400/10">
                                        Szczegóły
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
@endsection
