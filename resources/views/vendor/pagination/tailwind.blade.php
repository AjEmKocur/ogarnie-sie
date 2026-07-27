@if ($paginator->hasPages())
    @php
        $buttonBase = 'inline-flex min-w-10 items-center justify-center px-3 py-2 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-black';
        $buttonLink = $buttonBase.' border-r border-amber-500/20 bg-black/45 text-stone-200 hover:bg-amber-500/10 hover:text-amber-100';
        $buttonActive = $buttonBase.' border-r border-amber-500/20 bg-amber-400 text-black';
        $buttonMuted = $buttonBase.' border-r border-amber-500/20 bg-black/25 text-stone-600';
    @endphp

    <nav role="navigation" aria-label="Paginacja" class="mt-6">
        <div class="flex flex-col gap-3 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center rounded-md border border-amber-500/25 bg-black/35 px-4 py-2 text-sm font-semibold text-stone-500">
                    Poprzednia
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center rounded-md border border-amber-400/45 bg-black/45 px-4 py-2 text-sm font-semibold text-amber-100 transition hover:border-amber-300 hover:bg-amber-500/10 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-black">
                    Poprzednia
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center rounded-md border border-amber-400/45 bg-black/45 px-4 py-2 text-sm font-semibold text-amber-100 transition hover:border-amber-300 hover:bg-amber-500/10 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-black">
                    Następna
                </a>
            @else
                <span class="inline-flex items-center justify-center rounded-md border border-amber-500/25 bg-black/35 px-4 py-2 text-sm font-semibold text-stone-500">
                    Następna
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-between">
            <p class="text-sm text-stone-400">
                Pokazano
                <span class="font-semibold text-stone-200">{{ $paginator->firstItem() }}</span>
                -
                <span class="font-semibold text-stone-200">{{ $paginator->lastItem() }}</span>
                z
                <span class="font-semibold text-stone-200">{{ $paginator->total() }}</span>
            </p>

            <div class="inline-flex overflow-hidden rounded-md border border-amber-500/25">
                @if ($paginator->onFirstPage())
                    <span class="{{ $buttonMuted }}" aria-disabled="true" aria-label="Poprzednia">
                        &lsaquo;
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $buttonLink }}" aria-label="Poprzednia">
                        &lsaquo;
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="{{ $buttonMuted }}" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $buttonActive }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $buttonLink }}" aria-label="Strona {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $buttonLink }}" aria-label="Następna">
                        &rsaquo;
                    </a>
                @else
                    <span class="{{ $buttonMuted }}" aria-disabled="true" aria-label="Następna">
                        &rsaquo;
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
