@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-amber-400 focus:ring-amber-400 rounded-md shadow-sm']) }}>
