@extends('layouts.public')

@section('title', $service->name.' - Kocur Serwis Komputerowy')
@section('meta_description', \Illuminate\Support\Str::limit($service->description ?: 'Szczegóły usługi komputerowej: zakres, cena od oraz możliwość kontaktu w sprawie realizacji.', 155))

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        @include('public.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Start', 'url' => route('public.home')],
                ['label' => 'Usługi i cennik', 'url' => route('public.services')],
                ['label' => $service->name],
            ],
        ])

        <div class="grid gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-amber-300/20 bg-slate-950/70 p-7 lg:col-span-2">
                @if ($service->category)
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">{{ $service->category->name }}</p>
                @endif

                <h1 class="mt-3 text-4xl font-black leading-tight text-white">{{ $service->name }}</h1>

                @if ($service->price_from !== null)
                    <p class="mt-5 inline-flex rounded-md border border-amber-300/30 bg-amber-400/10 px-4 py-2 text-sm font-bold text-amber-100">
                        Cena od: {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                    </p>
                @else
                    <p class="mt-5 inline-flex rounded-md border border-white/15 bg-white/5 px-4 py-2 text-sm font-bold text-slate-300">
                        Wycena po kontakcie
                    </p>
                @endif

                <p class="mt-7 text-base leading-8 text-slate-200">
                    {{ $service->description ?: 'Szczegóły usługi są ustalane indywidualnie po krótkim kontakcie.' }}
                </p>

                @if ($service->long_description)
                    <div class="mt-7 space-y-4 text-sm leading-7 text-slate-300">
                        @foreach (preg_split('/\r\n|\r|\n/', trim($service->long_description)) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
            </article>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-amber-300/20 bg-black/55 p-5">
                    <h2 class="text-lg font-bold text-white">Masz podobny temat?</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">
                        Opisz sprzęt, objawy albo planowany zestaw. Po kontakcie ustalimy zakres, koszt i termin.
                    </p>

                    <a href="{{ route('public.contact') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-md bg-amber-400 px-4 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.22)] transition hover:bg-amber-300">
                        Opisz problem
                    </a>
                </div>

                <div class="rounded-2xl border border-amber-300/20 bg-slate-950/70 p-5">
                    <h2 class="text-lg font-bold text-white">Ważne przed usługą</h2>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                        <li>Wycena jest ustalana przed rozpoczęciem pracy.</li>
                        <li>Nie wymieniam części bez wcześniejszego potwierdzenia.</li>
                        <li>Przy danych na dysku warto wcześniej zrobić kopię zapasową.</li>
                    </ul>
                </div>
            </aside>
        </div>

        @if ($relatedServices->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-black text-white">Podobne usługi</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    @foreach ($relatedServices as $relatedService)
                        <a href="{{ route('public.services.show', $relatedService) }}" class="service-card block rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                            <p class="font-bold text-white">{{ $relatedService->name }}</p>
                            @if ($relatedService->price_from !== null)
                                <p class="mt-2 text-sm font-bold text-amber-200">
                                    Od {{ number_format($relatedService->price_from, 2, ',', ' ') }} PLN
                                </p>
                            @else
                                <p class="mt-2 text-sm text-slate-400">Wycena po kontakcie</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
