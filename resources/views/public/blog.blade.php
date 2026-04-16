@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Aktualności</h1>
        <p class="mt-4 text-slate-300">Nowinki z praktyki serwisowej i świata IT.</p>

        <div class="mt-10 grid gap-8 xl:grid-cols-[minmax(0,1fr)_360px]">
            <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3">
                @forelse ($posts as $post)
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white/5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300/70">
                        <a href="{{ route('public.news.show', $post->slug) }}" class="block">
                            <div class="relative h-36 w-full overflow-hidden">
                                @if ($post->coverImageUrl())
                                    <img
                                        src="{{ $post->coverImageUrl() }}"
                                        alt="{{ $post->title }}"
                                        class="h-36 w-full object-cover"
                                        loading="lazy"
                                        decoding="async"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden h-36 w-full flex items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                                @else
                                    <div class="flex h-36 w-full items-center justify-center bg-slate-900/70 text-sm text-slate-300">Brak zdjęcia</div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400">{{ $post->published_at?->format('Y-m-d H:i') }}</p>
                            <h2 class="mt-2 text-lg font-bold leading-snug">
                                <a href="{{ route('public.news.show', $post->slug) }}" class="hover:text-blue-300">{{ $post->title }}</a>
                            </h2>
                            <p class="mt-2 text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 96) }}</p>
                            <div class="mt-3">
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
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                    <h2 class="text-lg font-semibold">Najpopularniejsze</h2>
                </div>
                <p class="mt-1 text-xs text-slate-400">Na podstawie liczby wyświetleń.</p>

                <div class="mt-4 space-y-3">
                    @forelse (($popularNews ?? []) as $item)
                        <a href="{{ route('public.news.show', $item['slug']) }}" class="group flex items-start gap-3 rounded-lg border border-gray-200 bg-slate-900/30 p-2.5 hover:border-blue-300/60">
                            <div class="relative h-16 w-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                                @if (!empty($item['cover_image_url']))
                                    <img
                                        src="{{ $item['cover_image_url'] }}"
                                        alt="{{ $item['title'] }}"
                                        class="h-16 w-24 object-cover"
                                        loading="lazy"
                                        decoding="async"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden h-16 w-24 items-center justify-center bg-slate-900/70 text-[10px] text-slate-300">Brak</div>
                                @else
                                    <div class="flex h-16 w-24 items-center justify-center bg-slate-900/70 text-[10px] text-slate-300">Brak</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold leading-snug group-hover:text-blue-300">{{ \Illuminate\Support\Str::limit($item['title'], 72) }}</p>
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
