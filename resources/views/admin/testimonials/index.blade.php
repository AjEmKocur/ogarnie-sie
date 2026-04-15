<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Moderacja opinii</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Opinie klientów'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-400/40 bg-green-500/10 p-4 text-green-200">{{ session('status') }}</div>
            @endif

            <div class="rounded-xl border border-blue-400/30 bg-slate-900/60 p-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.testimonials.index', ['status' => 'all']) }}"
                       class="rounded-md border px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === 'all' ? 'border-blue-400 bg-blue-500/20 text-blue-100' : 'border-slate-600 text-slate-200 hover:bg-slate-800' }}">
                        Wszystkie
                    </a>
                    @foreach ($moderationStatuses as $key => $label)
                        <a href="{{ route('admin.testimonials.index', ['status' => $key]) }}"
                           class="rounded-md border px-3 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusFilter === $key ? 'border-blue-400 bg-blue-500/20 text-blue-100' : 'border-slate-600 text-slate-200 hover:bg-slate-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @forelse ($testimonials as $testimonial)
                <div class="rounded-xl border border-blue-400/30 bg-slate-900/60 p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Zgłoszenie #{{ $testimonial->ticket_id }}</p>
                            <p class="mt-1 font-semibold text-slate-100">{{ $testimonial->user->name }}</p>
                            <p class="mt-2 text-yellow-300">{{ str_repeat('★', (int) $testimonial->rating) }}{{ str_repeat('☆', max(0, 5 - (int) $testimonial->rating)) }}</p>
                            <p class="mt-3 max-w-3xl text-slate-100">{{ $testimonial->content }}</p>
                        </div>
                        <div class="text-right text-sm">
                            <p class="text-slate-300">Status AI:
                                <span class="@class([
                                    'font-semibold',
                                    'text-green-300' => $testimonial->moderation_status === 'approve',
                                    'text-amber-300' => $testimonial->moderation_status === 'review',
                                    'text-red-300' => $testimonial->moderation_status === 'reject',
                                ])">
                                    {{ $testimonial->moderationStatusLabel() }}
                                </span>
                            </p>
                            <p class="mt-1 text-slate-400">Score ryzyka: {{ $testimonial->moderation_score ?? 0 }}/100</p>
                            <p class="mt-1 text-slate-400">Publiczna: {{ $testimonial->is_approved ? 'Tak' : 'Nie' }}</p>
                        </div>
                    </div>

                    @if (! empty($testimonial->moderation_reasons))
                        <div class="mt-4 rounded-lg border border-slate-700 bg-slate-950/60 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Powody decyzji automatycznej</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-slate-200">
                                @foreach ($testimonial->moderation_reasons as $reason)
                                    <li>{{ $reason }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-4 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="approve">
                            <button class="rounded-md border border-green-500/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-green-200 hover:bg-green-900/20">
                                Opublikuj
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="review">
                            <button class="rounded-md border border-amber-500/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-900/20">
                                Do weryfikacji
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="reject">
                            <button class="rounded-md border border-red-500/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-red-200 hover:bg-red-900/20">
                                Odrzuć
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Usunąć opinię?');">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-md border border-slate-600 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                                Usuń
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-6 text-slate-200">
                    Brak opinii do moderacji.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
