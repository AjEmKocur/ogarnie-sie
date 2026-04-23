<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wiadomosci kontaktowe</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($messages->isEmpty())
                        <p>Brak Twoich wiadomosci kontaktowych.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($messages as $message)
                                <article class="rounded-xl border border-gray-200 bg-slate-900/40 p-4">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm text-slate-400">#{{ $message->id }} · {{ $message->created_at->format('Y-m-d H:i') }}</p>
                                            <p class="mt-1 text-lg font-semibold">
                                                <a href="{{ route('client.contact.show', $message) }}" class="hover:text-blue-300">
                                                    {{ $message->subject }}
                                                </a>
                                            </p>
                                            <p class="mt-2 text-sm text-slate-200">{{ \Illuminate\Support\Str::limit((string) $message->message_preview, 180) }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                {{ $statuses[$message->status] ?? $message->status }}
                                            </span>
                                            <a href="{{ route('client.contact.show', $message) }}"
                                               class="inline-flex items-center rounded-md border border-blue-400/50 bg-blue-500/20 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-100 transition hover:bg-blue-500/30">
                                                Otworz watek
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
