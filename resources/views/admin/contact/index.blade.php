<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wiadomości kontaktowe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
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

                                    <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="flex items-center justify-end gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach ($statuses as $value => $label)
                                                <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-primary-button>{{ __('Zapisz') }}</x-primary-button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
