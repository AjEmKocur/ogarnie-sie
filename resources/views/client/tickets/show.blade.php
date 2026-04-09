<x-app-layout>
    @php
        $canCancel = !in_array($ticket->status, [\App\Models\Ticket::STATUS_CLOSED, \App\Models\Ticket::STATUS_CANCELLED], true);
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
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
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

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-6 text-gray-900">
                    <div>
                        <p class="text-sm text-gray-500">Temat</p>
                        <p class="font-medium">{{ $ticket->title }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium">{{ $statuses[$ticket->status] ?? $ticket->status }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Wybrane usługi</p>
                        @if ($ticket->services->isEmpty())
                            <p class="font-medium">-</p>
                        @else
                            <ul class="list-disc pl-5">
                                @foreach ($ticket->services as $service)
                                    <li>{{ $service->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Orientacyjna cena od</p>
                        <p class="font-medium">
                            {{ $ticket->estimated_price_from !== null ? number_format($ticket->estimated_price_from, 2, ',', ' ') . ' PLN' : 'Wycena po diagnozie' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="text-sm font-semibold uppercase tracking-wider text-gray-500">Płatność</p>
                        <p class="mt-2">
                            <span class="font-medium">
                                {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
                            </span>
                            @if ($ticket->payment_amount !== null)
                                <span class="ml-2">{{ number_format((float) $ticket->payment_amount, 2, ',', ' ') }} PLN</span>
                            @endif
                        </p>

                        <p class="mt-2 text-sm text-gray-600">
                            Tryb: {{ \App\Models\Ticket::paymentModes()[$ticket->payment_mode] ?? $ticket->payment_mode }}
                        </p>

                        @if ($ticket->payment_note)
                            <p class="mt-2 whitespace-pre-line text-sm">{{ $ticket->payment_note }}</p>
                        @endif

                        @if ($ticket->paid_at)
                            <p class="mt-2 text-sm text-emerald-700">Opłacono: {{ $ticket->paid_at->format('Y-m-d H:i') }}</p>
                        @endif

                        @if ($ticket->payment_status === \App\Models\Ticket::PAYMENT_STATUS_PENDING && $ticket->payment_mode !== \App\Models\Ticket::PAYMENT_MODE_ON_PICKUP)
                            <form method="POST" action="{{ route('client.tickets.pay', $ticket) }}" class="mt-4">
                                @csrf
                                <x-primary-button>Opłać teraz</x-primary-button>
                            </form>
                            <p class="mt-2 text-xs text-gray-500">To jest płatność testowa (symulacja) na potrzeby projektu.</p>
                        @elseif ($ticket->payment_status === \App\Models\Ticket::PAYMENT_STATUS_PENDING && $ticket->payment_mode === \App\Models\Ticket::PAYMENT_MODE_ON_PICKUP)
                            <p class="mt-2 text-sm text-gray-600">Płatność przy odbiorze - nie wymaga akcji online.</p>
                        @endif
                    </div>

                    @if ($ticket->custom_request)
                        <div>
                            <p class="text-sm text-gray-500">Dodatkowe informacje</p>
                            <p class="whitespace-pre-line">{{ $ticket->custom_request }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500">Opis</p>
                        <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Przebieg realizacji</p>

                        @if ($ticket->statusHistories->isEmpty())
                            <p class="text-sm text-gray-500">Historia zmian nie jest jeszcze dostępna.</p>
                        @else
                            <ol class="space-y-3">
                                @foreach ($ticket->statusHistories as $history)
                                    <li class="rounded-lg border border-gray-200 bg-slate-900/60 p-4">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <p class="font-semibold text-slate-100">{{ $statuses[$history->status] ?? $history->status }}</p>
                                            <p class="text-xs text-slate-400">{{ $history->created_at?->format('Y-m-d H:i') }}</p>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Zmienił: {{ $history->changedByUser?->name ?? 'System' }}
                                        </p>
                                        @if ($history->admin_note)
                                            <p class="mt-2 whitespace-pre-line text-sm">{{ $history->admin_note }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Wiadomości w zgłoszeniu</p>

                        @if ($ticket->messages->isEmpty())
                            <p class="text-sm text-gray-500">Brak wiadomości.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($ticket->messages as $message)
                                    <div class="rounded-lg border border-gray-200 bg-slate-900/60 p-4">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <p class="text-sm font-semibold">
                                                {{ $message->user?->name ?? 'Użytkownik' }}
                                            </p>
                                            <p class="text-xs text-slate-400">{{ $message->created_at?->format('Y-m-d H:i') }}</p>
                                        </div>
                                        <p class="mt-2 whitespace-pre-line text-sm">{{ $message->message }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('tickets.messages.store', $ticket) }}" class="mt-4 space-y-3">
                            @csrf
                            <textarea name="message" rows="3" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" placeholder="Napisz wiadomość do serwisu..." required>{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            <div class="flex justify-end">
                                <x-primary-button>Wyślij wiadomość</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <p class="mb-3 text-sm text-gray-500">Załączniki</p>

                        <form method="POST" action="{{ route('tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mb-4 flex flex-wrap items-center gap-3">
                            @csrf
                            <input type="file" name="attachment" required class="block rounded-md border border-gray-300 bg-white text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                            <x-primary-button>Dodaj plik</x-primary-button>
                        </form>

                        @if ($ticket->attachments->isEmpty())
                            <p class="text-sm text-gray-500">Brak załączników.</p>
                        @else
                            <ul class="space-y-2">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
