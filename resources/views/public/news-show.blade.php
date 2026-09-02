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
                        images: @js($galleryImages->values()->all()),
                        currentIndex: 0,
                        isOpen: false,
                        get currentImage() {
                            return this.images[this.currentIndex] || { url: '', alt: '' };
                        },
                        selectImage(index) {
                            this.currentIndex = index;
                        },
                        openImage(index = null) {
                            if (index !== null) {
                                this.currentIndex = index;
                            }
                            this.isOpen = true;
                        },
                        closeImage() {
                            this.isOpen = false;
                        },
                        previousImage() {
                            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                        },
                        nextImage() {
                            this.currentIndex = (this.currentIndex + 1) % this.images.length;
                        }
                    }"
                    x-on:keydown.escape.window="closeImage()"
                    x-on:keydown.arrow-left.window="isOpen && previousImage()"
                    x-on:keydown.arrow-right.window="isOpen && nextImage()"
                    class="mt-6"
                >
                    <div class="relative">
                        <button
                            type="button"
                            x-on:click="openImage(currentIndex)"
                            class="group relative block aspect-[4/3] w-full overflow-hidden rounded-xl border border-amber-300/25 bg-zinc-950 text-left shadow-[0_24px_80px_rgba(0,0,0,0.45)]"
                        >
                            <img
                                src="{{ $mainImage['url'] }}"
                                alt="{{ $mainImage['alt'] }}"
                                x-bind:src="currentImage.url"
                                x-bind:alt="currentImage.alt"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                            >
                            <span class="absolute left-4 top-4 rounded-md border border-amber-300/35 bg-black/75 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </span>
                            <span class="absolute bottom-4 right-4 rounded-md border border-amber-300/40 bg-black/75 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 opacity-0 transition group-hover:opacity-100">
                                Powiększ zdjęcie
                            </span>
                        </button>

                        @if ($galleryImages->count() > 1)
                            <button
                                type="button"
                                x-on:click.stop="previousImage()"
                                class="absolute left-4 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-amber-300/45 bg-black/75 text-2xl font-bold leading-none text-amber-100 shadow-lg transition hover:bg-amber-400 hover:text-black"
                                aria-label="Poprzednie zdjęcie"
                            >
                                <span class="-translate-y-1">‹</span>
                            </button>
                            <button
                                type="button"
                                x-on:click.stop="nextImage()"
                                class="absolute right-4 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-amber-300/45 bg-black/75 text-2xl font-bold leading-none text-amber-100 shadow-lg transition hover:bg-amber-400 hover:text-black"
                                aria-label="Następne zdjęcie"
                            >
                                <span class="-translate-y-1">›</span>
                            </button>
                        @endif
                    </div>

                    <div
                        x-show="isOpen"
                        x-transition.opacity
                        x-cloak
                        class="fixed inset-0 z-50 bg-black/90"
                        x-on:click.self="closeImage()"
                    >
                        <div class="absolute left-4 right-4 top-4 z-10 flex flex-wrap items-center justify-between gap-3">
                            <p class="rounded-md border border-amber-300/30 bg-black/70 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </p>
                            <div class="flex flex-wrap items-center gap-2">
                                <a
                                    x-bind:href="currentImage.url"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-md border border-amber-300/50 bg-black px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 hover:bg-amber-400 hover:text-black"
                                >
                                    Otwórz oryginał
                                </a>
                                <button
                                    type="button"
                                    x-on:click="closeImage()"
                                    class="rounded-md border border-amber-300/50 bg-black px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 hover:bg-amber-400 hover:text-black"
                                >
                                    Zamknij
                                </button>
                            </div>
                        </div>

                        <button
                            type="button"
                            x-show="images.length > 1"
                            x-cloak
                            x-on:click.stop="previousImage()"
                            class="absolute left-4 top-1/2 z-10 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-amber-300/50 bg-black/75 text-3xl font-bold leading-none text-amber-100 transition hover:bg-amber-400 hover:text-black"
                            aria-label="Poprzednie zdjęcie"
                        >
                            <span class="-translate-y-1">‹</span>
                        </button>

                        <button
                            type="button"
                            x-show="images.length > 1"
                            x-cloak
                            x-on:click.stop="nextImage()"
                            class="absolute right-4 top-1/2 z-10 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-amber-300/50 bg-black/75 text-3xl font-bold leading-none text-amber-100 transition hover:bg-amber-400 hover:text-black"
                            aria-label="Następne zdjęcie"
                        >
                            <span class="-translate-y-1">›</span>
                        </button>

                        <div class="absolute inset-0 flex items-center justify-center p-4">
                            <div class="relative flex max-h-[88vh] w-full max-w-[92vw] items-center justify-center">
                                <button
                                    type="button"
                                    x-on:click.stop="nextImage()"
                                    x-show="images.length > 1"
                                    x-cloak
                                    class="absolute inset-0 z-10 cursor-pointer"
                                    aria-label="Następne zdjęcie"
                                ></button>
                                <img
                                    x-bind:src="currentImage.url"
                                    x-bind:alt="currentImage.alt"
                                    class="max-h-[88vh] max-w-full rounded-xl border border-amber-300/25 object-contain shadow-2xl"
                                    decoding="async"
                                >
                            </div>
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
