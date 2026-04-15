@extends('layouts.public')

@section('content')
    <style>
        #about-gallery-viewport {
            overflow: hidden;
        }

        #about-gallery-track {
            transition: transform 220ms ease;
            will-change: transform;
        }

        #about-gallery-track.is-shifting-left {
            transform: translateX(-42px);
        }

        #about-gallery-track.is-shifting-right {
            transform: translateX(42px);
        }
    </style>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">O nas</h1>
        <p class="mt-6 max-w-3xl text-lg text-slate-300">
            Ogarnie się to lokalny serwis komputerowy nastawiony na szybką diagnostykę, jasną komunikację i konkretne terminy.
            Łączymy podejście techniczne z prostym językiem dla klienta.
        </p>

        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Doświadczenie</p>
                <p class="mt-2 text-2xl font-bold">5+ lat</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Średni czas diagnozy</p>
                <p class="mt-2 text-2xl font-bold">24h</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-slate-400">Gwarancja serwisowa</p>
                <p class="mt-2 text-2xl font-bold">30 dni</p>
            </div>
        </div>

        <div class="mt-14 rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-semibold">Jak wygląda nasz serwis</h2>
                    <p class="mt-1 text-sm text-slate-300">Krótka galeria zdjęć z miejsca pracy.</p>
                </div>
            </div>

            @if ($aboutGalleryImages->isEmpty())
                <div class="mt-6 rounded-xl border border-dashed border-gray-300 p-6 text-sm text-slate-300">
                    Galeria jest jeszcze pusta. Zdjęcia można dodać w panelu admina: CMS → Galeria O nas.
                </div>
            @else
                <div class="relative mt-6">
                    @if ($aboutGalleryImages->count() > 3)
                        <button type="button" id="about-gallery-prev" class="absolute -left-3 top-1/2 z-10 -translate-y-1/2 rounded-full border border-blue-300/40 bg-slate-900/90 px-3 py-2 text-sm font-semibold text-slate-100 shadow-lg hover:bg-slate-800 md:-left-4">
                            ←
                        </button>
                        <button type="button" id="about-gallery-next" class="absolute -right-3 top-1/2 z-10 -translate-y-1/2 rounded-full border border-blue-300/40 bg-slate-900/90 px-3 py-2 text-sm font-semibold text-slate-100 shadow-lg hover:bg-slate-800 md:-right-4">
                            →
                        </button>
                    @endif

                    <div id="about-gallery-viewport">
                        <div id="about-gallery-track" class="grid gap-4 md:grid-cols-3">
                            @foreach ($aboutGalleryImages as $image)
                                <figure class="about-gallery-item overflow-hidden rounded-xl border border-gray-200 bg-slate-900/40" data-about-gallery-item @if($loop->index >= 3) style="display:none;" @endif>
                                    <img src="{{ $image->publicUrl() }}" alt="{{ $image->caption ?: 'Zdjęcie serwisu' }}" class="h-56 w-full object-cover">
                                    @if ($image->caption)
                                        <figcaption class="border-t border-gray-200 px-4 py-3 text-sm text-slate-200">{{ $image->caption }}</figcaption>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($aboutGalleryImages->count() > 3)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const items = Array.from(document.querySelectorAll('[data-about-gallery-item]'));
                const prev = document.getElementById('about-gallery-prev');
                const next = document.getElementById('about-gallery-next');
                const track = document.getElementById('about-gallery-track');
                let start = 0;
                const total = items.length;
                let isAnimating = false;

                const pageSize = () => {
                    if (window.matchMedia('(min-width: 768px)').matches) return 3;
                    if (window.matchMedia('(min-width: 640px)').matches) return 2;
                    return 1;
                };

                const visibleIndexes = () => {
                    const size = Math.min(pageSize(), total);
                    return Array.from({ length: size }, (_, offset) => (start + offset) % total);
                };

                const render = () => {
                    const visible = visibleIndexes();

                    items.forEach((item, index) => {
                        const position = visible.indexOf(index);
                        const isVisible = position !== -1;
                        item.style.display = isVisible ? '' : 'none';
                        item.style.order = isVisible ? String(position) : '';
                    });
                };

                const shift = (direction) => {
                    if (!track || isAnimating) {
                        return;
                    }

                    isAnimating = true;
                    track.classList.add(direction > 0 ? 'is-shifting-left' : 'is-shifting-right');

                    window.setTimeout(() => {
                        start = (start + direction + total) % total;
                        render();
                        track.classList.remove('is-shifting-left', 'is-shifting-right');
                        isAnimating = false;
                    }, 220);
                };

                const shiftInstant = (direction) => {
                    start = (start + direction + total) % total;
                    render();
                };

                prev?.addEventListener('click', () => {
                    shift(-1);
                });

                next?.addEventListener('click', () => {
                    shift(1);
                });

                render();

                window.addEventListener('resize', () => {
                    if (isAnimating) {
                        return;
                    }

                    shiftInstant(0);
                });
            });
        </script>
    @endif
@endsection
