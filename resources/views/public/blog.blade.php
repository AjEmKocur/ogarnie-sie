@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Aktualności</h1>
        <p class="mt-4 text-slate-300">Nowinki z praktyki serwisowej i świata IT.</p>

        <div class="mt-10 grid gap-8 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($posts as $post)
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white/5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300/70">
                        <a href="{{ route('public.news.show', $post->slug) }}" class="block">
                            <div class="relative h-52 w-full overflow-hidden">
                                @if ($post->coverImageUrl())
                                    <img
                                        src="{{ $post->coverImageUrl() }}"
                                        alt="{{ $post->title }}"
                                        class="h-52 w-full object-cover"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden h-52 w-full flex items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                                @else
                                    <div class="flex h-52 w-full items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                                @endif
                            </div>
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
                    <article class="rounded-xl border border-gray-200 bg-white p-5 md:col-span-2">
                        <h2 class="mt-2 text-lg font-semibold">Brak aktualności</h2>
                        <p class="mt-3 text-sm text-slate-300">Dodaj wpisy i oznacz je jako opublikowane w panelu admina: CMS -> Aktualności.</p>
                    </article>
                @endforelse
            </div>

            <aside class="rounded-xl border border-gray-200 bg-white/5 p-5">
                <h2 class="text-lg font-semibold">Najpopularniejsze</h2>
                <p class="mt-1 text-xs text-slate-400">Na podstawie liczby wyświetleń.</p>

                <div class="mt-4 space-y-3">
                    @forelse (($popularNews ?? []) as $item)
                        <a href="{{ route('public.news.show', $item['slug']) }}" class="block overflow-hidden rounded-lg border border-gray-200 bg-slate-900/40 hover:border-blue-300/60">
                            <div class="relative h-24 w-full overflow-hidden">
                                @if (!empty($item['cover_image_url']))
                                    <img
                                        src="{{ $item['cover_image_url'] }}"
                                        alt="{{ $item['title'] }}"
                                        class="h-24 w-full object-cover"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden h-24 w-full flex items-center justify-center bg-slate-900/70 text-xs text-slate-300">Brak zdjęcia</div>
                                @else
                                    <div class="flex h-24 w-full items-center justify-center bg-slate-900/70 text-xs text-slate-300">Brak zdjęcia</div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-semibold leading-snug">{{ $item['title'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">Wyświetlenia: {{ $item['views'] }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400">Brak danych popularności jeszcze.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>
@endsection
