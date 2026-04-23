<x-app-layout>
    @php
        $badgeClasses = [
            'new' => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
            'replied' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30',
        ];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wiadomości kontaktowe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Wiadomości kontaktowe'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                @if ($messages->isEmpty())
                    <div class="p-6 text-gray-900">
                        Brak wiadomości kontaktowych.
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach ($messages as $message)
                            <article class="px-5 py-4">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-slate-100">{{ $message->subject }}</p>
                                        <p class="mt-1 truncate text-sm text-slate-400">
                                            {{ $message->name }} ({{ $message->email }})
                                            @if ($message->phone)
                                                - {{ $message->phone }}
                                            @endif
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                            {{ $statuses[$message->status] ?? $message->status }}
                                        </span>
                                        <a
                                            href="{{ route('admin.contact.show', $message) }}"
                                            class="inline-flex items-center rounded-md border border-blue-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 hover:bg-blue-500/10"
                                        >
                                            Otwórz
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
