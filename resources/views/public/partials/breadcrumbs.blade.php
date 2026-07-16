@php
    $items = $items ?? [];
@endphp

@if (!empty($items))
    <nav aria-label="Breadcrumb" class="mb-4 text-sm">
        <ol class="flex flex-wrap items-center gap-2 text-slate-300">
            @foreach ($items as $index => $item)
                @php
                    $isLast = $index === count($items) - 1;
                    $label = (string) ($item['label'] ?? '');
                    $url = $item['url'] ?? null;
                @endphp

                <li class="flex items-center gap-2">
                    @if ($url && !$isLast)
                        <a href="{{ $url }}" class="hover:text-amber-200">{{ $label }}</a>
                    @else
                        <span class="{{ $isLast ? 'text-slate-100' : '' }}">{{ $label }}</span>
                    @endif

                    @if (!$isLast)
                        <span class="text-slate-500">›</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
