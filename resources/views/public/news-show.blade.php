@extends('layouts.public')

@section('title', $post->title.' - Realizacje - Kocur Serwis Komputerowy')
@section('meta_description', \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content ?: 'Realizacja usługi komputerowej wykonanej przez Kocur Serwis Komputerowy.'), 155))

@section('content')
    @php
        $galleryImages = collect();

        if ($post->coverImageUrl()) {
            $galleryImages->push([
                'url' => $post->coverImageUrl(),
                'alt' => $post->title,
            ]);
        }

        foreach ($post->images as $image) {
            $galleryImages->push([
                'url' => $image->publicUrl(),
                'alt' => $image->caption ?: $post->title,
            ]);
        }

        $mainImage = $galleryImages->first();
    @endphp

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

            @if ($galleryImages->isNotEmpty())
                <div
                    x-data="{
                        isOpen: false,
                        activeImage: '',
                        activeAlt: '',
                        openImage(url, alt) {
                            this.activeImage = url;
                            this.activeAlt = alt;
                            this.isOpen = true;
                        },
                        closeImage() {
                            this.isOpen = false;
                        }
                    }"
                    x-on:keydown.escape.window="closeImage()"
                    class="mt-6"
                >
                    <button
                        type="button"
                        x-on:click="openImage(@js($mainImage['url']), @js($mainImage['alt']))"
                        class="group relative block aspect-[4/3] w-full overflow-hidden rounded-xl border border-amber-300/25 bg-zinc-950 text-left shadow-[0_24px_80px_rgba(0,0,0,0.45)]"
                    >
                        <img
                            src="{{ $mainImage['url'] }}"
                            alt="{{ $mainImage['alt'] }}"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                            loading="eager"
                            fetchpriority="high"
                        >
                        <span class="absolute bottom-4 right-4 rounded-md border border-amber-300/40 bg-black/75 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 opacity-0 transition group-hover:opacity-100">
                            Powiększ zdjęcie
                        </span>
                    </button>

                    @if ($galleryImages->count() > 1)
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($galleryImages->skip(1) as $image)
                                <button
                                    type="button"
                                    x-on:click="openImage(@js($image['url']), @js($image['alt']))"
                                    class="group relative aspect-[4/3] overflow-hidden rounded-lg border border-amber-300/20 bg-zinc-950 text-left"
                                >
                                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div
                        x-show="isOpen"
                        x-transition.opacity
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                        x-on:click.self="closeImage()"
                    >
                        <div class="relative max-h-[92vh] max-w-6xl">
                            <button
                                type="button"
                                x-on:click="closeImage()"
                                class="absolute right-0 top-0 z-10 -translate-y-12 rounded-md border border-amber-300/50 bg-black px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 hover:bg-amber-400 hover:text-black"
                            >
                                Zamknij
                            </button>
                            <img :src="activeImage" :alt="activeAlt" class="max-h-[88vh] max-w-[92vw] rounded-xl border border-amber-300/25 object-contain shadow-2xl">
                        </div>
                    </div>
                </div>
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
