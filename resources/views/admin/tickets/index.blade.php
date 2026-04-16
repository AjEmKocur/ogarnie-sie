<x-app-layout>
    @php
        $badgeClasses = [
            'new' => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
            'in_progress' => 'bg-amber-500/20 text-amber-200 border border-amber-400/30',
            'waiting_parts' => 'bg-violet-500/20 text-violet-200 border border-violet-400/30',
            'ready' => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30',
            'closed' => 'bg-slate-500/20 text-slate-200 border border-slate-400/30',
            'cancelled' => 'bg-rose-500/20 text-rose-200 border border-rose-400/30',
        ];
        $paymentBadgeClasses = [
            'not_required' => 'bg-slate-500/20 text-slate-200 border border-slate-400/30',
            'pending' => 'bg-amber-500/20 text-amber-200 border border-amber-400/30',
            'paid' => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30',
        ];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Zgłoszenia serwisowe</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Zgłoszenia serwisowe'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="mb-3 text-xs uppercase tracking-wider text-slate-400">Filtr statusu</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.tickets.index', ['status' => 'all']) }}"
                       class="rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === 'all' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-slate-300 hover:bg-slate-800' }}">
                        Wszystkie aktywne
                    </a>
                    @foreach ($statuses as $value => $label)
                        @continue($value === \App\Models\Ticket::STATUS_CANCELLED)
                        <a href="{{ route('admin.tickets.index', ['status' => $value]) }}"
                           class="rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === $value ? 'bg-blue-600 text-white' : 'border border-gray-300 text-slate-300 hover:bg-slate-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($tickets->isEmpty())
                        <p>Brak zgłoszeń dla wybranego filtra.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($tickets as $ticket)
                                <article class="rounded-xl border border-gray-200 bg-slate-900/40 p-4">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm text-slate-400">#{{ $ticket->id }} · {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                                            <p class="mt-1 text-lg font-semibold">{{ $ticket->title }}</p>
                                            <p class="text-sm text-slate-400">Klient: {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                                            <p class="mt-2 text-xs text-slate-400">Załączniki: {{ $ticket->attachments_count }} · Wiadomości: {{ $ticket->messages_count }}</p>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                {{ $statuses[$ticket->status] ?? $ticket->status }}
                                            </span>
                                            @if ($ticket->status === \App\Models\Ticket::STATUS_CANCELLED)
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold bg-slate-500/20 text-slate-200 border border-slate-400/30">
                                                    Zamknięte (anulowane)
                                                </span>
                                            @endif
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                {{ $paymentStatuses[$ticket->payment_status] ?? $ticket->payment_status }}
                                            </span>
                                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                                               class="inline-flex items-center rounded-md border border-blue-400/50 bg-blue-500/20 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-100 transition hover:bg-blue-500/30">
                                                Otwórz szczegóły
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
