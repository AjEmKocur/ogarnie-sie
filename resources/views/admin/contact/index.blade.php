<x-app-layout>
    @php
        $badgeClasses = [
            'new' => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
            'replied' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30',
        ];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wiadomości kontaktowe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Wiadomości kontaktowe'],
                ],
            ])

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Nie udało się zapisać zmian.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-2">
                <a href="#lista-wiadomosci" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                    Lista wiadomości
                </a>
            </div>

            <div id="lista-wiadomosci" class="space-y-4">
                @if ($messages->isEmpty())
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p>Brak wiadomości kontaktowych.</p>
                    </div>
                @else
                    @foreach ($messages as $message)
                        @php
                            $isCurrentOldMessage = (int) old('contact_message_id') === $message->id;
                        @endphp
                        <details id="message-{{ $message->id }}" class="rounded-xl border border-gray-200 bg-white shadow-sm" @if($isCurrentOldMessage) open @endif>
                            <summary class="cursor-pointer list-none px-5 py-4">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold">{{ $message->subject }}</p>
                                        <p class="mt-1 truncate text-sm text-slate-400">
                                            {{ $message->name }} ({{ $message->email }})
                                            @if ($message->phone)
                                                - {{ $message->phone }}
                                            @endif
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                            {{ $statuses[$message->status] ?? $message->status }}
                                        </span>
                                    </div>
                                </div>
                                <p class="mt-3 line-clamp-2 text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($message->message, 180) }}</p>
                            </summary>

                            <div class="space-y-4 border-t border-gray-200 p-5">
                                <div class="rounded-lg border border-gray-200 bg-slate-900/40 p-4">
                                    <p class="text-xs uppercase tracking-wider text-slate-400">Treść wiadomości</p>
                                    <p class="mt-2 whitespace-pre-line text-sm text-slate-200">{{ $message->message }}</p>
                                </div>

                                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
                                    <form method="POST" action="{{ route('admin.contact.reply', $message) }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="contact_message_id" value="{{ $message->id }}">
                                        <input
                                            type="text"
                                            name="reply_subject"
                                            value="{{ $isCurrentOldMessage ? old('reply_subject') : 'Re: '.$message->subject }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Temat odpowiedzi"
                                            required
                                        >
                                        <textarea
                                            name="reply_message"
                                            rows="5"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Treść odpowiedzi..."
                                            required
                                        >{{ $isCurrentOldMessage ? old('reply_message') : '' }}</textarea>
                                        <div class="flex justify-end">
                                            <x-primary-button>{{ __('Wyślij odpowiedź') }}</x-primary-button>
                                        </div>
                                    </form>

                                    <div class="space-y-3">
                                        <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="space-y-2 rounded-lg border border-gray-200 p-3">
                                            @csrf
                                            @method('PATCH')
                                            <label class="block text-xs uppercase tracking-wider text-slate-400">Status</label>
                                            <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach ($statuses as $value => $label)
                                                    <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <x-primary-button class="w-full justify-center">{{ __('Zapisz status') }}</x-primary-button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.contact.destroy', $message) }}" class="rounded-lg border border-rose-300/30 p-3" onsubmit="return confirm('Na pewno usunąć tę wiadomość?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button class="w-full justify-center">{{ __('Usuń wiadomość') }}</x-danger-button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
