<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wiadomość kontaktowa #{{ $message->id }}</h2>
            <a href="{{ route('admin.contact.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
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
                    ['label' => 'Wiadomości kontaktowe', 'url' => route('admin.contact.index')],
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
                    <p class="font-semibold">Nie udało się zapisać zmian.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_360px]">
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
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                            {{ $statuses[$message->status] ?? $message->status }}
                        </span>
                    </div>

                    <div class="mt-5 rounded-md border border-gray-200 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Treść wiadomości</p>
                        <p class="mt-2 whitespace-pre-line text-sm">{{ $message->message }}</p>
                    </div>

                    @if ($message->reply_message)
                        <div class="mt-4 rounded-md border border-emerald-400/30 bg-emerald-500/10 p-4">
                            <p class="text-xs uppercase tracking-wider text-emerald-200">Ostatnia odpowiedź</p>
                            <p class="mt-1 text-sm text-emerald-100">
                                Temat: {{ $message->reply_subject ?? '-' }}
                            </p>
                            <p class="mt-2 whitespace-pre-line text-sm text-emerald-100">{{ $message->reply_message }}</p>
                            <p class="mt-2 text-xs text-emerald-200">
                                @if ($message->replied_at)
                                    Wysłano: {{ $message->replied_at->format('Y-m-d H:i') }}
                                @endif
                                @if ($message->relationLoaded('repliedByUser') && $message->repliedByUser)
                                    · przez {{ $message->repliedByUser->name }}
                                @endif
                            </p>
                        </div>
                    @endif
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
                        <p class="mt-1 text-xs text-slate-400">Odpowiedź zostanie wysłana na: {{ $message->email }}</p>

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
                                <x-input-label for="reply_message" :value="'Treść odpowiedzi'" />
                                <textarea
                                    id="reply_message"
                                    name="reply_message"
                                    rows="7"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-slate-900 text-slate-100 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >{{ old('reply_message') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>Wyślij odpowiedź</x-primary-button>
                            </div>
                        </form>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
