<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Moderacja opinii</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            @forelse ($testimonials as $testimonial)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Zgłoszenie #{{ $testimonial->ticket_id }}</p>
                            <p class="mt-1 font-semibold">{{ $testimonial->user->name }}</p>
                            <p class="mt-2 text-yellow-300">{{ str_repeat('★', (int) $testimonial->rating) }}{{ str_repeat('☆', max(0, 5 - (int) $testimonial->rating)) }}</p>
                            <p class="mt-3 max-w-3xl">{{ $testimonial->content }}</p>
                        </div>
                        <div class="text-right text-sm">
                            <p>Status:
                                <span class="{{ $testimonial->is_approved ? 'text-green-400' : 'text-amber-300' }}">
                                    {{ $testimonial->is_approved ? 'Opublikowana' : 'Oczekuje' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_approved" value="{{ $testimonial->is_approved ? 0 : 1 }}">
                            <button class="rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                                {{ $testimonial->is_approved ? 'Ukryj' : 'Opublikuj' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Usunąć opinię?');">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-md border border-red-500/50 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-red-300 hover:bg-red-900/20">
                                Usuń
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    Brak opinii do moderacji.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

