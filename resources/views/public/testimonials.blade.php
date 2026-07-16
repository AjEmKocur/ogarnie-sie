@extends('layouts.public')

@section('title', 'Opinie klientów - Kocur Serwis Komputerowy')
@section('meta_description', 'Opinie klientów po realizacji usług komputerowych: składanie PC, modernizacja, diagnostyka, instalacja systemów i pomoc techniczna.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Opinie klientów</h1>

        @auth
            @if (!auth()->user()->isAdmin() && auth()->user()->hasClosedTicketsWithoutTestimonial())
                <div class="mt-6 rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-sm text-slate-300">Masz zakończone zgłoszenie. Możesz wystawić opinię.</p>
                    <a href="{{ route('client.testimonials.create') }}" class="mt-3 inline-flex rounded-md bg-amber-400 px-4 py-2 text-xs font-black uppercase tracking-wider text-black hover:bg-amber-300">
                        Wystaw opinię
                    </a>
                </div>
            @endif
        @else
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-sm text-slate-300">Chcesz dodać opinię po realizacji? Zaloguj się do konta klienta.</p>
                <a href="{{ route('login', ['return' => route('public.testimonials')]) }}" class="mt-3 inline-flex rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                    Zaloguj się
                </a>
            </div>
        @endauth

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            @forelse ($testimonials as $testimonial)
                <blockquote class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-yellow-300">{{ str_repeat('★', (int) $testimonial->rating) }}{{ str_repeat('☆', max(0, 5 - (int) $testimonial->rating)) }}</p>
                    <p class="mt-2">"{{ $testimonial->content }}"</p>
                    <footer class="mt-4 text-sm text-slate-400">- {{ $testimonial->user->name }}</footer>
                </blockquote>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white p-5 md:col-span-3">
                    <p>Brak opublikowanych opinii.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
