<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-black/40 border border-amber-300/30 rounded-md font-semibold text-xs text-slate-100 uppercase tracking-widest shadow-sm hover:bg-amber-400/10 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-black disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
