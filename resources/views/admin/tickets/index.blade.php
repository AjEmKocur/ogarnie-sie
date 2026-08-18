<x-app-layout>
    @php
        $badgeClasses = [
            'new' => 'bg-amber-500/20 text-amber-300 border border-amber-400/30',
            'in_progress' => 'bg-amber-500/20 text-amber-200 border border-amber-400/30',
            'waiting_parts' => 'bg-stone-500/20 text-stone-200 border border-stone-400/30',
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
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex flex-col gap-2 sm:max-w-xs">
                    <label for="ticket-status-filter" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Filtr statusu</label>
                    <select
                        id="ticket-status-filter"
                        name="status"
                        onchange="this.form.submit()"
                        class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2 text-sm font-semibold text-slate-100"
                    >
                        <option value="all" @selected($statusFilter === 'all')>Wszystkie aktywne</option>
                        @foreach ($statuses as $value => $label)
                            @continue($value === \App\Models\Ticket::STATUS_CANCELLED)
                            <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="text-gray-900">
                    @if ($tickets->isEmpty())
                        <p class="p-5">Brak zgłoszeń dla wybranego filtra.</p>
                    @else
                        <div class="divide-y divide-white/10">
                            @foreach ($tickets as $ticket)
                                @php
                                    $hasAdminUnread = $ticket->last_client_message_at
                                        && (
                                            ! $ticket->admin_last_seen_at
                                            || strtotime((string) $ticket->last_client_message_at) > strtotime((string) $ticket->admin_last_seen_at)
                                        );
                                    $hasAdminNewTicket = ! $ticket->last_client_message_at
                                        && ! $ticket->admin_last_seen_at;
                                @endphp
                                <article class="bg-slate-900/25 px-4 py-3 transition hover:bg-slate-900/45 sm:px-5">
                                    <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                <p class="text-xs font-semibold text-slate-400">#{{ $ticket->id }}</p>
                                                <span class="text-xs text-slate-600">·</span>
                                                <p class="text-xs text-slate-400">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                                                @if ($hasAdminUnread)
                                                    <span class="rounded-full border border-amber-400/40 bg-amber-500/10 px-2 py-0.5 text-[11px] font-semibold text-amber-200">
                                                        Nowa wiadomość
                                                    </span>
                                                @elseif ($hasAdminNewTicket)
                                                    <span class="rounded-full border border-amber-400/40 bg-amber-500/10 px-2 py-0.5 text-[11px] font-semibold text-amber-200">
                                                        Nowe
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 truncate text-base font-semibold">{{ $ticket->title }}</p>
                                            <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-400">
                                                <span>Klient: {{ $ticket->user->name }}</span>
                                                <span>{{ $ticket->user->email }}</span>
                                                <span>Załączniki: {{ $ticket->attachments_count }}</span>
                                                <span>Wiadomości: {{ $ticket->messages_count }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 lg:justify-end">
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
                                               class="inline-flex items-center rounded-md border border-amber-400/50 bg-amber-500/20 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-amber-100 transition hover:bg-amber-500/30">
                                                Otwórz
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="border-t border-white/10 p-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
