@props([
    'id',
    'name',
    'accept' => null,
    'multiple' => false,
    'required' => false,
    'button' => 'Wybierz plik',
    'empty' => 'Nie wybrano pliku',
])

<div {{ $attributes->merge(['class' => 'file-picker']) }} data-file-picker>
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="file"
        @if ($multiple) multiple @endif
        @if ($required) required @endif
        @if ($accept) accept="{{ $accept }}" @endif
        class="sr-only"
        data-file-picker-input
    >
    <div class="flex flex-wrap items-center gap-3 rounded-md border border-amber-300/25 bg-black/35 p-2">
        <label
            for="{{ $id }}"
            class="inline-flex cursor-pointer items-center rounded-md border border-amber-300/50 bg-amber-400 px-4 py-2 text-xs font-black uppercase tracking-widest text-black transition hover:bg-amber-300"
        >
            {{ $button }}
        </label>
        <span class="min-w-0 flex-1 truncate text-sm text-slate-300" data-file-picker-label data-empty-label="{{ $empty }}">
            {{ $empty }}
        </span>
    </div>
</div>
