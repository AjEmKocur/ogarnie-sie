<x-app-layout>
    @php
        $badgeClasses = [
            'new' => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
            'replied' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Wiadomość kontaktowa') }}
            </h2>
            <a href="{{ route('admin.contact.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                Wróć do listy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Wiadomości kontaktowe', 'url' => route('admin.contact.index')],
                    ['label' => $message->subject],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Nie udało się zapisać zmian.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-400">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                        <h1 class="mt-1 text-2xl font-bold">{{ $message->subject }}</h1>
                        <p class="mt-1 text-sm text-slate-300">
                            {{ $message->name }} ({{ $message->email }})
                            @if ($message->phone)
                                - {{ $message->phone }}
                            @endif
                        </p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                        {{ $statuses[$message->status] ?? $message->status }}
                    </span>
                </div>

                <div class="mt-4 rounded-lg border border-gray-200 bg-slate-900/40 p-4">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Treść wiadomości</p>
                    <p class="mt-2 whitespace-pre-line text-sm text-slate-200">{{ $message->message }}</p>
                </div>
            </section>

            <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_320px]">
                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="text-base font-semibold">Odpowiedź</h3>

                    <form method="POST" action="{{ route('admin.contact.reply', $message) }}" class="mt-3 space-y-2">
                        @csrf
                        <input
                            type="text"
                            name="reply_subject"
                            value="{{ old('reply_subject', 'Re: '.$message->subject) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Temat odpowiedzi"
                            required
                        >
                        <textarea
                            name="reply_message"
                            rows="7"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Treść odpowiedzi..."
                            required
                        >{{ old('reply_message') }}</textarea>
                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Wyślij odpowiedź') }}</x-primary-button>
                        </div>
                    </form>
                </section>

                <aside class="space-y-3">
                    <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold">Status</h3>
                        <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="mt-2 space-y-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-primary-button class="w-full justify-center">{{ __('Zapisz status') }}</x-primary-button>
                        </form>
                    </section>

                    <section class="rounded-xl border border-rose-300/30 bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-rose-200">Usuń wiadomość</h3>
                        <form method="POST" action="{{ route('admin.contact.destroy', $message) }}" class="mt-2" onsubmit="return confirm('Na pewno usunąć tę wiadomość?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button class="w-full justify-center">{{ __('Usuń wiadomość') }}</x-danger-button>
                        </form>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
