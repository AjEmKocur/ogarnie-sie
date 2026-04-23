<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wiadomości kontaktowe</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Wiadomości kontaktowe'],
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

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="mb-3 text-xs uppercase tracking-wider text-slate-400">Filtr statusu</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.contact.index', ['status' => 'all']) }}"
                       class="rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === 'all' ? 'bg-blue-600 text-white' : 'border border-gray-300 text-slate-300 hover:bg-slate-800' }}">
                        Wszystkie
                    </a>
                    @foreach ($statuses as $value => $label)
                        <a href="{{ route('admin.contact.index', ['status' => $value]) }}"
                           class="rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === $value ? 'bg-blue-600 text-white' : 'border border-gray-300 text-slate-300 hover:bg-slate-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($messages->isEmpty())
                        <p>Brak wiadomości kontaktowych dla wybranego filtra.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($messages as $message)
                                <article class="rounded-xl border border-gray-200 bg-slate-900/40 p-4">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm text-slate-400">#{{ $message->id }} · {{ $message->created_at->format('Y-m-d H:i') }}</p>
                                            <p class="mt-1 text-lg font-semibold">
                                                <a href="{{ route('admin.contact.show', $message) }}" class="hover:text-blue-300">
                                                    {{ $message->subject }}
                                                </a>
                                            </p>
                                            <p class="text-sm text-slate-400">
                                                {{ $message->name }} ({{ $message->email }})
                                                @if ($message->phone)
                                                    · {{ $message->phone }}
                                                @endif
                                            </p>
                                            <p class="mt-2 text-sm text-slate-200">{{ \Illuminate\Support\Str::limit($message->message, 180) }}</p>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$message->status] ?? 'bg-gray-500/20 text-gray-200 border border-gray-400/30' }}">
                                                {{ $statuses[$message->status] ?? $message->status }}
                                            </span>

                                            <form method="POST" action="{{ route('admin.contact.update', $message) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="block rounded-md border-gray-300 bg-slate-900 px-2 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @foreach ($statuses as $value => $label)
                                                        <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <x-primary-button>Zapisz</x-primary-button>
                                            </form>

                                            <a href="{{ route('admin.contact.show', $message) }}"
                                               class="inline-flex items-center rounded-md border border-blue-400/50 bg-blue-500/20 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-100 transition hover:bg-blue-500/30">
                                                Otwórz szczegóły
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
