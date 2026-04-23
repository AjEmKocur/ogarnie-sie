@extends('layouts.public')

@section('content')
    <style>
        #about-gallery-viewport {
            overflow: hidden;
        }

        #about-gallery-track {
            display: flex;
            gap: 1rem;
            transition: transform 320ms ease;
            will-change: transform;
        }

        .about-gallery-item {
            flex: 0 0 100%;
            min-width: 0;
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
                        <div id="about-gallery-track">
                            @foreach ($aboutGalleryImages as $image)
                                <figure class="about-gallery-item overflow-hidden rounded-xl border border-gray-200 bg-slate-900/40" data-about-gallery-item>
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
                const originalItems = Array.from(document.querySelectorAll('[data-about-gallery-item]'));
                const prev = document.getElementById('about-gallery-prev');
                const next = document.getElementById('about-gallery-next');
                const track = document.getElementById('about-gallery-track');
                const viewport = document.getElementById('about-gallery-viewport');
                if (!track || !viewport || originalItems.length === 0) {
                    return;
                }

                const total = originalItems.length;
                let currentIndex = 0;
                let cloneCount = 0;
                let isAnimating = false;

                const pageSize = () => {
                    if (window.matchMedia('(min-width: 768px)').matches) return 3;
                    if (window.matchMedia('(min-width: 640px)').matches) return 2;
                    return 1;
                };

                const itemStep = () => {
                    const first = track.querySelector('[data-about-gallery-item]');
                    if (!first) return 0;
                    const gap = parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || '0') || 0;
                    return first.getBoundingClientRect().width + gap;
                };

                const setWidths = () => {
                    const visible = Math.min(pageSize(), total);
                    const gap = parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || '0') || 0;
                    const widthPx = Math.max(120, (viewport.clientWidth - (gap * (visible - 1))) / visible);
                    track.querySelectorAll('[data-about-gallery-item]').forEach((item) => {
                        item.style.flex = `0 0 ${widthPx}px`;
                    });
                };

                const applyTransform = () => {
                    track.style.transform = `translateX(${-currentIndex * itemStep()}px)`;
                };

                const setTransition = (enabled) => {
                    track.style.transition = enabled ? 'transform 320ms ease' : 'none';
                };

                const rebuildTrack = () => {
                    const visible = Math.min(pageSize(), total);
                    cloneCount = visible;
                    const beforeClones = originalItems.slice(-cloneCount).map((item) => item.cloneNode(true));
                    const afterClones = originalItems.slice(0, cloneCount).map((item) => item.cloneNode(true));

                    track.innerHTML = '';
                    [...beforeClones, ...originalItems, ...afterClones].forEach((item) => track.appendChild(item.cloneNode(true)));

                    setWidths();
                    currentIndex = cloneCount;
                    setTransition(false);
                    applyTransform();
                };

                const shift = (direction) => {
                    if (isAnimating) return;
                    isAnimating = true;
                    setTransition(true);
                    currentIndex += direction;
                    applyTransform();
                };

                track.addEventListener('transitionend', () => {
                    if (!isAnimating) return;

                    if (currentIndex >= total + cloneCount) {
                        currentIndex -= total;
                        setTransition(false);
                        applyTransform();
                    } else if (currentIndex < cloneCount) {
                        currentIndex += total;
                        setTransition(false);
                        applyTransform();
                    }

                    requestAnimationFrame(() => {
                        isAnimating = false;
                    });
                });

                prev?.addEventListener('click', () => shift(-1));
                next?.addEventListener('click', () => shift(1));

                rebuildTrack();

                window.addEventListener('resize', () => {
                    if (isAnimating) return;
                    rebuildTrack();
                });
            });
        </script>
    @endif
@endsection
