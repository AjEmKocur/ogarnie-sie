@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Cennik</h1>
        <p class="mt-4 text-slate-300">
            Cennik jest automatycznie powiązany z usługami. Ceny są orientacyjne, a dokładna wycena odbywa się po diagnozie sprzętu.
        </p>

        <div class="mt-8 overflow-hidden rounded-xl border border-gray-700 bg-slate-900/60">
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-5 py-3 text-left uppercase tracking-wider">Usługa</th>
                        <th class="px-5 py-3 text-left uppercase tracking-wider">Cena od</th>
                        <th class="px-5 py-3 text-left uppercase tracking-wider">Szczegóły</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse ($services as $service)
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-100">{{ $service->name }}</p>
                                @if ($service->description)
                                    <p class="mt-1 text-xs text-slate-400">{{ $service->description }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-blue-300">
                                {{ number_format($service->price_from, 2, ',', ' ') }} PLN
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('public.services.show', $service) }}" class="inline-flex items-center whitespace-nowrap rounded-md border border-gray-600 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800">
                                    O usłudze
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-3" colspan="3">Brak usług z podaną ceną. Dodaj ceny w CMS -> Usługi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 rounded-xl border border-gray-700 bg-slate-900/60 p-5">
            <h2 class="text-xl font-semibold">Jak zamówić usługę?</h2>
            <p class="mt-2 text-slate-300">
                Najprościej przez zgłoszenie serwisowe: opisujesz objawy, a my dobieramy właściwe działania i finalną wycenę.
            </p>
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.cms.dashboard') }}" class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                        Centrum CMS
                    </a>
                @else
                    <a href="{{ route('client.tickets.create') }}" class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                        Utwórz zgłoszenie
                    </a>
                @endif
            @else
                <a href="{{ route('login', ['return' => route('client.tickets.create')]) }}" class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">
                    Zaloguj i utwórz zgłoszenie
                </a>
            @endauth
        </div>
    </section>
@endsection
