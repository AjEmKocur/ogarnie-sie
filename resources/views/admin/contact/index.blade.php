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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-6 text-gray-900">
                    @if ($messages->isEmpty())
                        <p>Brak wiadomości kontaktowych.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($messages as $message)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <p class="font-semibold">{{ $message->subject }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $message->name }} ({{ $message->email }})
                                                @if ($message->phone)
                                                    - {{ $message->phone }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                            {{ $statuses[$message->status] ?? $message->status }}
                                        </span>
                                    </div>

                                    <p class="mb-4 whitespace-pre-line text-sm text-gray-700">{{ $message->message }}</p>

                                    <div class="space-y-3 border-t border-gray-200 pt-4">
                                        <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="flex items-center justify-end gap-3">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach ($statuses as $value => $label)
                                                    <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <x-primary-button>{{ __('Zapisz status') }}</x-primary-button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.contact.reply', $message) }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="contact_message_id" value="{{ $message->id }}">
                                            <input
                                                type="text"
                                                name="reply_subject"
                                                value="{{ old('contact_message_id') == $message->id ? old('reply_subject') : 'Re: '.$message->subject }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Temat odpowiedzi"
                                                required
                                            >
                                            <textarea
                                                name="reply_message"
                                                rows="4"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Treść odpowiedzi..."
                                                required
                                            >{{ old('contact_message_id') == $message->id ? old('reply_message') : '' }}</textarea>
                                            <div class="flex justify-end">
                                                <x-primary-button>{{ __('Wyślij odpowiedź') }}</x-primary-button>
                                            </div>
                                        </form>

                                        <form method="POST" action="{{ route('admin.contact.destroy', $message) }}" class="flex justify-start" onsubmit="return confirm('Na pewno usunąć tę wiadomość?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>{{ __('Usuń wiadomość') }}</x-danger-button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
