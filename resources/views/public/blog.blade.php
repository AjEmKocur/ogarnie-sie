@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Aktualności</h1>
        <p class="mt-4 text-slate-300">Nowinki z praktyki serwisowej i świata IT.</p>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($posts as $post)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white/5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300/70">
                    <a href="{{ route('public.news.show', $post->slug) }}" class="block">
                        @if ($post->coverImageUrl())
                            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="h-52 w-full object-cover">
                        @else
                            <div class="flex h-52 w-full items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                        @endif
                    </a>
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-wider text-slate-400">{{ $post->published_at?->format('Y-m-d H:i') }}</p>
                        <h2 class="mt-2 text-xl font-bold leading-snug">
                            <a href="{{ route('public.news.show', $post->slug) }}" class="hover:text-blue-300">{{ $post->title }}</a>
                        </h2>
                        <p class="mt-3 text-sm text-slate-300">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 180) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('public.news.show', $post->slug) }}" class="inline-flex items-center rounded-md border border-blue-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 transition hover:bg-blue-500/10">
                                Czytaj więcej
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="rounded-xl border border-gray-200 bg-white p-5 md:col-span-2 xl:col-span-3">
                    <h2 class="mt-2 text-lg font-semibold">Brak aktualności</h2>
                    <p class="mt-3 text-sm text-slate-300">Dodaj wpisy i oznacz je jako opublikowane w panelu admina: CMS -> Aktualności.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
