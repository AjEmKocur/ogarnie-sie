@props([
    'showLabel' => true,
])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-x-4 gap-y-2']) }}>
    @if ($showLabel)
        <span class="text-sm text-slate-400">Social media:</span>
    @endif

    <a
        href="https://www.facebook.com/kocurserwis/"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Kocur Serwis Komputerowy na Facebooku"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-300 transition hover:text-amber-200"
    >
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
            <path d="M13.5 21v-7h2.35l.35-2.72h-2.7V9.54c0-.79.22-1.33 1.35-1.33h1.45V5.78c-.25-.03-1.11-.11-2.11-.11-2.09 0-3.52 1.28-3.52 3.62v1.99H8.6V14h2.37v7h2.53Z" />
        </svg>
        <span>Facebook</span>
    </a>

    <a
        href="https://www.instagram.com/kocurserwis/"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Kocur Serwis Komputerowy na Instagramie"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-300 transition hover:text-amber-200"
    >
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="5" y="5" width="14" height="14" rx="4" />
            <circle cx="12" cy="12" r="3.1" />
            <circle cx="16.5" cy="7.5" r=".8" fill="currentColor" stroke="none" />
        </svg>
        <span>Instagram</span>
    </a>
</div>
