@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <article class="rounded-xl border border-gray-700 bg-slate-900/60 p-6 lg:col-span-2">
                <a href="{{ route('public.services') }}" class="text-sm text-blue-300 hover:text-blue-200">
                    <- Wróć do usług
                </a>

                <h1 class="mt-3 text-3xl font-bold">{{ $service->name }}</h1>

                @if ($service->price_from !== null)
                    <p class="mt-4 text-lg text-blue-300">
                        Cena od: {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                    </p>
                @endif

                <p class="mt-6 text-slate-200">
                    {{ $service->description ?: 'Szczegóły usługi są w przygotowaniu.' }}
                </p>

                @if ($service->long_description)
                    <div class="mt-6 space-y-4 text-slate-300">
                        @foreach (preg_split('/\r\n|\r|\n/', trim($service->long_description)) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
            </article>

            <aside class="space-y-4">
                <div class="rounded-xl border border-blue-700/40 bg-blue-950/30 p-5">
                    <h2 class="text-lg font-semibold">Masz podobny problem?</h2>
                    <p class="mt-2 text-sm text-slate-300">
                        Opisz objawy w zgłoszeniu. Nie musisz samodzielnie wybierać usługi - dobierzemy najlepsze rozwiązanie.
                    </p>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.cms.dashboard') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                                Centrum CMS
                            </a>
                        @else
                            <a href="{{ route('client.tickets.create') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                                Utwórz zgłoszenie
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login', ['return' => route('client.tickets.create')]) }}" class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                            Zaloguj i utwórz zgłoszenie
                        </a>
                    @endauth
                </div>

                <div class="rounded-xl border border-gray-700 bg-slate-900/60 p-5">
                    <h2 class="text-lg font-semibold">Kontakt</h2>
                    <p class="mt-2 text-sm text-slate-300">
                        Masz niestandardowy problem? Opisz go, a przygotujemy indywidualną wycenę.
                    </p>
                    <a href="{{ route('public.contact') }}" class="mt-4 inline-flex items-center rounded-md border border-gray-600 px-4 py-2 text-sm font-semibold text-slate-100 hover:bg-slate-800">
                        Napisz do nas
                    </a>
                </div>
            </aside>
        </div>

        @if ($relatedServices->isNotEmpty())
            <div class="mt-10">
                <h2 class="text-2xl font-semibold">Podobne usługi</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    @foreach ($relatedServices as $relatedService)
                        <a href="{{ route('public.services.show', $relatedService) }}" class="block rounded-xl border border-gray-700 bg-slate-900/60 p-4 hover:border-blue-500/50">
                            <p class="font-semibold">{{ $relatedService->name }}</p>
                            @if ($relatedService->price_from !== null)
                                <p class="mt-2 text-sm text-blue-300">
                                    Od {{ number_format($relatedService->price_from, 2, ',', ' ') }} PLN
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
