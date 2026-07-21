@extends('layouts.public')

@section('title', 'Realizacje - Kocur Serwis Komputerowy')
@section('meta_description', 'Realizacje serwisowe, modernizacje komputerów, składanie zestawów PC oraz przykłady wykonanych prac.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Realizacje</h1>
        <p class="mt-4 max-w-2xl text-slate-300">
            Przykłady wykonanych prac, modernizacji i zestawów przed oraz po realizacji. Każdy wpis może zawierać zdjęcie, krótki opis problemu, wykonane prace i efekt końcowy.
        </p>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($posts as $post)
                <article class="overflow-hidden rounded-xl border border-amber-300/20 bg-white/5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300/70">
                    <a href="{{ route('public.news.show', $post->slug) }}" class="block">
                        <div class="relative h-44 w-full overflow-hidden">
                            @if ($post->coverImageUrl())
                                <img
                                    src="{{ $post->coverImageUrl() }}"
                                    alt="{{ $post->title }}"
                                    class="h-44 w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                    onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                >
                                <div class="hidden h-44 w-full flex items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                            @else
                                <div class="flex h-44 w-full items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400">{{ $post->published_at?->format('Y-m-d H:i') }}</p>
                        <h2 class="mt-2 text-lg font-bold leading-snug">
                            <a href="{{ route('public.news.show', $post->slug) }}" class="hover:text-amber-200">{{ $post->title }}</a>
                        </h2>
                        <p class="mt-2 text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 120) }}</p>
                        <div class="mt-3">
                            <a href="{{ route('public.news.show', $post->slug) }}" class="inline-flex items-center rounded-md border border-amber-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 transition hover:bg-amber-400/10">
                                Zobacz realizację
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5 md:col-span-2">
                    <h2 class="mt-2 text-lg font-semibold text-white">Brak realizacji</h2>
                    <p class="mt-3 text-sm text-slate-300">Dodaj wpisy i oznacz je jako opublikowane w panelu admina.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
