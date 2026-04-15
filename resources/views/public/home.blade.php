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

            <div class="rounded-2xl border border-gray-200 bg-slate-900/60 p-6 shadow-2xl">
                <p class="text-sm text-slate-400">Jak działamy</p>
                <ol class="mt-4 space-y-4 text-sm">
                    <li class="rounded-lg border border-gray-200 bg-white p-4">1. Zgłaszasz problem przez stronę.</li>
                    <li class="rounded-lg border border-gray-200 bg-white p-4">2. Dostajesz status i kosztorys.</li>
                    <li class="rounded-lg border border-gray-200 bg-white p-4">3. Realizujemy usługę i potwierdzamy zakończenie.</li>
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
