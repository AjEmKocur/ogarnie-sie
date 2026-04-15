@extends('layouts.public')

@section('content')
    <article class="mx-auto max-w-4xl px-5 py-16 sm:px-6 lg:px-8">
        @include('public.partials.breadcrumbs', [
            'items' => [
                ['label' => 'Start', 'url' => route('public.home')],
                ['label' => 'Aktualności', 'url' => route('public.news')],
                ['label' => $post->title],
            ],
        ])

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
    </article>
@endsection
