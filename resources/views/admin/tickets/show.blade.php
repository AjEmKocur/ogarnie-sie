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
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Zgłoszenie #{{ $ticket->id }}</h2>
            <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                Wróć do listy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Zgłoszenia serwisowe', 'url' => route('admin.tickets.index')],
                    ['label' => '#'.$ticket->id],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('attachment'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    {{ $errors->first('attachment') }}
                </div>
            @endif

            @if ($errors->any() && ! $errors->has('attachment'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Nie udało się zapisać zmian.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                        <h1 class="mt-1 text-2xl font-bold">{{ $ticket->title }}</h1>
                        <p class="mt-1 text-sm text-slate-400">Klient: {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                            {{ $statuses[$ticket->status] ?? $ticket->status }}
                        </span>
                        @if ($ticket->status === \App\Models\Ticket::STATUS_CANCELLED)
                            <span class="rounded-full px-3 py-1 text-xs font-semibold bg-slate-500/20 text-slate-200 border border-slate-400/30">Zamknięte (anulowane)</span>
                        @endif
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                            {{ $paymentStatuses[$ticket->payment_status] ?? $ticket->payment_status }}
                        </span>
                    </div>
                </div>
            </section>

            <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-4">
                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Treść zgłoszenia</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 text-sm">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400">Usługi</p>
                                @if ($ticket->services->isEmpty())
                                    <p class="mt-1">-</p>
                                @else
                                    <ul class="mt-1 list-disc pl-5">
                                        @foreach ($ticket->services as $service)
                                            <li>{{ $service->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400">Orientacyjna cena od</p>
                                <p class="mt-1">{{ $ticket->estimated_price_from !== null ? number_format($ticket->estimated_price_from, 2, ',', ' ') . ' PLN' : 'Wycena po diagnozie' }}</p>
                            </div>
                        </div>

                        @if ($ticket->custom_request)
                            <div class="mt-4">
                                <p class="text-xs uppercase tracking-wider text-slate-400">Dodatkowe informacje</p>
                                <p class="mt-1 whitespace-pre-line text-sm">{{ $ticket->custom_request }}</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400">Opis problemu</p>
                            <p class="mt-1 whitespace-pre-line text-sm">{{ $ticket->description }}</p>
                        </div>
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Załączniki</h3>

                        <form method="POST" action="{{ route('tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mt-3 flex flex-wrap items-center gap-3">
                            @csrf
                            <input type="file" name="attachment" required class="block rounded-md border border-gray-300 bg-white text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                            <x-primary-button>Dodaj plik</x-primary-button>
                        </form>

                        @if ($ticket->attachments->isEmpty())
                            <p class="mt-3 text-sm text-slate-400">Brak załączników.</p>
                        @else
                            <ul class="mt-3 space-y-1">
                                @foreach ($ticket->attachments as $attachment)
                                    <li class="flex items-center justify-between rounded-md border border-gray-200 px-3 py-2 text-sm">
                                        <span>{{ $attachment->original_name }}</span>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('tickets.attachments.download', $attachment) }}" class="text-indigo-600 hover:text-indigo-800">Pobierz</a>
                                            <form method="POST" action="{{ route('tickets.attachments.destroy', $attachment) }}" onsubmit="return confirm('Usunąć ten plik?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Usuń</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Historia statusów</h3>
                        @if ($ticket->statusHistories->isEmpty())
                            <p class="mt-3 text-sm text-slate-400">Brak historii zmian.</p>
                        @else
                            <ol class="mt-3 space-y-2">
                                @foreach ($ticket->statusHistories as $history)
                                    <li class="rounded-md border border-gray-200 px-3 py-2 text-sm">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="font-semibold">{{ $statuses[$history->status] ?? $history->status }}</span>
                                            <span class="text-xs text-slate-400">{{ $history->created_at?->format('Y-m-d H:i') }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">Zmienił: {{ $history->changedByUser?->name ?? 'System' }}</p>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </section>
                </div>

                <aside class="space-y-4">
                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Wiadomości</h3>

                        <div class="mt-3 max-h-96 overflow-y-auto space-y-2 pr-1">
                            @if ($ticket->messages->isEmpty())
                                <p class="text-sm text-slate-400">Brak wiadomości.</p>
                            @else
                                @foreach ($ticket->messages as $message)
                                    <div class="rounded-md border border-gray-200 px-3 py-2 text-sm">
                                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-slate-400">
                                            <span>{{ $message->user?->name ?? 'Użytkownik' }}</span>
                                            <span>{{ $message->created_at?->format('Y-m-d H:i') }}</span>
                                        </div>
                                        <p class="mt-1 whitespace-pre-line">{{ $message->message }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <form method="POST" action="{{ route('tickets.messages.store', $ticket) }}" class="mt-3 space-y-2">
                            @csrf
                            <textarea name="message" rows="4" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" placeholder="Napisz wiadomość do klienta..." required></textarea>
                            <div class="flex justify-end">
                                <x-primary-button>Wyślij wiadomość</x-primary-button>
                            </div>
                        </form>
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Aktualizacja zgłoszenia</h3>

                        <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="mt-3 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <x-input-label :value="'Notatka wewnętrzna (tylko admin/operator)'" />
                                <textarea name="admin_note" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('admin_note', $ticket->admin_note) }}</textarea>
                            </div>

                            <div>
                                <x-input-label :value="'Płatność na miejscu (kwota PLN)'" />
                                <input
                                    name="payment_amount"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-slate-900 text-slate-100 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('payment_amount', $ticket->payment_amount) }}"
                                    placeholder="Puste = brak płatności"
                                />
                            </div>

                            <label class="inline-flex items-center gap-2 text-sm text-slate-200">
                                <input
                                    type="checkbox"
                                    name="payment_mark_paid"
                                    value="1"
                                    @checked((bool) old('payment_mark_paid', $ticket->payment_status === \App\Models\Ticket::PAYMENT_STATUS_PAID))
                                    class="rounded border-gray-300 bg-slate-900 text-blue-500"
                                >
                                Oznacz jako opłacone
                            </label>

                            <div class="rounded-md border border-gray-200 p-3">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400">Status (szybka zmiana)</p>
                                <select name="status" class="mt-2 block w-full rounded-md border-gray-300 bg-slate-900 px-2 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $ticket->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>Zapisz zmiany</x-primary-button>
                            </div>
                        </form>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
