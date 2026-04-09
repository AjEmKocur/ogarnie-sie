@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">O nas</h1>
        <p class="mt-6 max-w-3xl text-lg text-slate-300">
            Ogarnie się to lokalny serwis komputerowy nastawiony na szybką diagnostykę, jasną komunikację i konkretne terminy.
            Łączymy podejście techniczne z prostym językiem dla klienta.
        </p>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Doświadczenie</p>
                <p class="mt-2 text-2xl font-bold">5+ lat</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Średni czas diagnozy</p>
                <p class="mt-2 text-2xl font-bold">24h</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Gwarancja serwisowa</p>
                <p class="mt-2 text-2xl font-bold">30 dni</p>
            </div>
        </div>
    </section>
@endsection

