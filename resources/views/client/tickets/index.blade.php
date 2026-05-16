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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Moje zgłoszenia</h2>
            <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-blue-500">
                Nowe zgłoszenie
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($tickets->isEmpty())
                        <p>Brak zgłoszeń. Dodaj pierwsze zgłoszenie serwisowe.</p>
                    @else
                        <div class="space-y-3 md:hidden">
                            @foreach ($tickets as $ticket)
                                @php
                                    $canReview = $ticket->status === \App\Models\Ticket::STATUS_CLOSED && !$ticket->testimonial;
                                    $canCancel = !in_array($ticket->status, [\App\Models\Ticket::STATUS_CLOSED, \App\Models\Ticket::STATUS_CANCELLED], true);
                                @endphp
                                <article class="rounded-xl border border-gray-200 bg-slate-900/60 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm text-slate-400">#{{ $ticket->id }}</p>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                            {{ $statuses[$ticket->status] ?? $ticket->status }}
                                        </span>
                                    </div>

                                    <p class="mt-2 font-semibold leading-5">{{ $ticket->title }}</p>

                                    <div class="mt-3 flex items-center justify-between">
                                        <p class="text-sm text-slate-400">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                            {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
                                        </span>
                                    </div>

                                    <div class="mt-4 flex flex-wrap items-center justify-end gap-2">
                                        @if ($canCancel)
                                            <form method="POST" action="{{ route('client.tickets.cancel', $ticket) }}" data-confirm-title="Anulowanie zgłoszenia" data-confirm-message="Na pewno anulować zgłoszenie?">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-md border border-rose-400/50 bg-rose-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-rose-200 hover:bg-rose-500/20">
                                                    Anuluj
                                                </button>
                                            </form>
                                        @endif
                                        @if ($canReview)
                                            <a href="{{ route('client.testimonials.create', ['ticket' => $ticket->id]) }}"
                                               class="inline-flex items-center rounded-md border border-amber-400/50 bg-amber-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/20">
                                                Wystaw opinię
                                            </a>
                                        @endif
                                        <a href="{{ route('client.tickets.show', $ticket) }}"
                                           class="inline-flex items-center rounded-md border border-indigo-400/50 bg-indigo-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-indigo-200 hover:bg-indigo-500/20">
                                            Podgląd
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-[1060px] divide-y divide-gray-200">
                                <thead class="bg-slate-800/90">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-100">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-100">Temat</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-100">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-100">Płatność</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-100">Data</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-100">Akcja</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($tickets as $ticket)
                                        @php
                                            $canReview = $ticket->status === \App\Models\Ticket::STATUS_CLOSED && !$ticket->testimonial;
                                            $canCancel = !in_array($ticket->status, [\App\Models\Ticket::STATUS_CLOSED, \App\Models\Ticket::STATUS_CANCELLED], true);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap">#{{ $ticket->id }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <div class="max-w-[260px] whitespace-normal break-words leading-5">{{ $ticket->title }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                    {{ $statuses[$ticket->status] ?? $ticket->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                    {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap">{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="px-4 py-3 text-right text-sm whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    @if ($canCancel)
                                                        <form method="POST" action="{{ route('client.tickets.cancel', $ticket) }}" data-confirm-title="Anulowanie zgłoszenia" data-confirm-message="Na pewno anulować zgłoszenie?">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="inline-flex items-center rounded-md border border-rose-400/50 bg-rose-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-rose-200 hover:bg-rose-500/20">
                                                                Anuluj
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($canReview)
                                                        <a href="{{ route('client.testimonials.create', ['ticket' => $ticket->id]) }}"
                                                           class="inline-flex items-center rounded-md border border-amber-400/50 bg-amber-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/20">
                                                            Wystaw opinię
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('client.tickets.show', $ticket) }}"
                                                       class="inline-flex items-center rounded-md border border-indigo-400/50 bg-indigo-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-indigo-200 hover:bg-indigo-500/20">
                                                        Podgląd
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
