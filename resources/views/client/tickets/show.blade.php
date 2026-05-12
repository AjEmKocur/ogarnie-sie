<x-app-layout>
    @php
        $canCancel = !in_array($ticket->status, [\App\Models\Ticket::STATUS_CLOSED, \App\Models\Ticket::STATUS_CANCELLED], true);
        $statusBadgeClasses = [
            'new' => 'bg-blue-500/20 text-blue-200 border border-blue-400/40',
            'in_progress' => 'bg-amber-500/20 text-amber-200 border border-amber-400/40',
            'waiting_parts' => 'bg-violet-500/20 text-violet-200 border border-violet-400/40',
            'ready' => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40',
            'closed' => 'bg-slate-500/20 text-slate-200 border border-slate-400/40',
            'cancelled' => 'bg-rose-500/20 text-rose-200 border border-rose-400/40',
        ];
        $paymentBadgeClasses = [
            'not_required' => 'bg-slate-500/20 text-slate-200 border border-slate-400/40',
            'pending' => 'bg-amber-500/20 text-amber-200 border border-amber-400/40',
            'paid' => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Szczegóły zgłoszenia #{{ $ticket->id }}
            </h2>
            <div class="flex items-center gap-2">
                @if ($canCancel)
                    <form method="POST" action="{{ route('client.tickets.cancel', $ticket) }}" data-confirm-title="Anulowanie zgłoszenia" data-confirm-message="Na pewno anulować zgłoszenie?">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md border border-rose-400/50 bg-rose-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-rose-200 transition hover:bg-rose-500/20"
                        >
                            Anuluj zgłoszenie
                        </button>
                    </form>
                @endif

                <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                    Wróć do listy
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('attachment'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    {{ $errors->first('attachment') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-slate-900/40 shadow-sm sm:rounded-lg">
                <div class="space-y-4 p-4 sm:p-6 text-slate-100">
                    <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400">Zgłoszenie #{{ $ticket->id }}</p>
                                <h3 class="mt-1 text-2xl font-bold text-white">{{ $ticket->title }}</h3>
                                <p class="mt-2 text-xs text-slate-400">Utworzone: {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/40' }}">
                                    {{ $statuses[$ticket->status] ?? $ticket->status }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/40' }}">
                                    {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
                                </span>
                            </div>
                        </div>
                    </section>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4 lg:col-span-2">
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-300">📝 Treść zgłoszenia</h4>
                            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-400">Usługi</p>
                                    @if ($ticket->services->isEmpty())
                                        <p class="mt-1 text-sm text-slate-100">-</p>
                                    @else
                                        <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-slate-100">
                                            @foreach ($ticket->services as $service)
                                                <li>{{ $service->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-400">Orientacyjna cena od</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-100">
                                        {{ $ticket->estimated_price_from !== null ? number_format($ticket->estimated_price_from, 2, ',', ' ') . ' PLN' : 'Wycena po diagnozie' }}
                                    </p>
                                </div>
                            </div>

                            @if ($ticket->custom_request)
                                <div class="mt-4">
                                    <p class="text-xs uppercase tracking-wider text-slate-400">Dodatkowe informacje</p>
                                    <p class="mt-1 whitespace-pre-line text-sm text-slate-100">{{ $ticket->custom_request }}</p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <p class="text-xs uppercase tracking-wider text-slate-400">Opis problemu</p>
                                <p class="mt-1 whitespace-pre-line text-sm text-slate-100">{{ $ticket->description }}</p>
                            </div>
                        </section>

                        <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-300">💳 Płatność</h4>
                            <p class="mt-3 text-sm text-slate-200">
                                Tryb: {{ \App\Models\Ticket::paymentModes()[$ticket->payment_mode] ?? $ticket->payment_mode }}
                            </p>
                            <p class="mt-2 text-sm text-slate-200">
                                Kwota:
                                @if ($ticket->payment_amount !== null)
                                    {{ number_format((float) $ticket->payment_amount, 2, ',', ' ') }} PLN
                                @else
                                    Brak
                                @endif
                            </p>

                            @if ($ticket->payment_note)
                                <p class="mt-2 whitespace-pre-line text-sm text-slate-200">{{ $ticket->payment_note }}</p>
                            @endif

                            @if ($ticket->paid_at)
                                <p class="mt-2 text-sm text-emerald-300">Opłacono: {{ $ticket->paid_at->format('Y-m-d H:i') }}</p>
                            @endif

                            @if ($ticket->payment_status === \App\Models\Ticket::PAYMENT_STATUS_PENDING && $ticket->payment_mode !== \App\Models\Ticket::PAYMENT_MODE_ON_PICKUP)
                                <form method="POST" action="{{ route('client.tickets.pay', $ticket) }}" class="mt-4">
                                    @csrf
                                    <x-primary-button>Opłać teraz</x-primary-button>
                                </form>
                                <p class="mt-2 text-xs text-slate-400">Płatność testowa (symulacja) na potrzeby projektu.</p>
                            @elseif ($ticket->payment_status === \App\Models\Ticket::PAYMENT_STATUS_PENDING && $ticket->payment_mode === \App\Models\Ticket::PAYMENT_MODE_ON_PICKUP)
                                <p class="mt-2 text-sm text-slate-300">Płatność przy odbiorze - nie wymaga akcji online.</p>
                            @endif
                        </section>
                    </div>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4 lg:col-span-2">
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-300">📎 Załączniki</h4>

                            <form method="POST" action="{{ route('tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mt-4 flex flex-wrap items-center gap-3">
                                @csrf
                                <input type="file" name="attachment" required class="block rounded-md border border-gray-300 bg-white text-sm text-slate-900 file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                                <x-primary-button>Dodaj plik</x-primary-button>
                            </form>

                            @if ($ticket->attachments->isEmpty())
                                <p class="mt-4 text-sm text-slate-400">Brak załączników.</p>
                            @else
                                <ul class="mt-4 space-y-2">
                                    @foreach ($ticket->attachments as $attachment)
                                        <li class="flex items-center justify-between rounded-md border border-gray-200/30 bg-slate-900/50 px-3 py-2 text-sm">
                                            <span class="truncate pr-3">{{ $attachment->original_name }}</span>
                                            <div class="flex items-center gap-3 whitespace-nowrap">
                                                <a href="{{ route('tickets.attachments.download', $attachment) }}" class="text-indigo-300 hover:text-indigo-200">Pobierz</a>
                                                <form method="POST" action="{{ route('tickets.attachments.destroy', $attachment) }}" onsubmit="return confirm('Usunąć ten plik?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-300 hover:text-rose-200">Usuń</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>

                        <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-300">📈 Przebieg realizacji</h4>
                            @if ($ticket->statusHistories->isEmpty())
                                <p class="mt-3 text-sm text-slate-400">Historia zmian nie jest jeszcze dostępna.</p>
                            @else
                                <ol class="mt-3 space-y-2 max-h-80 overflow-y-auto pr-1">
                                    @foreach ($ticket->statusHistories as $history)
                                        <li class="rounded-lg border border-gray-200/20 bg-slate-900/50 p-3">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <p class="text-sm font-semibold text-slate-100">{{ $statuses[$history->status] ?? $history->status }}</p>
                                                <p class="text-[11px] text-slate-400">{{ $history->created_at?->format('Y-m-d H:i') }}</p>
                                            </div>
                                            <p class="mt-1 text-[11px] text-slate-400">Zmienił: {{ $history->changedByUser?->name ?? 'System' }}</p>
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </section>
                    </div>

                    <section class="rounded-xl border border-gray-200/30 bg-slate-900/40 p-4">
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-300">💬 Wiadomości z serwisem</h4>

                        @if ($ticket->messages->isEmpty())
                            <p class="mt-3 text-sm text-slate-400">Brak wiadomości.</p>
                        @else
                            <div class="mt-3 h-96 overflow-y-auto pr-1 space-y-3">
                                @foreach ($ticket->messages as $message)
                                    @php
                                        $isOwn = $message->user_id === auth()->id();
                                    @endphp
                                    <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                        <div class="w-full max-w-[86%] rounded-lg border p-4 {{ $isOwn ? 'border-blue-400/30 bg-blue-500/10' : 'border-gray-200/20 bg-slate-900/50' }}">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <p class="text-sm font-semibold text-slate-100">{{ $message->user?->name ?? 'Użytkownik' }}</p>
                                                <p class="text-xs text-slate-400">{{ $message->created_at?->format('Y-m-d H:i') }}</p>
                                            </div>
                                            <p class="mt-2 whitespace-pre-line text-sm text-slate-100">{{ $message->message }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('tickets.messages.store', $ticket) }}" class="mt-4 space-y-3">
                            @csrf
                            <textarea name="message" rows="3" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2 text-slate-100" placeholder="Napisz wiadomość do serwisu..." required>{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            <div class="flex justify-end">
                                <x-primary-button>Wyślij wiadomość</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
