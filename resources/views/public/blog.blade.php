@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Blog / Aktualności</h1>
        <p class="mt-4 text-slate-300">Nowinki z praktyki serwisowej i świata IT.</p>

        <div class="mt-10 grid gap-5 md:grid-cols-3">
            @forelse ($posts as $post)
                <article class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400">{{ $post->published_at?->format('Y-m-d') }}</p>
                    <h2 class="mt-2 text-lg font-semibold">{{ $post->title }}</h2>
                    <p class="mt-3 text-sm text-slate-300">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
                </article>
            @empty
                <article class="rounded-xl border border-gray-200 bg-white p-5 md:col-span-3">
                    <h2 class="mt-2 text-lg font-semibold">Brak wpisów blogowych</h2>
                    <p class="mt-3 text-sm text-slate-300">Dodaj wpisy i oznacz je jako opublikowane w panelu admina: CMS -> Blog.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection

