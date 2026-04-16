@extends('layouts.public')

@section('content')
    <article class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        @include('public.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Start', 'url' => route('public.home')],
                ['label' => 'Aktualności', 'url' => route('public.news')],
                ['label' => $post->title],
            ],
        ])

        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_360px]">
            <div>
                <h1 class="mt-2 text-4xl font-bold leading-tight">{{ $post->title }}</h1>
                <p class="mt-3 text-sm text-slate-300">Opublikowano: {{ $post->published_at?->format('Y-m-d H:i') }}</p>

                @if ($post->coverImageUrl())
                    <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="mt-6 h-auto w-full rounded-xl border border-gray-200 object-cover" loading="eager" fetchpriority="high">
                @endif

                @if ($post->excerpt)
                    <p class="mt-6 rounded-lg border border-gray-200 bg-white/5 p-4 text-slate-200">{{ $post->excerpt }}</p>
                @endif

                <div class="prose prose-invert mt-8 max-w-none leading-8 text-slate-100">
                    {!! nl2br(e($post->content ?: 'Brak treści wpisu.')) !!}
                </div>
            </div>

            <aside class="h-fit self-start rounded-xl border border-gray-200 bg-white/5 p-5">
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
    </article>
@endsection
