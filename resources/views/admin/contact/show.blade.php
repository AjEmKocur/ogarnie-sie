<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wiadomosc kontaktowa #{{ $message->id }}</h2>
            <a href="{{ route('admin.contact.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                Wroc do listy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona glowna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Wiadomosci kontaktowe', 'url' => route('admin.contact.index')],
                    ['label' => '#'.$message->id],
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

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Nie udalo sie zapisac zmian.</p>
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
                        <p class="text-sm text-slate-400">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                        <h1 class="mt-1 text-2xl font-bold">{{ $message->subject }}</h1>
                        <p class="mt-1 text-sm text-slate-400">{{ $message->name }} ({{ $message->email }})</p>
                        @if ($message->phone)
                            <p class="text-sm text-slate-400">Telefon: {{ $message->phone }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                            {{ $statuses[$message->status] ?? $message->status }}
                        </span>
                    </div>
                </div>
            </section>

            <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_360px]">
                <section class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-base font-semibold">Historia watku</h3>

                    <div class="mt-3 space-y-2">
                        @forelse ($message->entries as $entry)
                            @php
                                $isClient = $entry->sender_type === \App\Models\ContactMessageEntry::SENDER_CLIENT;
                            @endphp
                            <article class="rounded-lg border {{ $isClient ? 'border-blue-400/40 bg-blue-500/10' : 'border-emerald-400/40 bg-emerald-500/10' }} p-3">
                                <div class="flex flex-wrap items-center justify-between gap-2 text-xs {{ $isClient ? 'text-blue-200' : 'text-emerald-200' }}">
                                    <span class="font-semibold">
                                        {{ $isClient ? ($message->name ?: 'Klient') : ($entry->user?->name ?? 'Admin') }}
                                    </span>
                                    <span>{{ $entry->created_at?->format('Y-m-d H:i') }}</span>
                                </div>
                                <p class="mt-2 whitespace-pre-line text-sm {{ $isClient ? 'text-blue-50' : 'text-emerald-50' }}">{{ $entry->message }}</p>
                            </article>
                        @empty
                            <p class="text-sm text-slate-400">Brak wiadomosci w watku.</p>
                        @endforelse
                    </div>
                </section>

                <aside class="space-y-4">
                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Status</h3>
                        <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="mt-3 space-y-3">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="block w-full rounded-md border-gray-300 bg-slate-900 px-2 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $message->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="flex justify-end">
                                <x-primary-button>Zapisz status</x-primary-button>
                            </div>
                        </form>
                    </section>

                    <section class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-base font-semibold">Odpowiedz e-mail</h3>
                        <p class="mt-1 text-xs text-slate-400">Wysylka na: {{ $message->email }}</p>

                        <form method="POST" action="{{ route('admin.contact.reply', $message) }}" class="mt-3 space-y-3">
                            @csrf
                            <div>
                                <x-input-label for="reply_subject" :value="'Temat'" />
                                <input
                                    id="reply_subject"
                                    name="reply_subject"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-slate-900 text-slate-100 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('reply_subject', 'Re: '.$message->subject) }}"
                                    required
                                >
                            </div>

                            <div>
                                <x-input-label for="reply_message" :value="'Tresc odpowiedzi'" />
                                <textarea
                                    id="reply_message"
                                    name="reply_message"
                                    rows="7"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-slate-900 text-slate-100 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >{{ old('reply_message') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>Wyslij odpowiedz</x-primary-button>
                            </div>
                        </form>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
