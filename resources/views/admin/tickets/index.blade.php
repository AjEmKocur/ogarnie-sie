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

    <style>
        details > summary {
            list-style: none;
        }
        details > summary::-webkit-details-marker {
            display: none;
        }
    </style>

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

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="mb-3 text-xs uppercase tracking-wider text-slate-400">Filtr statusu</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.tickets.index', ['status' => 'all']) }}"
                       class="rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === 'all' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-slate-300 hover:bg-slate-800' }}">
                        Wszystkie aktywne
                    </a>
                    @foreach ($statuses as $value => $label)
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
                        <div class="space-y-4">
                            @foreach ($tickets as $ticket)
                                <details class="group rounded-lg border border-gray-200 bg-slate-900/40">
                                    <summary class="cursor-default list-none p-4" data-ticket-summary>
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm text-slate-400">#{{ $ticket->id }} · {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                                                <p class="font-semibold text-lg">{{ $ticket->title }}</p>
                                                <p class="text-sm text-slate-400">
                                                    Klient: {{ $ticket->user->name }} ({{ $ticket->user->email }})
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$ticket->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                    {{ $statuses[$ticket->status] ?? $ticket->status }}
                                                </span>
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                    {{ $paymentStatuses[$ticket->payment_status] ?? $ticket->payment_status }}
                                                </span>
                                                <button
                                                    type="button"
                                                    data-ticket-toggle
                                                    class="inline-flex items-center rounded-md border border-blue-400/50 bg-blue-500/20 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-blue-100 transition hover:bg-blue-500/30"
                                                >
                                                    Rozwiń
                                                </button>
                                            </div>
                                        </div>
                                    </summary>

                                    <div class="border-t border-gray-200 p-4">
                                        <div class="mb-3 text-sm">
                                            <p class="font-medium">Usługi:</p>
                                            @if ($ticket->services->isEmpty())
                                                <p>-</p>
                                            @else
                                                <ul class="list-disc pl-5">
                                                    @foreach ($ticket->services as $service)
                                                        <li>{{ $service->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>

                                        <p class="mb-2 text-sm">
                                            <strong>Orientacyjna cena od:</strong>
                                            {{ $ticket->estimated_price_from !== null ? number_format($ticket->estimated_price_from, 2, ',', ' ') . ' PLN' : 'Wycena po diagnozie' }}
                                        </p>

                                        <p class="mb-2 text-sm">
                                            <strong>Płatność:</strong>
                                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $paymentBadgeClasses[$ticket->payment_status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                {{ $paymentStatuses[$ticket->payment_status] ?? $ticket->payment_status }}
                                            </span>
                                            @if ($ticket->payment_amount !== null)
                                                <span class="ml-2">{{ number_format((float) $ticket->payment_amount, 2, ',', ' ') }} PLN</span>
                                            @endif
                                            @if ($ticket->paid_at)
                                                <span class="ml-2 text-gray-500">(opłacono: {{ $ticket->paid_at->format('Y-m-d H:i') }})</span>
                                            @endif
                                        </p>

                                        @if ($ticket->custom_request)
                                            <div class="mb-2 text-sm">
                                                <p class="font-medium">Dodatkowe informacje:</p>
                                                <p class="whitespace-pre-line">{{ $ticket->custom_request }}</p>
                                            </div>
                                        @endif

                                        <p class="mb-4 whitespace-pre-line text-sm text-gray-700">{{ $ticket->description }}</p>

                                        <div class="mb-4 rounded-md border border-gray-200 p-3">
                                            <p class="mb-2 text-sm font-medium text-gray-700">Załączniki</p>

                                            <form method="POST" action="{{ route('tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mb-3 flex flex-wrap items-center gap-3">
                                                @csrf
                                                <input type="file" name="attachment" required class="block rounded-md border border-gray-300 bg-white text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                                                <x-primary-button>Dodaj plik</x-primary-button>
                                            </form>

                                            @if ($ticket->attachments->isEmpty())
                                                <p class="text-sm text-gray-500">Brak załączników.</p>
                                            @else
                                                <ul class="space-y-1">
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

                                        <div class="mb-4 rounded-md border border-gray-200 p-3">
                                            <p class="mb-2 text-sm font-medium text-gray-700">Wiadomości w zgłoszeniu</p>

                                            @if ($ticket->messages->isEmpty())
                                                <p class="text-sm text-gray-500">Brak wiadomości.</p>
                                            @else
                                                <div class="mb-3 space-y-2">
                                                    @foreach ($ticket->messages as $message)
                                                        <div class="rounded-md border border-gray-200 px-3 py-2 text-sm">
                                                            <div class="mb-1 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                                                                <span>{{ $message->user?->name ?? 'Użytkownik' }}</span>
                                                                <span>{{ $message->created_at?->format('Y-m-d H:i') }}</span>
                                                            </div>
                                                            <p class="whitespace-pre-line">{{ $message->message }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('tickets.messages.store', $ticket) }}" class="space-y-2">
                                                @csrf
                                                <textarea name="message" rows="2" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" placeholder="Napisz wiadomość do klienta..." required></textarea>
                                                <div class="flex justify-end">
                                                    <x-primary-button>Wyślij wiadomość</x-primary-button>
                                                </div>
                                            </form>
                                        </div>

                                        <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="grid gap-4 md:grid-cols-2">
                                            @csrf
                                            @method('PATCH')

                                            <div>
                                                <x-input-label :value="'Status'" />
                                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @foreach ($statuses as $value => $label)
                                                        <option value="{{ $value }}" @selected(old('status', $ticket->status) === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="md:col-span-2">
                                                <x-input-label :value="'Komentarz do kroku'" />
                                                <textarea name="admin_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('admin_note', $ticket->admin_note) }}</textarea>
                                            </div>

                                            <div class="md:col-span-2 rounded-md border border-gray-200 p-3">
                                                <p class="mb-3 text-sm font-semibold text-gray-700">Płatność</p>
                                                <div class="grid gap-4 md:grid-cols-2">
                                                    <div>
                                                        <x-input-label :value="'Tryb płatności'" />
                                                        <select name="payment_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                            @foreach ($paymentModes as $value => $label)
                                                                <option value="{{ $value }}" @selected(old('payment_mode', $ticket->payment_mode) === $value)>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <x-input-label :value="'Kwota do zapłaty (PLN)'" />
                                                        <input
                                                            name="payment_amount"
                                                            type="number"
                                                            min="0"
                                                            step="0.01"
                                                            class="mt-1 block w-full rounded-md border-gray-300 bg-slate-900 text-slate-100 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                            value="{{ old('payment_amount', $ticket->payment_amount) }}"
                                                            placeholder="Np. 199.99"
                                                        />
                                                    </div>

                                                    <div>
                                                        <x-input-label :value="'Status płatności'" />
                                                        <select name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                            @foreach ($paymentStatuses as $value => $label)
                                                                <option value="{{ $value }}" @selected(old('payment_status', $ticket->payment_status) === $value)>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <x-input-label :value="'Notatka do płatności (dla klienta)'" />
                                                        <textarea name="payment_note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('payment_note', $ticket->payment_note) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-2 flex justify-end">
                                                <x-primary-button>Zapisz zmiany</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-ticket-summary]').forEach((summary) => {
                summary.addEventListener('click', (event) => {
                    event.preventDefault();
                });
            });

            document.querySelectorAll('[data-ticket-toggle]').forEach((button) => {
                const details = button.closest('details');
                if (!details) return;

                const syncLabel = () => {
                    button.textContent = details.open ? 'Zwiń' : 'Rozwiń';
                    button.classList.toggle('bg-blue-500/20', !details.open);
                    button.classList.toggle('bg-blue-600/40', details.open);
                };

                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    details.open = !details.open;
                    syncLabel();
                });

                syncLabel();
            });
        });
    </script>
</x-app-layout>
