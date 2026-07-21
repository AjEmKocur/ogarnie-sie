@extends('layouts.public')

@section('title', $post->title.' - Realizacje - Kocur Serwis Komputerowy')
@section('meta_description', \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content ?: 'Realizacja usługi komputerowej wykonanej przez Kocur Serwis Komputerowy.'), 155))

@section('content')
    <article class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        @include('public.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Start', 'url' => route('public.home')],
                ['label' => 'Realizacje', 'url' => route('public.news')],
                ['label' => $post->title],
            ],
        ])

        <div class="max-w-4xl">
            <h1 class="mt-2 text-4xl font-bold leading-tight">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-slate-300">Opublikowano: {{ $post->published_at?->format('Y-m-d H:i') }}</p>

            @if ($post->coverImageUrl())
                <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="mt-6 h-auto w-full rounded-xl border border-amber-300/20 object-cover" loading="eager" fetchpriority="high">
            @endif

            @if ($post->excerpt)
                <p class="mt-6 rounded-lg border border-amber-300/20 bg-white/5 p-4 text-slate-200">{{ $post->excerpt }}</p>
            @endif

            <div class="prose prose-invert mt-8 max-w-none leading-8 text-slate-100">
                {!! nl2br(e($post->content ?: 'Brak treści wpisu.')) !!}
            </div>
        </div>
    </article>
@endsection
