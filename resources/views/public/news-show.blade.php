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

        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div>
                <h1 class="mt-2 text-4xl font-bold leading-tight">{{ $post->title }}</h1>
                <p class="mt-3 text-sm text-slate-300">Opublikowano: {{ $post->published_at?->format('Y-m-d H:i') }}</p>

                @if ($post->coverImageUrl())
                    <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="mt-6 h-auto w-full rounded-xl border border-gray-200 object-cover">
                @endif

                @if ($post->excerpt)
                    <p class="mt-6 rounded-lg border border-gray-200 bg-white/5 p-4 text-slate-200">{{ $post->excerpt }}</p>
                @endif

                <div class="prose prose-invert mt-8 max-w-none leading-8 text-slate-100">
                    {!! nl2br(e($post->content ?: 'Brak treści wpisu.')) !!}
                </div>
            </div>

            <aside class="rounded-xl border border-gray-200 bg-white/5 p-5">
                <h2 class="text-lg font-semibold">Najpopularniejsze</h2>
                <p class="mt-1 text-xs text-slate-400">Na podstawie liczby wyświetleń.</p>

                <div class="mt-4 space-y-3">
                    @forelse (($popularNews ?? []) as $item)
                        <a href="{{ route('public.news.show', $item['slug']) }}" class="block rounded-lg border border-gray-200 bg-slate-900/40 p-3 hover:border-blue-300/60">
                            <p class="text-sm font-semibold leading-snug">{{ $item['title'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">Wyświetlenia: {{ $item['views'] }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400">Brak danych popularności jeszcze.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </article>
@endsection
