@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="rounded-xl border border-gray-700 bg-slate-900/60 p-6">
            <h1 class="text-3xl font-bold">Usługi serwisowe</h1>
            <p class="mt-3 text-slate-300">
                Nie musisz znać się na technice. Opisz problem, a my zaproponujemy najlepsze rozwiązanie.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.cms.dashboard') }}" class="rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                            Centrum CMS
                        </a>
                    @else
                        <a href="{{ route('client.tickets.create') }}" class="rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                            Zgłoś usterkę
                        </a>
                        <a href="{{ route('public.contact') }}" class="rounded-md border border-gray-600 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                            Kontakt indywidualny
                        </a>
                    @endif
                @else
                    <a href="{{ route('login', ['return' => route('client.tickets.create')]) }}" class="rounded-md bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                        Zaloguj i zgłoś usterkę
                    </a>
                    <a href="{{ route('public.contact') }}" class="rounded-md border border-gray-600 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                        Kontakt indywidualny
                    </a>
                @endauth
            </div>
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-2">
            @forelse ($services as $service)
                <article class="service-card flex h-full flex-col rounded-xl border border-gray-700 bg-slate-900/60 p-5">
                    <h2 class="text-2xl font-semibold">{{ $service->name }}</h2>
                    <p class="mt-3 text-sm text-slate-300">
                        {{ $service->description ?: 'Opis usługi w przygotowaniu.' }}
                    </p>

                    @if ($service->price_from !== null)
                        <p class="mt-4 text-sm text-blue-300">
                            Cena od: {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('public.services.show', $service) }}" class="inline-flex items-center rounded-md border border-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                            O usłudze
                        </a>
                    </div>
                </article>
            @empty
                <article class="rounded-xl border border-gray-700 bg-slate-900/60 p-5 md:col-span-2">
                    <h2 class="text-xl font-semibold">Brak usług do wyświetlenia</h2>
                    <p class="mt-3 text-sm text-slate-300">
                        Dodaj usługi w panelu admina: CMS -> Usługi.
                    </p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
