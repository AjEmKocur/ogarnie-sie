@extends('layouts.public')

@section('title', 'O mnie - Kocur Serwis Komputerowy')
@section('meta_description', 'Kocur Serwis Komputerowy to lokalna pomoc przy składaniu komputerów, modernizacji sprzętu, diagnostyce laptopów, instalacji systemów i sieciach domowych.')

@section('content')
    @php
        $galleryImages = $aboutGalleryImages->map(fn ($image) => [
            'url' => $image->publicUrl(),
            'alt' => $image->caption ?: 'Zdjęcie realizacji lub stanowiska pracy',
        ])->values();

        $mainImage = $galleryImages->first();
    @endphp

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">O mnie</h1>
        <p class="mt-6 max-w-4xl text-lg leading-8 text-slate-300 text-justify">
            Kocur Serwis Komputerowy to lokalna pomoc techniczna prowadzona osobiście w Jarosławiu i okolicach.
            Pomagam przy składaniu zestawów PC, modernizacji sprzętu, diagnostyce komputerów i laptopów,
            instalacji systemów oraz podstawowej konfiguracji sieci domowych. Stawiam na jasne ustalenia,
            wycenę przed usługą i rozwiązania dobrane do realnego problemu, bez wciskania niepotrzebnych części.
        </p>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Zakres</p>
                <p class="mt-2 text-2xl font-bold">PC i laptopy</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Dojazd</p>
                <p class="mt-2 text-2xl font-bold">Jarosław i okolice</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Wycena</p>
                <p class="mt-2 text-2xl font-bold">Przed usługą</p>
            </div>
        </div>

        <div class="mt-14">
            <div>
                <h2 class="text-2xl font-semibold">Jak pracuję</h2>
                <p class="mt-1 text-sm text-slate-300">Zdjęcia realizacji, stanowiska i przykładowych etapów pracy.</p>
            </div>

            @if ($galleryImages->isEmpty())
                <div class="mt-6 rounded-xl border border-dashed border-gray-300 p-6 text-sm text-slate-300">
                    Wkrótce pojawią się tutaj zdjęcia stanowiska i przykładowych etapów pracy.
                </div>
            @else
                <div
                    x-data="{
                        images: @js($galleryImages),
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
                        },
                        handleWheel(event) {
                            if (this.images.length <= 1 || Math.abs(event.deltaY) < 24) {
                                return;
                            }

                            event.preventDefault();

                            if (event.deltaY > 0) {
                                this.nextImage();
                            } else {
                                this.previousImage();
                            }
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
                            x-on:wheel="handleWheel($event)"
                            class="group relative block aspect-[16/9] w-full overflow-hidden rounded-xl bg-black/55 text-left shadow-[0_24px_80px_rgba(0,0,0,0.35)]"
                        >
                            <img
                                src="{{ $mainImage['url'] }}"
                                alt="{{ $mainImage['alt'] }}"
                                x-bind:src="currentImage.url"
                                x-bind:alt="currentImage.alt"
                                class="h-full w-full object-contain transition duration-500 group-hover:scale-[1.01]"
                                loading="eager"
                            >
                            <span class="absolute bottom-4 right-4 rounded-md bg-black/75 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 opacity-0 transition group-hover:opacity-100">
                                Powiększ
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
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                        x-on:click.self="closeImage()"
                        x-on:wheel="handleWheel($event)"
                    >
                        <div class="absolute left-4 right-4 top-4 z-10 flex flex-wrap items-center justify-between gap-3">
                            <p class="rounded-md border border-amber-300/30 bg-black/70 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </p>
                            <button
                                type="button"
                                x-on:click="closeImage()"
                                class="rounded-md border border-amber-300/50 bg-black px-4 py-2 text-xs font-semibold uppercase tracking-wider text-amber-100 hover:bg-amber-400 hover:text-black"
                            >
                                Zamknij
                            </button>
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

                        <div class="relative max-h-[92vh] max-w-6xl">
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
                                class="max-h-[88vh] max-w-[92vw] rounded-xl object-contain shadow-2xl"
                            >
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
