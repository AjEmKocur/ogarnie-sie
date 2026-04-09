<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wystaw opinię</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
            @if ($eligibleTickets->isEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p>Brak zakończonych zgłoszeń, dla których można wystawić opinię.</p>
                </div>
            @else
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <form method="POST" action="{{ route('client.testimonials.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="mb-1 block text-sm font-medium">Zakończone zgłoszenie</label>
                            <select name="ticket_id" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" required>
                                <option value="">Wybierz zgłoszenie</option>
                                @foreach ($eligibleTickets as $ticket)
                                    <option value="{{ $ticket->id }}" @selected((int) old('ticket_id', $preselectedTicketId) === $ticket->id)>
                                        #{{ $ticket->id }} - {{ $ticket->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('ticket_id')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium">Ocena</label>
                            <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">

                            <div id="rating-stars" class="flex items-center gap-2 text-3xl select-none">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button
                                        type="button"
                                        data-value="{{ $i }}"
                                        class="rating-star text-slate-600 transition duration-150 hover:-translate-y-0.5 hover:text-blue-300"
                                        aria-label="Oceń na {{ $i }} gwiazdek"
                                    >
                                        ★
                                    </button>
                                @endfor
                                <span id="rating-label" class="ms-2 text-sm text-slate-400"></span>
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Twoja opinia</label>
                            <textarea name="content" rows="6" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" placeholder="Napisz, jak oceniasz realizację usługi..." required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>Wyślij opinię</x-primary-button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('rating');
            const stars = Array.from(document.querySelectorAll('.rating-star'));
            const label = document.getElementById('rating-label');
            let current = Number(input?.value || 0);

            const render = (value) => {
                stars.forEach((star, index) => {
                    const active = index < value;
                    star.classList.toggle('text-blue-300', active);
                    star.classList.toggle('text-slate-600', !active);
                });

                if (label) {
                    label.textContent = value > 0 ? `${value}/5` : 'Wybierz ocenę';
                }
            };

            stars.forEach((star) => {
                star.addEventListener('click', () => {
                    const value = Number(star.dataset.value || 0);
                    current = value;
                    input.value = String(value);
                    render(current);
                });

                star.addEventListener('mouseenter', () => {
                    const value = Number(star.dataset.value || 0);
                    render(value);
                });
            });

            const wrapper = document.getElementById('rating-stars');
            wrapper?.addEventListener('mouseleave', () => render(current));

            render(current);
        });
    </script>
</x-app-layout>

