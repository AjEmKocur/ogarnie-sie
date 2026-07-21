@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-amber-300 text-start text-base font-medium text-amber-100 bg-black/60 focus:outline-none focus:text-white focus:bg-amber-400/10 focus:border-amber-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-slate-800 hover:border-gray-300 focus:outline-none focus:text-white focus:bg-slate-800 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
