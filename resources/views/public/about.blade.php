@extends('layouts.public')

@section('content')
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

                @if ($aboutGalleryImages->count() > 3)
                    <div class="flex items-center gap-2">
                        <button type="button" id="about-gallery-prev" class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                            ← Wstecz
                        </button>
                        <button type="button" id="about-gallery-next" class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                            Dalej →
                        </button>
                    </div>
                @endif
            </div>

            @if ($aboutGalleryImages->isEmpty())
                <div class="mt-6 rounded-xl border border-dashed border-gray-300 p-6 text-sm text-slate-300">
                    Galeria jest jeszcze pusta. Zdjęcia można dodać w panelu admina: CMS → Galeria O nas.
                </div>
            @else
                <div id="about-gallery-track" class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($aboutGalleryImages as $image)
                        <figure class="about-gallery-item overflow-hidden rounded-xl border border-gray-200 bg-slate-900/40" data-about-gallery-item @if($loop->index >= 3) style="display:none;" @endif>
                            <img src="{{ $image->publicUrl() }}" alt="{{ $image->caption ?: 'Zdjęcie serwisu' }}" class="h-56 w-full object-cover">
                            @if ($image->caption)
                                <figcaption class="border-t border-gray-200 px-4 py-3 text-sm text-slate-200">{{ $image->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
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
                const pageSize = 3;
                let start = 0;

                const render = () => {
                    items.forEach((item, index) => {
                        const visible = index >= start && index < start + pageSize;
                        item.style.display = visible ? '' : 'none';
                    });
                };

                prev?.addEventListener('click', () => {
                    start = Math.max(0, start - pageSize);
                    render();
                });

                next?.addEventListener('click', () => {
                    const maxStart = Math.max(0, items.length - pageSize);
                    start = Math.min(maxStart, start + pageSize);
                    render();
                });

                render();
            });
        </script>
    @endif
@endsection
